<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Logs\LogsTotalController;

class LogsIndayController extends LogsTotalController {

    protected $view = 'logs.inday';

    public function calData($stocks){
        return $stocks;
    }

    protected function setDataExtends(){
        $max = \App\Models\DataLog::max("DATE");
        $this->dataExtends["currDate"] = $max ;
    }
    
    public function getStocksDateIndex($stocks){
        
        $stocksRes = $this->getDataFromProfiles($stocks);
        
        $stocksDateIndex = array();
        foreach ($stocksRes as $stocks) {
            foreach (array_reverse($stocks) as $stock) {
                $symbol = $stock->SYMBOL;
                $date = $stock->DATE;
                if(!$stock->LAST_BEAN){
                    $stocksDateIndex[$date][$symbol] = $stock; 
                }
            }
        }
        return $stocksDateIndex;
    } 
    
    public function getSnapShotDateIndex(){
        $historySet = $this->getHistorySet();
        $snapShotArr = array();
        foreach ($historySet as $key => $value) {
            $snapShotArr[$key]["index"] = $value;
        }
        return $snapShotArr;
    } 
    public function getHistorySet() {
        
        $min = DB::table('DATA_LOG')->where('USER_ID', $this->USER_ID)->min("DATE");
        $max = DB::table('DATA_LOG')->where('USER_ID', $this->USER_ID)->max("DATE");

        return DB::table('HISTORY_SET')
                ->whereBetween('TIME', array($min, $max))
                ->lists('CLOSE', 'TIME');
    }
    
        
    public function getDataLogs() {
        $symbolNameIn = Request::input('symbol');
        $brokerIdIn = Request::input('broker');
        $date = Request::input('date');
        
        $brokerId = ($brokerIdIn === ''? null : $brokerIdIn);
        $symbolName = ($symbolNameIn === '' || $symbolNameIn === null ? null : $symbolNameIn);
        
        $dataLogs = DB::select(
        "SELECT DISTINCT
            da.*
            , msy.SYMBOL, msd.SIDE_CODE, msd.SIDE_NAME, mbk.BROKER_NAME
            , GROUP_CONCAT(ma.MAP_DESC SEPARATOR ',') as MATCHER
        FROM super_stock_db.DATA_LOG da
        LEFT JOIN super_stock_db.LOG_MAP ma on (da.ID = ma.MAP_SRC)
        LEFT JOIN super_stock_db.DATA_LOG dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.MAS_SIDE msd on (msd.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.MAS_SYMBOL msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.MAS_SYMBOL msyd on (msyd.id = dad.SYMBOL_ID)
        LEFT JOIN super_stock_db.MAS_BROKER mbk ON (da.BROKER_ID = mbk.ID)
        LEFT JOIN super_stock_db.MAS_BROKER mbkd ON (dad.BROKER_ID = mbkd.ID)
        WHERE da.USER_ID = ? 

            AND (? IS null OR msy.SYMBOL = ?)
            AND (? IS null OR mbk.ID = ?)
            AND (? IS null OR da.DATE = ?)
        GROUP BY da.ID
        ORDER BY da.SYMBOL_ID, da.DATE,  msd.SIDE_CODE"
        , [$this->USER_ID
                , $symbolName, $symbolName
                , $brokerId, $brokerId
                , $date, $date]
                );
        return $dataLogs;
    }

}