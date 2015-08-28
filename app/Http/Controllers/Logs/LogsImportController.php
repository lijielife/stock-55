<?php

namespace App\Http\Controllers\Logs;

//use App\Http\Controllers\AdminsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MasSide;
use App\Models\MasBroker;
use App\Models\SymbolName;
use App\Models\DataLog;

class LogsImportController extends Controller {

    public function getIndex() {
//		$images = Images::get();
//		return view('admin.upload.index');

        return view('logs.import');
    }

    public function postAction(Request $request) {
        if ($request->exists('btn-upload')) {
            $file = $request->file('uploader');
            $path = 'images/uploads';
            $filename = $file->getClientOriginalName();
            $pathExcelFile = $file->move('images/uploads', $file->getClientOriginalName());

            $this->readExcel($pathExcelFile);


            $image = new Images;
            $image->image_name = $filename;
            $image->save();
            echo 'Uploaded';
        }
        return redirect()->back();
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

        $symbolNames = array("1-1" => "123");
        foreach (SymbolName::get() as $symbolName) {
            $symbolNames[$symbolName->SYMBOL] = $symbolName->ID;
        }


        $dataLogs = array();
        Excel::selectSheets('import_data')
            ->batch(array($pathExcelFile), function($rows, $files)
                use( &$masSides, &$masBrokers, &$symbolNames, &$dataLogs) {

            $rows->formatDates(true, 'Y-m-d');
            $rows->each(function($row)
                    use( &$masSides, &$masBrokers, &$symbolNames, &$dataLogs) {

                $symbolNamesIn = $symbolNames;
//                        $id = $row->id;
                $side = $row->side_id;
                $symbol = $row->symbol_id;
//                        $volume = $row->volume;
//                        $price = $row->price;
//                        $amount = $row->amount;
//                        $vat = $row->vat;
//                        $atpay = $row->atpay;
//                        $netamount = $row->net_amount;
//                        $date = $row->date->format('Y-m-d');;
                $broker = $row->broker_id;
                        $dw = ($row->is_dw ? true : false);

                        if($row->is_dw || $row->is_dw == "true"){
                            $dw = true;
                        }
                        
                        
                        if($row->is_dw || $row->is_dw == true){
                            $dw = true;
                        }
                        
                        
                        if($row->is_dw || $row->is_dw == "TRUE"){
                            $dw = true;
                        }
                        
                $dataLog = (array) $row->toArray();

                unset($dataLog["id"]);
                
                if (isset($side) && $side != ""
                        && isset($symbol)  && $symbol != ""
                        && isset($broker)  && $broker != ""
                        && isset($dw) && $dw != "") {

                    if (!array_key_exists($symbol, $symbolNamesIn)) {
//                        $symbolName = new SymbolName();
//                        $symbolName->SYMBOL = $symbol;
//                        $symbolName->IS_USE = 0;
//                        $symbolName->created_at = date("Y-m-d H:i:s");
//                        $symbolName->updated_at = date("Y-m-d H:i:s");
                        
                        $symbolVo = SymbolName::create(array("SYMBOL" => $symbol));
                        
                        $symbolNamesIn[$symbolVo->SYMBOL] = $symbolVo->ID;
                        $symbolNamesIn[$symbolVo["SYMBOL"]] = $symbolVo["ID"];
                        $symbolNamesIn[$symbol] = $symbolVo->ID;
                        $symbolVo->SYMBOL = $symbol;
//                        SymbolName::create(array())
                    }
                    
                    
                    if (array_key_exists($side, $masSides) && array_key_exists($broker, $masBrokers) && array_key_exists($symbol, $symbolNamesIn)) {
                        $sideId = $masSides[$side];
                        $brokerId = $masBrokers[$broker];
                        $symbolId = $symbolNamesIn[$symbol];
                        $dataLog["is_dw"] = $dw;
//                                $dataLog["date"]

                        $userId  = 1;
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

        
        DataLog::insert($dataLogs);
        
//        DB::transaction(function() use( &$dataLogs ) {
//            foreach (array_chunk($dataLogs, 1000) as $dataLog) {
//                DataLog::insert($dataLog);
//            }
//        });



//        Excel::load($pathExcelFile, function($reader) {
//            $data = array();
//            foreach ($reader as $key => $value) {
//                $data[$key] = $value;
//            }
        // Getting all results
//            $results = $reader->get();
        // ->all() is a wrapper for ->get() and will work the same
//            $results = $reader->all();
//            $data = $reader->toArray();
//            $dataObj = $reader->toObject();
//            $reader->each(function($sheet) {
        // Loop through all rows
//                $sheet->each(function($row) {
//                });
//            });
//        })->get();
//
//        try {
//            $objPHPExcel = PHPExcel_IOFactory::load($filename);
//        } catch (Exception $e) {
//            die('Error loading file "' . pathinfo($filename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
//        }
//
//        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
//        $arrayCount = count($allDataInSheet); // Here get total count of row in that Excel sheet 
//        for ($i = 2; $i <= $arrayCount; $i++) {
//            $userName = trim($allDataInSheet[$i]["A"]);
//            $userMobile = trim($allDataInSheet[$i]["B"]);
//            $query = "SELECT name FROM YOUR_TABLE WHERE name = '" . $userName . "' and email = '" . $userMobile . "'";
//            $sql = mysql_query($query);
//            $recResult = mysql_fetch_array($sql);
//            $existName = $recResult["name"];
//            if ($existName == "") {
//                $insertTable = mysql_query("insert into YOUR_TABLE (name, email) values('" . $userName . "', '" . $userMobile . "');");
//                $msg = 'Record has been added. <div style="Padding:20px 0 0 0;"><a href="http://www.discussdesk.com/import-excel-file-data-in-mysql-database-using-PHP.htm" target="_blank">Go Back to tutorial</a></div>';
//            } else {
//                $msg = 'Record already exist. <div style="Padding:20px 0 0 0;"><a href="http://www.discussdesk.com/import-excel-file-data-in-mysql-database-using-PHP.htm" target="_blank">Go Back to tutorial</a></div>';
//            }
//        }
//        echo "<div style='font: bold 18px arial,verdana;padding: 45px 0 0 500px;'>" . $msg . "</div>";
    }

}
