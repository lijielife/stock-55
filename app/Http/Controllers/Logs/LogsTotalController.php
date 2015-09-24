<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Logs\LogsProfileController;

class LogsTotalController extends LogsProfileController {
    
    protected $view = 'logs.total';

    public function calData($stocks){
        $stocksRet = array();
        $stockArr = array();
        $stockSingleArr = array();
        foreach ($stocks as $stock) {
            $symbol = $stock->SYMBOL;
            if(array_key_exists($symbol, $stockArr)){
                $stockSingleArr = $stockArr[$symbol];
            } else {
                $stockSingleArr = array();
            }
            array_push($stockSingleArr, $stock);
            $stockArr[$symbol] = $stockSingleArr;
        }
        
        foreach ($stockArr as $symbol => $stockSingleArr) {
            $stocksRes = App::make('App\Http\Controllers\Logs\LogsProfileController')->calData($stockSingleArr);
            array_push($stocksRet, current($stocksRes));
        }
        return $stocksRet;
    }

    public function getDataLogs() {
        $symbolNameIn = Request::input('symbol');
        $brokerIdIn = Request::input('broker');
        
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

        GROUP BY da.ID
        ORDER BY da.SYMBOL_ID, da.DATE,  msd.SIDE_CODE"
        , [$this->USER_ID, $symbolName, $symbolName, $brokerId, $brokerId]);
        return $dataLogs;
    }

}
