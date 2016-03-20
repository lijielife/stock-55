<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App;
//use Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Logs\LogsProfileController;
use App\Utils\SystemUtils;

class LogsTotalController extends LogsProfileController {
    
    protected $view = 'logs.total';
    protected $showActive = ".show.active";
    
    public function calData($stocks){
        
        $stocksRet = array();
        
        $showActive = filter_var($this->stockModel->getConfigByName($this->USER_ID, $this->view.$this->showActive), FILTER_VALIDATE_BOOLEAN);
        $stockArr = $this->getDataFromProfiles($stocks, $showActive);
        foreach ($stockArr as $stockSingleArr) {
            $stockCurrent = current($stockSingleArr);
            if($showActive && $stockCurrent->TOTAL === 0){
                continue;
            }
            array_push($stocksRet, current($stockSingleArr));
        }
        return $stocksRet;        
    }
    
    
    protected function setDataExtends(){
        $this->dataExtends['showActive'] = filter_var($this->stockModel->getConfigByName($this->USER_ID, $this->view.$this->showActive), FILTER_VALIDATE_BOOLEAN);
    }
    
    public function changeShowActive(){
        $showActive = filter_var($this->getRequestParam('showActive'), FILTER_VALIDATE_BOOLEAN);
        DB::table('user_config')
            ->where('USER_ID', $this->USER_ID)
            ->where('CONFIG_ID', 
                    DB::raw("(SELECT CONFIG_ID FROM CONFIG WHERE CONFIG_NAME = '$this->view$this->showActive')"))
            ->update(array('CONFIG_VALUE' => $showActive));
//        $showActive = (boolean)$this->getConfigByName($this->view.$this->showActive);
    }
    
    public function getStockArr($stocks, $showActive) {
        
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
        
        
        $stockArrRet = array();
        if($showActive){
            foreach ($stockArr as $symbol => $stocks) {
                $volumeCount = 0;
                foreach ($stocks as $stock) {
                    if($stock->SIDE_CODE == '001'){
                        $volumeCount += $stock->VOLUME;
                    } else if($stock->SIDE_CODE == '002'){
                        $volumeCount -= $stock->VOLUME;
                    }
    //                $stock->VOLUME;
                }
                if($volumeCount > 0){
                    $stockArrRet[$symbol] = $stocks;
                }
            }
        } else {
            $stockArrRet = $stockArr;
        }
        return $stockArrRet;
    }
    public function getDataFromProfiles($stocks, $showActive){
        $stocksRet = array();
//        $startA = SystemUtils::getMillisec();
        $stockArr = $this->getStockArr($stocks, $showActive);
//        $stopA = SystemUtils::getMillisec();
        
//        $startB = SystemUtils::getMillisec();
        foreach ($stockArr as $symbol => $stockSingleArr) {
            
//            array_push($stockSingleArr, $this->getLastBean($symbol));
        
            $stocksRes = $this->getDataFromProfile($symbol, $stockSingleArr);
                
            array_push($stocksRet, $stocksRes);
        }
//        $stopB = SystemUtils::getMillisec();
        
//        $this->log->info("Call getStockArr : " . ($stopA - $startA));
//        $this->log->info("Call foreach : " . ($stopB - $startB));
        
        return $stocksRet;        
    }
    
    public function getDataFromProfile($symbol, $stockSingleArr){
        
        array_push($stockSingleArr, $this->getLastBean($symbol));
            
        return App::make('App\Http\Controllers\Logs\LogsProfileController')->calData($stockSingleArr);
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
        
//        $this->log->info(DB::getQueryLog());
        
        return $dataLogs;
    }

}
