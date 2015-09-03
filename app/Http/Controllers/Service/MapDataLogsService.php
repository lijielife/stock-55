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
use App\Beans\LogMapBean;

class MapDataLogsService extends Controller {

    private $buyCode = "001";
    private $sellCode = "002"; 
    
    public function autoMap() {
        $mapDataLogsBean = new MapDataLogsGetAllDataBean($this->getAllData());
        $symbols = (array) $mapDataLogsBean->getSymbols();

        $idIsUse = array();
        foreach ($symbols as $symbol => $sides) {
            if(array_key_exists($this->buyCode, $sides) && array_key_exists($this->sellCode, $sides)){
                $buySides = $sides["001"];
                $sellSides = $sides["002"];
                
                foreach ($buySides as $buySide) {
                    $buySide = new DataLogBean($buySide);
                    $buyNetAmount = (float)$buySide->getNetAmount();
                    
                    $sellSideTemp = new DataLogBean();
                    $diffTemp = 0;
                    foreach ($sellSides as $sellSide) {
                        if(in_array($sellSide->getId(), $idIsUse)){
                            continue;
                        }
                        $sellSide = new DataLogBean($sellSide);
                        $sellNetAmount = (float)$sellSide->getNetAmount();
                        $diff = $sellNetAmount - $buyNetAmount;
                        if($diff > $diffTemp){
                            $sellSideTemp = $sellSide;
                            $diffTemp = $diff;
                        } 
                    }
                    
                    if($diffTemp > 0){
                        \array_push($idIsUse, $sellSideTemp->getId());
                        
                    }
                }
            }
        }
    }

    private function getAllData() {

        $dataLogs = DB::select(
                        "SELECT 
            da.`ID` as ID_SRC , da.`SIDE_ID` as SIDE_ID_SRC 
            , ms.`SIDE_CODE` as SIDE_CODE_SRC, ms.`SIDE_NAME` as SIDE_NAME_SRC
            , da.`SYMBOL_ID` as SYMBOL_ID_SRC, msy.`SYMBOL` as SYMBOL_SRC
            , da.`VOLUME` as VOLUME_SRC
            , da.`PRICE` as PRICE_SRC, da.`AMOUNT` as AMOUNT_SRC, da.`VAT` as VAT_SRC
            , da.`NET_AMOUNT` as NET_AMOUNT_SRC, da.`DATE` as DATE_SRC, da.`BROKER_ID` as BROKER_ID_SRC 
            , da.`MAP_VOL` as MAP_VOL_SRC

            , ma.`ID`as MAP_ID , ma.`MAP_SRC` , ma.`MAP_DESC` , ma.`MAP_VOL` 

            ,dad.`ID` as ID_DESC , dad.`SIDE_ID` as SIDE_ID_DESC
            , msd.`SIDE_CODE` as SIDE_CODE_DESC, msd.`SIDE_NAME` as SIDE_NAME_DESC
            , dad.`SYMBOL_ID` as SYMBOL_ID_DESC, msyd.`SYMBOL` as SYMBOL_DESC
            , dad.`VOLUME` as VOLUME_DESC
            , dad.`PRICE` as PRICE_DESC, dad.`AMOUNT` as AMOUNT_DESC, dad.`VAT` as VAT_DESC
            , dad.`NET_AMOUNT` as NET_AMOUNT_DESC , dad.`DATE` as DATE_DESC , dad.`BROKER_ID` as BROKER_ID_DESC 
            , da.`MAP_VOL` as MAP_VOL_DESC

        FROM super_stock_db.data_log da
        LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.mas_side ms on (ms.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.mas_side msd on (msd.id = dad.SIDE_ID)
        LEFT JOIN super_stock_db.mas_symbol msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.mas_symbol msyd on (msyd.id = dad.SYMBOL_ID)
        WHERE da.USER_ID = ? 
        ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price
        ", [$this->USER_ID]);

        $mapDataLogsBean = new MapDataLogsGetAllDataBean();
        foreach ($dataLogs as $dataLog) {
            $symbolSrc = $dataLog->SYMBOL_SRC;
            $sideSrc = $dataLog->SIDE_CODE_SRC;

            $dataLogSrcBean = $this->getDataLogSrcBean($dataLog);
            $logMapBean = $this->getLogMapBean($dataLog);
            $dataLogDescBean = $this->getDataLogDescBean($dataLog);

            //set data to Obj
            $dataLogSrcBean->pushLogMap($logMapBean);

            $logMapBean->setDataLogSrc($dataLogSrcBean);
            $logMapBean->setDataLogDesc($dataLogDescBean);

            $dataLogDescBean->pushLogMap($logMapBean);

            //push data to map
            $mapDataLogsBean->pushSide($dataLogSrcBean, $symbolSrc, $sideSrc);
        }
        return $mapDataLogsBean;
    }

    private function getDataLogSrcBean($dataLog) {

        $dataLogBean = new DataLogBean();
        $dataLogBean->setId($dataLog->ID_SRC);
        $dataLogBean->setSideId($dataLog->SIDE_ID_SRC);
        $dataLogBean->setSideCode($dataLog->SIDE_CODE_SRC);
        $dataLogBean->setSideName($dataLog->SIDE_NAME_SRC);
        $dataLogBean->setSymbolId($dataLog->SYMBOL_ID_SRC);
        $dataLogBean->setVolume($dataLog->VOLUME_SRC);
        $dataLogBean->setPrice($dataLog->PRICE_SRC);
        $dataLogBean->setAmount($dataLog->AMOUNT_SRC);
        $dataLogBean->setVat($dataLog->VAT_SRC);
        $dataLogBean->setNetAmount($dataLog->NET_AMOUNT_SRC);
        $dataLogBean->setDate($dataLog->DATE_SRC);
        $dataLogBean->setBrokerId($dataLog->BROKER_ID_SRC);
        $dataLogBean->setMapVol($dataLog->MAP_VOL_SRC);

        return $dataLogBean;
    }

    private function getLogMapBean($dataLog) {

        $logMapBean = new LogMapBean();
        $logMapBean->setId($dataLog->MAP_ID);
        $logMapBean->setMapSrc($dataLog->MAP_SRC);
        $logMapBean->setMapDesc($dataLog->MAP_DESC);
        $logMapBean->setMapVol($dataLog->MAP_VOL);
        return $logMapBean;
    }

    private function getDataLogDescBean($dataLog) {

        $dataLogBean = new DataLogBean();
        $dataLogBean->setId($dataLog->ID_DESC);
        $dataLogBean->setSideId($dataLog->SIDE_ID_DESC);
        $dataLogBean->setSideCode($dataLog->SIDE_CODE_DESC);
        $dataLogBean->setSideName($dataLog->SIDE_NAME_DESC);
        $dataLogBean->setSymbolId($dataLog->SYMBOL_ID_DESC);
        $dataLogBean->setVolume($dataLog->VOLUME_DESC);
        $dataLogBean->setPrice($dataLog->PRICE_DESC);
        $dataLogBean->setAmount($dataLog->AMOUNT_DESC);
        $dataLogBean->setVat($dataLog->VAT_DESC);
        $dataLogBean->setNetAmount($dataLog->NET_AMOUNT_DESC);
        $dataLogBean->setDate($dataLog->DATE_DESC);
        $dataLogBean->setBrokerId($dataLog->BROKER_ID_DESC);
        $dataLogBean->setMapVol($dataLog->MAP_VOL_DESC);

        return $dataLogBean;
    }

}
