<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App;
use Illuminate\Support\Facades\DB;

class LogsTotalController extends Controller {
    
    private $STOCKS_KEY = "STOCKS";
    private $STOCKS_ARR_KEY = "STOCKS_ARR";
    private $TOTAL_KEY = "TOTAL";
    private $TOTAL_NET_AMOUNT_KEY = "TOTAL_NET_AMOUN";
    private $TOTAL_VOLUME_KEY = "TOTAL_VOLUME";
    private $AVG_PRICE_KEY = "AVG_PRICE";
    private $MAX_KEY = "MAX";
        
    public function getIndex() {
        $symbolName = Request::input('symbol');
        $brokerId = Request::input('broker');
        
//        $stocks = $this->getDataLogs();
        $brokerAll = json_decode(App::make('App\Http\Controllers\Service\SingleStockService')->getAllBroker());
        return view('logs.total', 
                    [
//                        'stocks' => $this->calData($stocks), 
                        'brokers' => $brokerAll,
                        'symbolName' => $symbolName, 
                        'brokerId' => $brokerId
                    ]
                );
    }
    
    public function data_json(){
        return json_encode($this->calData($this->getDataLogs()));
    }
    
    private function calData($stocks){
        
        $stockArr = array();
        foreach ($stocks as $stock) {
            $symbol = $stock->SYMBOL;
            $stocksObj = array();
            $total = 0;
            $totalNetAmount = 0;
            $totalVolume = 0;
            $avgPrice = 0;
            $max = 0;
            if(array_key_exists($symbol, $stockArr)){
                $stockTemp = $stockArr[$symbol];
                $total = $stockTemp->TOTAL;
                $totalNetAmount = $stockTemp->TOTAL_NET_AMOUNT;
                $totalVolume = $stockTemp->TOTAL_VOLUME;
                $avgPrice = $stockTemp->AVG_PRICE;
                $max = $stockTemp->MAX_VALUE;
            }
                    
            $stock->PRICE = number_format($stock->PRICE, 2, '.', '');
            $stock->NET_AMOUNT = number_format($stock->NET_AMOUNT, 2, '.', '');
            
            $sideCode = $stock->SIDE_CODE;
            $volume = (int)$stock->VOLUME;
            $price = (float)$stock->PRICE;
            $netAmount = (float)$stock->NET_AMOUNT;
            
            
            if($sideCode == '001'){
                $total += $volume;
                
                $totalNetAmount += $netAmount;
                $totalVolume += $volume;

            } else if($sideCode == '002'){
                $total -= $volume;
                
                $totalNetAmount -= $netAmount;
                $totalVolume -= $volume;
            } else if($sideCode == '003'){
                $totalNetAmount -= $netAmount;
            }
            
            if($totalVolume > 0){
                $avgPrice = $totalNetAmount / $totalVolume;
            } else {
                $avgPrice = 0;
            }
            
            $valueBeforeVat = $price * $total;
            if($sideCode == '003'){
                
                $date = $stock->DATE;
                $tableName = $this->getTableName($symbol);
                $priceInDay = DB::table($tableName)
                        ->where('TIME', $date)
                        ->lists('CLOSE')[0];
                    
                $valueBeforeVat = $priceInDay * $total;
                $value = ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
                $result = (($valueBeforeVat) - ($valueBeforeVat * 0.001578) - (($valueBeforeVat * 0.001578) * 7 / 100)) - ($avgPrice * $totalVolume);
            } else {
                $value = ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
                $result = (($valueBeforeVat) - ($valueBeforeVat * 0.001578) - (($valueBeforeVat * 0.001578) * 7 / 100)) - ($avgPrice * $totalVolume);
            }
            
            if($value > $max){
                $max = $value;
            } 
            if($max == 0){
                $max = 1;
            }
            $resultPercent = ($result / $max) * 100;
            if(($total * $avgPrice) > 0){
                $portIndex = ($valueBeforeVat * 100) / ($total * $avgPrice);
            }
            
            
            //เหลือ
            $stock->TOTAL = $total;
            //มูลค่าหุ้น
            $stock->VALUE = $value;
            //ผล
            $stock->RESULT = $result;
            //%
            $stock->RESULT_PERCENT = $resultPercent;
            //ทุนคงเหลือ	หุ้นคงเหลือ
            $stock->TOTAL_NET_AMOUNT = $totalNetAmount;
            //ราคาเฉลี่ย
            $stock->AVG_PRICE = $avgPrice;
            
            $stock->PORT_INDEX = $portIndex;
            
            $stock->TOTAL_VOLUME = $totalVolume;
            
            $stock->MAX_VALUE = $max;
//            if(AND(M4=0,B4="ขาย"), (O4 / MAX(N$4:N)) * 100,(O4 / MAX(N$4:N)) * 100))
                
            $stockArr[$symbol] = $stock;
        }
        $stocksRet = array();
        foreach ($stockArr as $symbol => $stocksObj) {
            array_push($stocksRet, $stocksObj);
        }
        
        return array_reverse($stocksRet);
    }

    private function getDataLogs() {
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
        ORDER BY da.DATE, da.SYMBOL_ID, da.SIDE_ID"
        , [$this->USER_ID, $symbolName, $symbolName, $brokerId, $brokerId]);
        return $dataLogs;
    }

}