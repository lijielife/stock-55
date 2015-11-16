<?php

namespace App\Http\Controllers\Logs;

//use App\Http\Controllers\AdminsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Contracts\Logging\Log;
use Log;
//use App\Models\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MasSide;
use App\Models\MasBroker;
use App\Models\MasSymbol;
use App\Models\DataLog;
//use Illuminate\Support\Facades\Request;
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
            ORDER BY date, SYMBOL, BROKER, VOLUME', [$this->USER_ID]);
         
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

    public function getMasSide() {
        $masSides = array();
        foreach (MasSide::get() as $masSide) {
            $masSides[$masSide->SIDE_NAME] = $masSide->ID;
        }
        return $masSides;
    }
    
    public function getMasSideCode() {
        $masSides = array();
        foreach (MasSide::get() as $masSide) {
            $masSides[$masSide->SIDE_CODE] = $masSide->SIDE_NAME;
        }
        return $masSides;
    }
    
    public function getMasBrokers() {
        $masBrokers = array();
        foreach (MasBroker::get() as $masBroker) {
            $masBrokers[$masBroker->BROKER_NAME] = $masBroker->ID;
        }
        return $masBrokers;
    }
    
    public function getMasBrokersCode() {
        $masBrokers = array();
        foreach (MasBroker::get() as $masBroker) {
            $masBrokers[$masBroker->BROKER_CODE] = $masBroker->ID;
        }
        return $masBrokers;
    }
    
    public function getMasSymbols() {
        $masSymbols = array();
        foreach (MasSymbol::get() as $masSymbol) {
            $masSymbols[$masSymbol->SYMBOL] = $masSymbol->ID;
        }
        return $masSymbols;
    }
    
    private function readExcel($pathExcelFile) {

        $masSides = $this->getMasSide();

        $masBrokers = $this->getMasBrokers();

        $masSymbols = $this->getMasSymbols();

//        $this->deleteTestAll();
        
        $dataLogs = array();
        Excel::selectSheets('import_data')
            ->batch(array($pathExcelFile), function($rows, $files)
                    use( &$masSides, &$masBrokers, &$masSymbols, &$dataLogs) {

                $rows->formatDates(true, 'Y-m-d');
                $rows->each(function($row)
                        use( &$masSides, &$masBrokers, &$masSymbols, &$dataLogs) {
                    
                    LogsImportController::genDataLogs($row->toArray(), $dataLogs, $this->USER_ID, $masSymbols, $masSides, $masBrokers);
                    
                });
            });
        $this->insertDataLogs($dataLogs);
    }
    
    public static function setAuditable(&$bean, $userId) {
        
        $bean["updated_at"] = date("Y-m-d H:i:s");
        $bean["updated_by"] = $userId;
        $bean["created_at"] = date("Y-m-d H:i:s");
        $bean["created_by"] = $userId;
    }

    private function insertDataLogs($dataLogs){
        
        DataLog::insert($dataLogs);
        
        DB::statement('UPDATE `super_stock_db`.`data_log`
            SET HASH_MD = 
            MD5(concat(SIDE_ID, SYMBOL_ID, VOLUME, PRICE, 
                AMOUNT, VAT, at_pay, NET_AMOUNT, DATE, 
                DUE_DATE, BROKER_ID, MAP_VOL, MAP_AVG, 
                IS_DW, USER_ID))
            WHERE HASH_MD IS NULL');
    }
    
    public function deleteTest(){
        
        $symbol = $this->getRequestParam('symbol');
        if($symbol){
            $symbolDB = MasSymbol::where('SYMBOL', $symbol)->select("ID")->first();
            $brokerDB = MasBroker::where('BROKER_CODE', '004')->select("ID")->first();
            $symbolId = $symbolDB->ID;   
            $brokerId = $brokerDB->ID;
            DataLog::where('broker_id', $brokerId)
                    ->where('symbol_id', $symbolId)
                    ->where('USER_ID', $this->USER_ID)
                    ->delete();
        }
    }
    
    
    public function deleteTestAll(){
            $brokerDB = MasBroker::where('BROKER_CODE', '004')->select("ID")->first();
            $brokerId = $brokerDB->ID;
            DataLog::where('broker_id', $brokerId)
                    ->where('USER_ID', $this->USER_ID)
                    ->delete();
    }
    
    
    public function saveDataLogs(){
        $tts = $this->getRequestParam('tt');
        if(is_array($tts)){
            $masSides = $this->getMasSide();
            $masSidesCode = $this->getMasSideCode();
            $masBrokers = $this->getMasBrokers();
            $masSymbols = $this->getMasSymbols();
            $dataLogs = array();

            foreach ($tts as $tt) {
                $row = array();
//                $row = new \stdClass();
    //            $row = (object) $tt;
                $sideCode = $tt["SIDE_CODE"];            
                if(!array_key_exists($sideCode, $masSidesCode) ){
                    continue;
                } 
                $row["side_id"] = $masSidesCode[$sideCode];
                $row["symbol_id"] = $tt["SYMBOL"];
                $row["broker_id"] = "TEST";
                $price = $tt["PRICE"];
                $volume = $tt["VOLUME"];
                $row["price"] = $price;
                $row["volume"] = $volume;
                $row["amount"] = LogsProfileController::getPriceTotal($volume, $price);
                $row["vat"] = LogsTotalController::getVat($row["amount"]);
                $row["net_amount"] = $tt["NET_AMOUNT"];
                $row["due_date"] = $row["date"] = $tt["DATE"];
                LogsImportController::genDataLogs($row, $dataLogs, $this->USER_ID, $masSymbols, $masSides, $masBrokers);
            }

            $this->insertDataLogs($dataLogs);
            
            return "success";
        }
        return "error";
    }
    
    private static function genDataLogs($row, &$dataLogs, $userId, $masSymbols, $masSides, $masBrokers){
        $side = $row["side_id"];
        if(!$side) {
            return;
        }
        $symbol = (string)$row["symbol_id"];
        $broker = $row["broker_id"];
//                        $dw = ($row->is_dw ? true : false);

        $dataLog = (array) $row;
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

            if (array_key_exists($side, $masSides) 
                    && array_key_exists($broker, $masBrokers) 
                    && array_key_exists($symbol, $masSymbols)) {
                $sideId = $masSides[$side];
                $brokerId = $masBrokers[$broker];
                $symbolId = $masSymbols[$symbol];
                if(!array_key_exists($symbol, $masSymbols)){
                    Log::info('$symbol = ' . $symbol);
                }
                
                $dataLog["side_id"] = $sideId;
                $dataLog["broker_id"] = $brokerId;
                $dataLog["symbol_id"] = $symbolId;
                $dataLog["user_id"] = $userId;
                
                if(!isset($dataLog["due_date"]) || $dataLog["due_date"] == null){
                    $dataLog["due_date"] = $dataLog["date"];
                }
                LogsImportController::setAuditable($dataLog, $userId);

                array_push($dataLogs, $dataLog);
            } else {
                return;
            }
        }
    }
}
