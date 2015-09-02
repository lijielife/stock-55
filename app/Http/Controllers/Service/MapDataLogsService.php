<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use App\Models\DataLog;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
//
//use App\Models\MasSymbol;
//
//use App\Models\History;
use App\Beans\MapDataLogsGetAllDataBean;
use App\Beans\DataLogBean;
class MapDataLogsService extends Controller {

    public function getAllData($param = null) {


       $dataLogs = DB::select(
        "SELECT 
	da.`ID` as ID_SRC , da.`SIDE_ID` as SIDE_ID_SRC , da.`SYMBOL_ID` as SYMBOL_ID_SRC
	, da.`VOLUME` as VOLUME_SRC, da.`PRICE` as PRICE_SRC, da.`AMOUNT` as AMOUNT_SRC, da.`VAT` as VAT_SRC
	, da.`NET_AMOUNT` as NET_AMOUNT_SRC , da.`DATE` as DATE_SRC , da.`BROKER_ID` as BROKER_ID_SRC 
  
        , ma.`ID`as MAP_ID , ma.`MAP_SRC` , ma.`MAP_DESC` , ma.`MAP_VOL` 
  
	,dad.`ID` as ID_DESC , dad.`SIDE_ID` as SIDE_ID_SRC , dad.`SYMBOL_ID` as SYMBOL_ID_DESC
	, dad.`VOLUME` as VOLUME_DESC, dad.`PRICE` as PRICE_DESC, dad.`AMOUNT` as AMOUNT_DESC, dad.`VAT` as VAT_DESC
	, dad.`NET_AMOUNT` as NET_AMOUNT_DESC , dad.`DATE` as DATE_DESC , dad.`BROKER_ID` as BROKER_ID_DESC 

        FROM super_stock_db.data_log da
        LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
        ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price
"
        );

       
        $mapDataLogsBean = new MapDataLogsGetAllDataBean();
        foreach ($dataLogs as $dataLog) {
            $symbolSrc = $dataLog->SYMBOL_ID_SRC;
            $this->setDataLogSrcBean($dataLog);
  
            $mapDataLogsBean->pushSymbols($symbolSrc, $dataLogSrcBean);
            
            
        }
        
        return json_encode([]);
    }
    
    private function setDataLogSrcBean($dataLog){
        
        $dataLogSrcBean = new DataLogBean();
        $dataLogSrcBean->setId($dataLog->ID_SRC);
        $dataLogSrcBean->setSide_id($dataLog->SIDE_ID_SRC);
        $dataLogSrcBean->setSymbol_id($dataLog->SYMBOL_ID_SRC);
        $dataLogSrcBean->setVolume($dataLog->VOLUME_SRC);
        $dataLogSrcBean->setPrice($dataLog->PRICE_SRC);
        $dataLogSrcBean->setAmount($dataLog->AMOUNT_SRC);
        $dataLogSrcBean->setVat($dataLog->VAT_SRC);
        $dataLogSrcBean->setNet_amount($dataLog->NET_AMOUNT_SRC);
        $dataLogSrcBean->setDate($dataLog->DATE_SRC);
        $dataLogSrcBean->setBroker_id($dataLog->BROKER_ID_SRC);
        
        return $dataLogSrcBean;
    }

}
