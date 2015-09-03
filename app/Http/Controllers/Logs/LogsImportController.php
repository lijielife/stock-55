<?php

namespace App\Http\Controllers\Logs;

//use App\Http\Controllers\AdminsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Models\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MasSide;
use App\Models\MasBroker;
use App\Models\MasSymbol;
use App\Models\DataLog;
use Illuminate\Support\Facades\DB;
class LogsImportController extends Controller {

    public function getIndex() {
        
        $dataLogs = $this->getLastDataLog();
//        
        return view('logs.import', ['dataLogs' => $dataLogs]);
//        return view('logs.import');
    }

    private function getLastDataLog(){
         $dataLogs = DB::select('SELECT ms.side_name as side, msy.symbol, dl.volume, dl.price, dl.amount, dl.vat
            , dl.at_pay, dl.net_amount, dl.date, mbk.broker_name  as broker, us.name
            FROM DATA_LOG dl
            LEFT JOIN MAS_SYMBOL msy ON (dl.SYMBOL_ID = msy.ID)
            LEFT JOIN MAS_BROKER mbk ON (dl.BROKER_ID = mbk.ID)
            LEFT JOIN MAS_SIDE ms ON (dl.SIDE_ID = ms.ID)
            LEFT JOIN USERS us ON (dl.USER_ID = us.ID)
            WHERE dl.CREATED_AT = (
                    SELECT MAX(CREATED_AT) FROM DATA_LOG WHERE UPDATED_AT IS NOT NULL
            ) 
            AND dl.USER_ID = ?
            ORDER BY SYMBOL, BROKER, VOLUME', [$this->USER_ID]);
         
//         var_dump($dataLogs);
         
         return $dataLogs;
    }
    
    public function postAction(Request $request) {
        if ($request->exists('btn-upload')) {
            $file = $request->file('uploader');
            $path = 'images/uploads';
            $filename = $file->getClientOriginalName();
            $pathExcelFile = $file->move('images/uploads', $file->getClientOriginalName());

            $this->readExcel($pathExcelFile);

//            $this->readSymbol($pathExcelFile);


//            $image = new Images;
//            $image->image_name = $filename;
//            $image->save();
//            echo 'Uploaded';
        }
        return redirect()->back();
    }

    private function readSymbol($pathExcelFile) {

        Excel::selectSheets('import_data')
                ->batch(array($pathExcelFile), function($rows, $files) {

//                    $rows->formatDates(true, 'Y-m-d');
                    $rows->each(function($row) {

                        $symbol = $row->symbol;
                        $market = $row->market;

                        $symbolVo = MasSymbol::firstOrCreate(["SYMBOL" => $symbol]);
                        unset($symbolVo->ID);
                        $symbolVo->MARKET = strtoupper($market);
                        $symbolVo->IS_SET = (strtoupper($market) == "SET" ? true : false);
                        $symbolVo->IS_DW = false;
                        $symbolVo->save();
                    });
                });
    }

    private function readExcel($pathExcelFile) {


        $masSides = array();
        foreach (MasSide::get() as $masSide) {
            $masSides[$masSide->SIDE_NAME] = $masSide->ID;
        }

        $masBrokers = array();
        foreach (MasBroker::get() as $masBroker) {
            $masBrokers[$masBroker->BROKER_NAME] = $masBroker->ID;
        }

        $masSymbols = array();
        foreach (MasSymbol::get() as $masSymbol) {
            $masSymbols[$masSymbol->SYMBOL] = $masSymbol->ID;
        }


        $dataLogs = array();
        Excel::selectSheets('import_data')
                ->batch(array($pathExcelFile), function($rows, $files)
                        use( &$masSides, &$masBrokers, &$masSymbols, &$dataLogs) {

                    $rows->formatDates(true, 'Y-m-d');
                    $rows->each(function($row)
                            use( &$masSides, &$masBrokers, &$masSymbols, &$dataLogs) {

                        $side = $row->side_id;
                        $symbol = $row->symbol_id;
                        $broker = $row->broker_id;
                        $dw = ($row->is_dw ? true : false);

                        $dataLog = (array) $row->toArray();
                        unset($dataLog["id"]);
                        unset($dataLog["is_dw"]);

                        if (isset($side) && $side != "" && isset($symbol) && $symbol != "" && isset($broker) && $broker != "") {

                            if (!array_key_exists($symbol, $masSymbols)) {
                                $symbolVo = MasSymbol::firstOrCreate(array("SYMBOL" => $symbol));
                                $masSymbols[$symbolVo->SYMBOL] = $symbolVo->id;

                                // ถ้ามี "-" แปลว่าเป็น W
                                if (strpos($symbol, '-')) {
                                    // หาชื่อตัวแม่
                                    $parentSymbolName = substr($symbol, 0, strpos($symbol, "-"));
                                    $parentSymbolVo = MasSymbol::firstOrCreate(array("SYMBOL" => $parentSymbolName));
                                    
                                    // copy ParentValue To $symbolVo
                                    $symbolVo->MARKET = $parentSymbolVo->MARKET;
                                    $symbolVo->IS_SET = $parentSymbolVo->IS_SET;
                                    $symbolVo->IS_USE = true;
                                    $symbolVo->IS_W = true;
                                    $symbolVo->IS_DW = false;
                                    $symbolVo->save();
                                } else if(strlen($symbol) > 8){
                                    $symbolVo->IS_USE = true;
                                    $symbolVo->IS_W = false;
                                    $symbolVo->IS_DW = true;
                                    $symbolVo->save();
                                }
                            }

                            if (array_key_exists($side, $masSides) && array_key_exists($broker, $masBrokers) && array_key_exists($symbol, $masSymbols)) {
                                $sideId = $masSides[$side];
                                $brokerId = $masBrokers[$broker];
                                $symbolId = $masSymbols[$symbol];
//                                $dataLog["is_dw"] = $dw;

                                $userId = $this->USER_ID;
                                $dataLog["side_id"] = $sideId;
                                $dataLog["broker_id"] = $brokerId;
                                $dataLog["symbol_id"] = $symbolId;
                                $dataLog["user_id"] = $userId;

                                $dataLog["updated_at"] = date("Y-m-d H:i:s");
                                $dataLog["updated_by"] = $userId;
                                $dataLog["created_at"] = date("Y-m-d H:i:s");
                                $dataLog["created_by"] = $userId;

                                array_push($dataLogs, $dataLog);
                            } else {
                                return;
                            }
                        }
                    });
                });

//        foreach ($dataLogs as $dataLog) {
            
//            DataLog::insert($dataLog);
//        }
        DataLog::insert($dataLogs);
    }

}
