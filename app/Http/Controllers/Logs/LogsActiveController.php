<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Support\Facades\Request;
//use Auth;
use App\Http\Controllers\Controller;
use App;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class LogsActiveController extends Controller {

    private $objArrKey = "objArr";
    private $objAvgKey = "objAvg";
    private $objVolKey = "objVol";
    private $objVolBuyKey = "objVolBuy";
    private $objVolSellKey = "objVolSell";

    public function getIndex() {

        $symbolName = Request::input('symbol');
        $brokerId = Request::input('broker');

        $stocks = $this->calDataStocks($this->getActiveDataLogs());
        $brokerAll = json_decode(App::make('App\Http\Controllers\Service\SingleStockService')->getAllBroker());

        return view('logs.active', [
            'stocks' => $stocks,
            'brokers' => $brokerAll,
            'symbolName' => $symbolName,
            'brokerId' => $brokerId,
            'objArrKey' => $this->objArrKey,
            'objAvgKey' => $this->objAvgKey,
            'objVolKey' => $this->objVolKey,
            'objVolBuyKey' => $this->objVolBuyKey,
            'objVolSellKey' => $this->objVolSellKey
                ]
        );
    }

    private function calDataStocks($stocks) {

        foreach ($stocks as $broker => &$symbols) {
            foreach ($symbols as $symbol => &$dataObjs) {
                $datas = $dataObjs[$this->objArrKey];
                $sumBuy = 0;
                $sumSell = 0;
                $objAvgBuy = 0;
                $objAvgSell = 0;
                foreach ($datas as $data) {
                    $volBuy = $data->VOLUME_BUY;
                    $volSell = $data->VOLUME_SELL;

                    $sumBuy += (int) $data->VOLUME_BUY;
                    $sumSell += (int) $data->VOLUME_SELL;
                    $price = (float) $data->PRICE_SRC;

                    $objAvgBuy += ($volBuy * $price);
                    $objAvgSell += ($volSell * $price);
                }

                $objVol = abs($sumBuy - $sumSell);
                $objAvgBuy /= ($sumBuy == 0 ? 1 : $sumBuy);
                $objAvgSell /= ($sumSell == 0 ? 1 : $sumSell);
                $objAvg = ($objAvgBuy - $objAvgSell) / ($objVol == 0 ? 1 : $objVol);

                $dataObjs[$this->objAvgKey] = $objAvg;
                $dataObjs[$this->objVolKey] = $objVol;
                $dataObjs[$this->objVolBuyKey] = $sumBuy;
                $dataObjs[$this->objVolSellKey] = $sumSell;
            }
        }
        return $stocks;
    }

    private function getActiveDataLogs() {
        $symbolName = Request::input('symbol');
        $brokerIdIn = Request::input('broker');

        $brokerId = ($brokerIdIn === '' ? null : $brokerIdIn);
//        $symbol = Request::input('symbol');
//        $symbol = Request::input('symbol');
//         AND da.BROKER_ID = 2 AND da.SYMBOL_ID = 76
        $dataLogs = DB::select(
                        "SELECT DISTINCT
            da.`BROKER_ID` as BROKER_ID_SRC , mbk.`BROKER_NAME` as BROKER_NAME_SRC 
            , da.`SYMBOL_ID` as SYMBOL_ID_SRC, msy.`SYMBOL` as SYMBOL_SRC
            , da.`PRICE` as PRICE_SRC
            ,SUM( CASE WHEN ms.`SIDE_CODE` = '001' THEN da.`VOLUME` - da.`MAP_VOL`
                ELSE 0
                END ) as VOLUME_BUY
            ,SUM( CASE WHEN ms.`SIDE_CODE` = '002' THEN da.`VOLUME` - da.`MAP_VOL`
                ELSE 0
                END ) as VOLUME_SELL
            , SUM( da.`VOLUME` - da.`MAP_VOL` )as VOLUME_ALL
        FROM super_stock_db.DATA_LOG da
        LEFT JOIN super_stock_db.LOG_MAP ma on (da.ID = ma.MAP_SRC)
        LEFT JOIN super_stock_db.DATA_LOG dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.MAS_SIDE ms on (ms.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.MAS_SIDE msd on (msd.id = dad.SIDE_ID)
        LEFT JOIN super_stock_db.MAS_SYMBOL msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.MAS_SYMBOL msyd on (msyd.id = dad.SYMBOL_ID)
        LEFT JOIN super_stock_db.MAS_BROKER mbk ON (da.BROKER_ID = mbk.ID)
        LEFT JOIN super_stock_db.MAS_BROKER mbkd ON (dad.BROKER_ID = mbkd.ID)
        WHERE da.USER_ID = ? 
            AND (ms.SIDE_CODE <> '003' AND ms.SIDE_CODE IS NOT NULL) 
            AND (msd.SIDE_CODE <> '003' OR msd.SIDE_CODE IS NULL)
            AND (
                    ma.ID IS NULL OR da.ID IN (
                        SELECT da.ID
                        FROM data_log da
                        JOIN log_map lm on (da.ID = lm.MAP_SRC)
                        GROUP BY da.ID, da.VOLUME
                        having da.VOLUME <> SUM(lm.MAP_VOL)
                    )
                ) 
            AND (? IS null OR msy.SYMBOL = ?)
            AND (? IS null OR mbk.ID = ?)
            
        GROUP BY da.BROKER_ID, mbk.BROKER_NAME, da.symbol_id, msy.SYMBOL, da.PRICE
        ORDER BY da.BROKER_ID, da.symbol_id, da.price DESC, da.VOLUME DESC
        ", [$this->USER_ID, $symbolName, $symbolName, $brokerId, $brokerId]);


//        $dataLogs = DB::select('SELECT msy.symbol, ms.side_name as side, dl.volume, dl.price, dl.amount, dl.vat, dl.net_amount, dl.date, mbk.broker_name  as broker, us.name
//            FROM DATA_LOG dl
//            LEFT JOIN MAS_SYMBOL msy ON (dl.SYMBOL_ID = msy.ID)
//            LEFT JOIN MAS_BROKER mbk ON (dl.BROKER_ID = mbk.ID)
//            LEFT JOIN MAS_SIDE ms ON (dl.SIDE_ID = ms.ID)
//            LEFT JOIN USERS us ON (dl.USER_ID = us.ID)
//            WHERE dl.CREATED_AT = (
//                            SELECT MAX(CREATED_AT) FROM DATA_LOG WHERE UPDATED_AT IS NOT NULL
//            ) 
//            AND dl.USER_ID = ?
//            ORDER BY BROKER, SYMBOL, SIDE desc, dl.date', [$this->USER_ID]);
        $stocks = array();

        foreach ($dataLogs as $dataLog) {
            $brokerIdSrc = $dataLog->BROKER_NAME_SRC;
            $symbol = $dataLog->SYMBOL_SRC;
            if (array_key_exists($brokerIdSrc, $stocks)) {
                $symbols = &$stocks[$brokerIdSrc];
                if (array_key_exists($symbol, $symbols)) {
                    $dataObjs = $symbols[$symbol];
                    $this->addDataLog($dataObjs, $dataLog);
//                    array_push($datas, $dataLog);
                    $symbols[$symbol] = $dataObjs;
                } else {
                    $dataObjs = array();
                    $this->addDataLog($dataObjs, $dataLog);
//                    array_push($datas, $dataLog);
                    $symbols[$symbol] = $dataObjs;
                }
            } else {
                $stocks[$brokerIdSrc] = array();
                $dataObjs = array();
//                array_push($datas, $dataLog);
                $this->addDataLog($dataObjs, $dataLog);
                $stocks[$brokerIdSrc][$symbol] = $dataObjs;
            }
        }
        return $stocks;
    }

    private function addDataLog(&$dataObjs, $dataLog) {
        if (!array_key_exists($this->objArrKey, $dataObjs)) {
            $dataObjs[$this->objArrKey] = array();
        }
        $datas = &$dataObjs[$this->objArrKey];
        array_push($datas, $dataLog);
    }

//    private function getNewChild(&$symbols, $symbol, $dataLog) {
//        $datas = array();
//        array_push($datas, $dataLog);
//        $symbols[$symbol] = $datas;
//    }
//    private function getNewSide(&$sides, $side, $dataLog) {
//        $datas = array();
//        array_push($datas, $dataLog);
//        $sides[$side] = $datas;
//    }
//
//    private function checkSide(&$sides, $side, $dataLog) {
//        if (array_key_exists($side, $sides)) {
//            $datas = &$sides[$side];
//            array_push($datas, $dataLog);
//        } else {
//            $this->getNewSide($sides, $side, $dataLog);
//        }
//    }
}
