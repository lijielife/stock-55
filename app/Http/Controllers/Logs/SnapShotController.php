<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Logs\LogsTotalController;

class SnapShotController extends LogsTotalController {

    protected $view = 'logs.snapshot';

    public function calData($stocks){
        
//        $stockFromProfiles = App::make('App\Http\Controllers\Logs\LogsTotalController')->getDataFromProfile($stocks);

//        foreach ($stockFromProfiles as $stockFromProfile) {
//            array_push($stocksRet, current($stockFromProfile));
//        }
        
        $snapShotDateIndex = $this->getSnapShotDateIndex();
        
        $stocksDateIndex = $this->getStocksDateIndex($stocks);

        
        
        
//        $stocksRet = array();
//        foreach ($stockArr as $symbol => $stockSingleArr) {
//            
//            $stocksRes = $this->getDataFromProfile($symbol, $stockSingleArr);
//            
//            foreach ($stocksRes as $stock) {
//                $symbol = $stock->SYMBOL;
//                $date = $stock->DATE;
//                
//                $snapShotArr[$date][$symbol] = $stock;
//            }
//        }
        
        foreach ($snapShotDateIndex as $date => $snapShot) {
            
            $stocksInDate = $stocksDateIndex[$date];
            foreach ($stocksInDate as $stockArr) {
                $stock = current($stockArr);
                $symbol = $stock->SYMBOL;
                $snapShotDateIndex[$date][$symbol] = $stock;
            }
            
//            $snapShotDateIndex
        }
        return $stocksRet;
    }

    
    public function getStocksDateIndex($stocks){
        
        $stocksRes = $this->getDataFromProfiles($stocks);
        
        $stocksDateIndex = array();
        foreach ($stocksRes as $stocks) {
            foreach (array_reverse($stocks) as $stock) {
                $symbol = $stock->SYMBOL;
                $date = $stock->DATE;
                if(!isset($stock->LAST_BEAN) || !$stock->LAST_BEAN){
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