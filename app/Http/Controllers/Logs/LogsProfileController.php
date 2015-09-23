<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App;
use Illuminate\Support\Facades\DB;
use App\Utils\StockUtils;

class LogsProfileController extends Controller {

    public function getIndex() {
        $symbolName = Request::input('symbol');
        $brokerId = Request::input('broker');
        
//        $stocks = $this->getDataLogs();
        $brokerAll = json_decode(App::make('App\Http\Controllers\Service\SingleStockService')->getAllBroker());
        return view('logs.profile', 
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
    
    
    public static function calDataStatic($stocks){
        return $this->calData($stocks);
    }
    
    public function calData($stocks){
        $stocksRet = array();
        $total = 0;
        $totalNetAmount = 0;
        $totalVolume = 0;
        $avgPrice = 0;
//        $i = 0;
        $max = 0;
        $CMVn = $CMVo = $BMVn = $BMVo = 0;
        $stockPre = null;
        foreach ($stocks as $stock) {
            
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
            } 
            
            if($sideCode == '003'){
                
                $date = $stock->DATE;
                $symbol = $stock->SYMBOL;
                $tableName = $this->getTableName($symbol);
                $priceInDay = DB::table($tableName)
                        ->where('TIME', $date)
                        ->lists('CLOSE')[0];
                $valueBeforeVat = $priceInDay * $total;
            } else {
                $valueBeforeVat = $price * $total;
            }
            
            if($valueBeforeVat > 0){
                $result = (($valueBeforeVat) - ($valueBeforeVat * 0.001578) - (($valueBeforeVat * 0.001578) * 7 / 100)) - ($avgPrice * $totalVolume);
                $value = ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
            } else {
                $result = $netAmount - $value + $result;
                $value = ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
            }
            if(!$totalVolume > 0){
                $avgPrice -= $result / $volume;
            }

            if($value > $max){
                $max = $value;
            }
            $resultPercent = ($result / $max) * 100;
//            if(($total * $avgPrice) > 0){
//                $portIndex = ($valueBeforeVat * 100) / ($total * $avgPrice);
//            }
            
            
            // PORT INDEX
            if($stockPre === null){
                $CMVo = $BMVo = $CMVn = $BMVn = $netAmount;
            } else {
                // $BMVo 
                $BMVo = $BMVn;
                
                // $CMVo
                $CMVo = ($stockPre->TOTAL == 0 ? $CMVn : $stockPre->TOTAL * $price);
                
                // $CMVn
                $CMVn = ($sideCode == '001' ? $CMVo + $netAmount : $CMVo - $netAmount);
                
                // $BMVn
                $BMVn = StockUtils::getMarketValueNew($CMVn, $CMVo, $BMVo);
            }
            
            $portIndex = StockUtils::getIndex($CMVn, $BMVn);
            
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
            
//            if(AND(M4=0,B4="ขาย"), (O4 / MAX(N$4:N)) * 100,(O4 / MAX(N$4:N)) * 100))
                
            array_push($stocksRet, $stock);
            
            $stockPre = $stock;
//            $stocksRet[count($stocks) - $i++] = $stock;
        }
        
        return array_reverse($stocksRet);
    }

    private function getDataLogs() {
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
