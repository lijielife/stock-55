<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Logs\LogsTableController;
use App;
use Illuminate\Support\Facades\DB;
use App\Utils\StockUtils;
//use App\Beans\DataLogBean;

class LogsProfileController extends LogsTableController {

    protected $view = 'logs.profile';
    public function getIndex() {
        $symbolName = Request::input('symbol');
        $brokerId = Request::input('broker');
        $brokerAll = json_decode(App::make('App\Http\Controllers\Service\SingleStockService')->getAllBroker());
        return view($this->view, 
                    [
                        'brokers' => $brokerAll,
                        'symbolName' => $symbolName, 
                        'brokerId' => $brokerId
                    ]
                );
    }
    
    public function data_json(){
        return json_encode($this->calData($this->getDataLogs()));
    }
    
    public function calData($stocks){
        
        if(empty($stocks)) {
            return $stocks;
        }
        $stocksRet = array(); $avgPrice = 0; $max = 0;
        $total = 0; $totalNetAmount = 0; $totalVolume = 0;
        $CMVn = $CMVo = $BMVn = $BMVo = 0;
        $stockPre = null;
        
        $stockFirst = current($stocks);
//        $stockEnd = end($stocks);
        
        $priceLast = null;
        $thisDate = date("Y-m-d");
        $firstDate = $stockFirst->DATE;
        $endDate = $thisDate;//$stockEnd->DATE;
        $symbol = $stockFirst->SYMBOL;
        $pricesInDB = $this->getPriceBetweenDate($symbol, $firstDate, $endDate);
        
        $currentBean = new \stdClass();
        $currentBean->SYMBOL = $symbol;
        $currentBean->SIDE_CODE = '001';
        $currentBean->VOLUME = null;
        $currentBean->PRICE = end($pricesInDB);
        $currentBean->NET_AMOUNT = null;
        $currentBean->DATE = $thisDate;
        
        array_push($stocks, $currentBean);
        foreach ($stocks as $stock) {
            
            $stock->PRICE = ($stock->NET_AMOUNT != NULL 
                                ? number_format($stock->PRICE, 2, '.', '') : null);
            $stock->NET_AMOUNT = ($stock->NET_AMOUNT != NULL
                                ? number_format($stock->NET_AMOUNT, 2, '.', '') : null);
            
            $sideCode = $stock->SIDE_CODE;
            $volume = (int)$stock->VOLUME;
            $price = (float)($stock->PRICE ? $stock->PRICE : $priceLast);
            $netAmount = (float)$stock->NET_AMOUNT;
            $date = $stock->DATE;
            if(array_key_exists($date, $pricesInDB)){
                $priceInDay = $pricesInDB[$date];
            } else {
                $priceInDay = ($sideCode == '003' && isset($priceLast) ? $priceLast : $price);
            }
            $priceLast = $priceInDay;
            
            switch ($sideCode){
                case '001':
                    $total += $volume;
                    $totalNetAmount += $netAmount;
                    $totalVolume += $volume;
                    break;
                case '002':
                    $total -= $volume;
                    $totalVolume -= $volume;
                case '003':
                    $totalNetAmount -= $netAmount;
                    break;
            }
            
            if($totalVolume > 0){
                $avgPrice = $totalNetAmount / $totalVolume;
            } 

            $valueBeforeVat = ($sideCode == '003' ? $priceInDay : $price) * $total;
            
            if($valueBeforeVat > 0){
                $value = $this->calValue($valueBeforeVat);
                $result = $value - ($avgPrice * $totalVolume);
            } else {
                if(!isset($value)){
                    $value = 0;
                }
                $result = $netAmount - $value + $result;
                $value = $this->calValue($valueBeforeVat);
            }
            
            if(!$totalVolume > 0 && $volume > 0){
                $avgPrice -= $result / $volume;
            }

            if($value > $max){
                $max = $value;
            }
            $resultPercent = ($result / $max) * 100;
            
            // PORT INDEX
            if($stockPre === null){
                $CMVo = $BMVo = $CMVn = $BMVn = $netAmount;
            } else {
                // $BMVo 
                $BMVo = $BMVn;
                
                // $CMVo
                $CMVo = ($stockPre->TOTAL == 0 ? $CMVn : $stockPre->TOTAL * $priceInDay);

                if($CMVo == 0){
                    $CMVo = 0;
                }
                // $CMVn
                $CMVn = ($sideCode == '002' ? $CMVo - $netAmount : $CMVo + $netAmount);
                
                // $BMVn ปันผลไม่ต้องคำนวนวันฐานใหม่
                $BMVn = ($sideCode == '003' ? $BMVo : StockUtils::getMarketValueNew($CMVn, $CMVo, $BMVo));
            }
            $portIndex = StockUtils::getIndex($CMVn, $BMVn);
            
            //เหลือ
            $stock->TOTAL = $total;
            //มูลค่าหุ้น
            $stock->VALUE = $value;
            //ผล
            $stock->RESULT = $result;
            //%array_pop
            $stock->RESULT_PERCENT = $resultPercent;
            //ทุนคงเหลือ	หุ้นคงเหลือ
            $stock->TOTAL_NET_AMOUNT = $totalNetAmount;
            //ราคาเฉลี่ย
            $stock->AVG_PRICE = $avgPrice;
            
            $stock->PORT_INDEX = $portIndex;
            //ราคาปิดตลาด
            $stock->PRICE_IN_DAY = $priceInDay;
            
            array_push($stocksRet, $stock);
            
            $stockPre = $stock;
        }
        
        return array_reverse($stocksRet);
    }

    public function getDataLogs() {
        $symbolNameIn = Request::input('symbol');
        $brokerIdIn = Request::input('broker');
        
        $brokerId = ($brokerIdIn === ''? null : $brokerIdIn);
        $symbolName = ($symbolNameIn === '' || $symbolNameIn === null ? '' : $symbolNameIn);

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
        ORDER BY da.DATE, da.SIDE_ID"
        , [$this->USER_ID, $symbolName, $symbolName, $brokerId, $brokerId]);
        return $dataLogs;
    }

}
