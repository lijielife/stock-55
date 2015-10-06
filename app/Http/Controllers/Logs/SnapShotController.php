<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Logs\LogsProfileController;

class SnapShotController extends LogsProfileController {

    protected $view = 'logs.snapshot';

    public function calData($stocks){
        
        $stocksRet = array();
        $stockFromProfiles = App::make('App\Http\Controllers\Logs\LogsTotalController')->getDataFromProfile($stocks);

//        foreach ($stockFromProfiles as $stockFromProfile) {
//            array_push($stocksRet, current($stockFromProfile));
//        }
        
        $historySet = $this->getHistorySet();
        
        foreach ($historySet as $historySet => $value) {
            
        }
        return $stocksRet;
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