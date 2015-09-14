<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App;
use Illuminate\Support\Facades\DB;

class LogsDividendController extends Controller {

    public function getIndex() {
        $symbolName = Request::input('symbol');
        $brokerId = Request::input('broker');
        
        $stocks = $this->getDataLogs();
        $brokerAll = json_decode(App::make('App\Http\Controllers\Service\SingleStockService')->getAllBroker());
        return view('logs.dividend', 
                    [
                        'stocks' => $this->calData($stocks),
                        'brokers' => $brokerAll,
                        'symbolName' => $symbolName, 
                        'brokerId' => $brokerId
                    ]
                );
    }
    
    
    private function getData(){
        return json_encode($this->calData($this->getDataLogs()));
    }
    
    
    private function calData($stocks){
        $stocksRet = array();
        $total = 0;
        $totalNetAmount = 0;
        $totalVolume = 0;
        $avgPrice = 0;
//        $i = 0;
        $max = 0;
        foreach ($stocks as $stock) {
            
            $stock->PRICE = number_format($stock->PRICE, 2, '.', '');
            $stock->NET_AMOUNT = number_format($stock->NET_AMOUNT, 2, '.', '');
            
            $volume = (int)$stock->VOLUME;
            $price = (float)$stock->PRICE;
            $netAmount = (float)$stock->NET_AMOUNT;
            
            
            
            $date = $stock->DATE;
            $symbol = $stock->SYMBOL;
            $tableName = $this->getTableName($symbol);
            $priceInDay = DB::table($tableName)
                    ->where('TIME', $date)
                    ->lists('CLOSE')[0];
            $resultPercent = $netAmount / ($priceInDay * $volume) * 100;

            //เหลือ
            $stock->TOTAL = number_format($totalNetAmount += $netAmount, 2);
            //%
            $stock->RESULT_PERCENT = number_format($resultPercent, 2) ;
//            //มูลค่าหุ้น
//            $stock->VALUE = number_format($value, 2) ;
//            //ผล
//            $stock->RESULT = number_format($result, 2) ;
//            //%
//            $stock->RESULT_PERCENT = number_format($resultPercent, 2) ;
//            //ทุนคงเหลือ	หุ้นคงเหลือ
//            $stock->TOTAL_NET_AMOUNT = number_format($totalNetAmount, 2) ;
//            //ราคาเฉลี่ย
//            $stock->AVG_PRICE = number_format($avgPrice, 4) ;
//            
//            $stock->PORT_INDEX = number_format($portIndex, 2) ;
            
//            if(AND(M4=0,B4="ขาย"), (O4 / MAX(N$4:N)) * 100,(O4 / MAX(N$4:N)) * 100))
                
            array_push($stocksRet, $stock);
//            $stocksRet[count($stocks) - $i++] = $stock;
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
             da.*, msy.SYMBOL, mbk.BROKER_NAME, msd.SIDE_NAME
        FROM super_stock_db.DATA_LOG da
        LEFT JOIN super_stock_db.LOG_MAP ma on (da.ID = ma.MAP_SRC)
        LEFT JOIN super_stock_db.DATA_LOG dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.MAS_SIDE msd on (msd.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.MAS_SYMBOL msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.MAS_BROKER mbk ON (da.BROKER_ID = mbk.ID)
        WHERE da.USER_ID = ? 

            AND (? IS null OR msy.SYMBOL = ?)
            AND (? IS null OR mbk.ID = ?)

            AND msd.SIDE_CODE = '003'
        GROUP BY da.ID
        ORDER BY da.DATE, da.SIDE_ID"
        , [$this->USER_ID, $symbolName, $symbolName, $brokerId, $brokerId]);
        return $dataLogs;
    }

}
