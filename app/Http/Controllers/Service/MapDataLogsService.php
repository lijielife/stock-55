<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use App\Models\DataLog;
use App\Models\LogMap;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
//
//use App\Models\MasSymbol;
//
//use App\Models\History;
use App\Beans\MapDataLogsGetAllDataBean;
use App\Beans\DataLogBean;
use App\Beans\LogMapBean;
use App\Beans\MapDataLogsPlanBean;

class MapDataLogsService extends Controller {

    private $buyCode = "001";
    private $sellCode = "002";

    public function autoMap() {
        $mapDataLogsBean = new MapDataLogsGetAllDataBean($this->getAllData());
        $brokers = (array) $mapDataLogsBean->getBrokers();
        $idIsUse = array();


        foreach ($brokers as $broker => $symbols) {

            foreach ($symbols as $symbol => $sides) {
                if (array_key_exists($this->buyCode, $sides) && array_key_exists($this->sellCode, $sides)) {
                    $buySides = $sides["001"];
                    $sellSides = $sides["002"];


                    $this->autoMapSell($idIsUse, $buySides, $sellSides, 1.00);
                    $this->autoMapSell($idIsUse, $sellSides, $buySides, -1.00);
                }
            }
        }
        return json_encode(["response" => "success"]);
    }

    function autoMapSell(&$idIsUse, $firstSides, $secondSides, $conf) {

//        foreach ($symbols as $symbol => $sides) {
//            if (array_key_exists($this->buyCode, $sides) && array_key_exists($this->sellCode, $sides)) {
//                $buySides = $sides["001"];
//                $sellSides = $sides["002"];


        foreach ($firstSides as $firstSide) {
            $firstSide = new DataLogBean($firstSide);
            $firstPrice = (float) $firstSide->getPrice();
            $firstVolume = (int) $firstSide->getVolume();
            $firstId = (int) $firstSide->getId();

            if (in_array($firstId, $idIsUse)) {
                continue;
            }

            $secondSideTemp = NULL;
            $plans = array();
            $diffTemp = 0;
            foreach ($secondSides as $id => $secondSide) {
                if (in_array($secondSide->getId(), $idIsUse)) {
                    continue;
                }
                $secondVolume = (int) $secondSide->getVolume();
                $secondPrice = (float) $secondSide->getPrice();
                $diff = ((float) $secondPrice - $firstPrice) * $conf;
                if ($firstVolume == $secondVolume && ($diff > $diffTemp)) {
                    $secondSideTemp = $secondSide;
                    $diffTemp = $diff;
//                            break;
                } else if ($secondVolume < $firstVolume) {
                    foreach ($plans as $key => $plan) {
                        $totalVolume = $plan->getTotalVolume();
                        $secondTotalVolume = $secondSide->getVolume();

                        if ($totalVolume + $secondTotalVolume <= $firstVolume) {
                            $plan->addDataLogBeanArr($secondSide);
                        }
                    }
                    $this->addPlan($plans, $secondSide);
                }
            }

            if ($secondSideTemp !== NULL) {
                $this->saveDataToDB($idIsUse, $firstSide, array($secondSideTemp));
            } else {
                if (!empty($plans)) {
                    usort($plans, array($this, "plansCmp"));

                    $plan = $this->selectPlan($plans, $firstSide, $conf);
                    if ($plan !== null) {
                        $this->saveDataToDB($idIsUse, $firstSide, $plan->getDataLogBeanArr());
                    }
                }
            }
        }
//            }
//        }
    }

    function autoMapBuy(&$idIsUse, $buySides, $sellSides, $conf) {

//        foreach ($symbols as $symbol => $sides) {
//            if (array_key_exists($this->buyCode, $sides) && array_key_exists($this->sellCode, $sides)) {
//                $buySides = $sides["001"];
//                $sellSides = $sides["002"];

        foreach ($buySides as $buySide) {
            $buySide = new DataLogBean($buySide);
            $buyPrice = (float) $buySide->getPrice();
            $buyVolume = (int) $buySide->getVolume();
            $buyId = (int) $buySide->getId();

            if (in_array($buyId, $idIsUse)) {
                continue;
            }

            $sellSideTemp = NULL;
            $plans = array();
            $diffTemp = 0;
            foreach ($sellSides as $id => $sellSide) {
                if (in_array($sellSide->getId(), $idIsUse)) {
                    continue;
                }
                $sellVolume = (int) $sellSide->getVolume();
                $sellPrice = (float) $sellSide->getPrice();
                $diff = ((float) $sellPrice - $buyPrice) * $conf;
                if ($buyVolume == $sellVolume && ($diff > $diffTemp)) {
                    $sellSideTemp = $sellSide;
                    $diffTemp = $diff;
//                            break;
                } else if ($sellVolume < $buyVolume) {
                    foreach ($plans as $key => $plan) {
                        $totalVolume = $plan->getTotalVolume();
                        $sellTotalVolume = $sellSide->getVolume();

                        if ($totalVolume + $sellTotalVolume <= $buyVolume) {
                            $plan->addDataLogBeanArr($sellSide);
                        }
                    }
                    $this->addPlan($plans, $sellSide);
                }
            }

            if ($sellSideTemp !== NULL) {
                $this->saveDataToDB($idIsUse, $buySide, array($sellSideTemp));
            } else {
                if (!empty($plans)) {
                    usort($plans, array($this, "plansCmp"));
                    $plan = $this->selectPlan($plans, $buySide, $conf);
                    if ($plan !== null) {
                        $this->saveDataToDB($idIsUse, $buySide, $plan->getDataLogBeanArr());
                    }
                }
            }
        }
//            }
//        }
    }

    function getUniKey($diff, $sellVolume, $id) {
        $diffp = str_pad($diff, 15, "0", STR_PAD_LEFT);
        $sellVolumep = str_pad($sellVolume, 10, "0", STR_PAD_LEFT);
        $idp = str_pad($id, 10, "0", STR_PAD_LEFT);
        return $diffp . "_" . $sellVolumep . "_" . $idp;
    }

    function plansCmp($a, $b) {
        return strcmp($b->getAvgPrice(), $a->getAvgPrice());
    }

    private function selectPlan(&$plans, $buySide, $conf) {
        $planSelect = NULL;
        $diffPriceTemp = 0;
        $diffVolTemp = 0;

        $planCadedate = array();
        foreach ($plans as $key => $plan) {
            $planPrice = (float) $plan->getAvgPrice();
            $planVolume = (int) $plan->getTotalVolume();

            $buyprice = (float) $buySide->getPrice();
            $buyVolume = (int) $buySide->getVolume();
            $diff = ((float) $planPrice - $buyprice) * $conf;

            if ($planVolume == $buyVolume) {
                if ($diff > $diffPriceTemp) {
                    $diffPriceTemp = $diff;
                    $planSelect = $plan;
                    $diffVolTemp = $buyVolume;
//                    array_pull($plans, $key);
                    continue;
                }
            }

            if ($diff > 0) {
                $planCadedate[$this->getUniKey($diff, $planVolume, $plan->getId())] = $plan;
            } else {
                $diff = 0;
            }
        }

        if ($planSelect !== NULL) {
            $key = array_search($planSelect, $plans);
            unset($plans[$key]);
            return $planSelect;
        } else {
            krsort($planCadedate);
            return array_pop($planCadedate);
        }
    }

    private function addPlan(&$plans, $sellSide) {
        $mapDataLogsPlanBean = new MapDataLogsPlanBean();
        $mapDataLogsPlanBean->addDataLogBeanArr($sellSide);
        array_push($plans, $mapDataLogsPlanBean);
    }

    private function saveDataToDB(&$idIsUse, $dataLogSrcBean, $dataLogDescBeans) {
        $srcVolume = 0;
        $srcAmount = 0.0000;
        $srcId = $dataLogSrcBean->getId();
        foreach ($dataLogDescBeans as $dataLogDescBean) {
            $descId = $dataLogDescBean->getId();
            $descAmount = (float) $dataLogDescBean->getAmount();
            $descVolume = (int) $dataLogDescBean->getVolume();

            $srcAmount += $descAmount;
            $srcVolume += $descVolume;

            $this->insertLogMap($srcId, $descId, $descVolume);
            $this->insertLogMap($descId, $srcId, $descVolume);

            \array_push($idIsUse, $srcId, $descId);


            
            if ($descVolume > 0) {
                $this->updateDataLog($descId, $descVolume, number_format((float)$descAmount / $descVolume, 4, '.', ''));
            } else {
                $descVolume = 0;
            }
        }
        if ($srcVolume > 0) {
            $this->updateDataLog($srcId, $srcVolume, number_format((float)$srcAmount / $srcVolume, 4, '.', ''));
        }
    }

    private function updateDataLog($id, $volume, $prices) {
        DataLog::where("ID", $id)
                ->update(['MAP_VOL' => $volume
                    , 'MAP_AVG' => $prices]);
    }

    private function insertLogMap($srcId, $descId, $descVolume) {

        $logMapBean = new LogMapBean();
        $logMapBean->setMapSrc($srcId);
        $logMapBean->setMapDesc($descId);
        $logMapBean->setMapVol($descVolume);

        LogMap::insert($logMapBean->getDataToInsert());
    }

    private function getAllData() {

        $dataLogs = DB::select(
                        "SELECT 
            da.`ID` as ID_SRC , da.`SIDE_ID` as SIDE_ID_SRC 
            , ms.`SIDE_CODE` as SIDE_CODE_SRC, ms.`SIDE_NAME` as SIDE_NAME_SRC
            , da.`SYMBOL_ID` as SYMBOL_ID_SRC, msy.`SYMBOL` as SYMBOL_SRC
            , da.`VOLUME` as VOLUME_SRC
            , da.`PRICE` as PRICE_SRC, da.`AMOUNT` as AMOUNT_SRC, da.`VAT` as VAT_SRC
            , da.`NET_AMOUNT` as NET_AMOUNT_SRC, da.`DATE` as DATE_SRC, da.`BROKER_ID` as BROKER_ID_SRC 
            , da.`MAP_VOL` as MAP_VOL_SRC, da.`MAP_AVG` as MAP_AVG_SRC

            , ma.`ID`as MAP_ID , ma.`MAP_SRC` , ma.`MAP_DESC` , ma.`MAP_VOL` 

            ,dad.`ID` as ID_DESC , dad.`SIDE_ID` as SIDE_ID_DESC
            , msd.`SIDE_CODE` as SIDE_CODE_DESC, msd.`SIDE_NAME` as SIDE_NAME_DESC
            , dad.`SYMBOL_ID` as SYMBOL_ID_DESC, msyd.`SYMBOL` as SYMBOL_DESC
            , dad.`VOLUME` as VOLUME_DESC
            , dad.`PRICE` as PRICE_DESC, dad.`AMOUNT` as AMOUNT_DESC, dad.`VAT` as VAT_DESC
            , dad.`NET_AMOUNT` as NET_AMOUNT_DESC , dad.`DATE` as DATE_DESC , dad.`BROKER_ID` as BROKER_ID_DESC 
            , da.`MAP_VOL` as MAP_VOL_DESC, dad.`MAP_AVG` as MAP_AVG_DESC

        FROM super_stock_db.data_log da
        LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_SRC)
        LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.mas_side ms on (ms.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.mas_side msd on (msd.id = dad.SIDE_ID)
        LEFT JOIN super_stock_db.mas_symbol msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.mas_symbol msyd on (msyd.id = dad.SYMBOL_ID)
        WHERE da.USER_ID = ? 
            AND (ms.SIDE_CODE <> '003' AND ms.SIDE_CODE IS NOT NULL) 
            AND (msd.SIDE_CODE <> '003' OR msd.SIDE_CODE IS NULL)
            AND ma.ID IS NULL
        ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price DESC, da.VOLUME DESC
        ", [$this->USER_ID]);
//        AND da.MAP_VOL = '' AND da.SYMBOL_ID = 76

        $mapDataLogsBean = new MapDataLogsGetAllDataBean();
        foreach ($dataLogs as $dataLog) {
            $brokerSrc = $dataLog->BROKER_ID_SRC;
            $symbolSrc = $dataLog->SYMBOL_SRC;
            $sideSrc = $dataLog->SIDE_CODE_SRC;

            $dataLogSrcBean = $this->getDataLogSrcBean($dataLog);
            if ($dataLogSrcBean === NULL) {
                continue;
            }

            $logMapBean = $this->getLogMapBean($dataLog);
            $dataLogDescBean = $this->getDataLogDescBean($dataLog);

            if ($logMapBean !== NULL) {

                //set data to Obj
                $dataLogSrcBean->pushLogMap($logMapBean);

                $logMapBean->setDataLogSrc($dataLogSrcBean);


                if ($dataLogDescBean !== NULL) {

                    $logMapBean->setDataLogDesc($dataLogDescBean);

                    $dataLogDescBean->pushLogMap($logMapBean);
                }
            }

            //push data to map
            $mapDataLogsBean->pushSide($dataLogSrcBean, $brokerSrc, $symbolSrc, $sideSrc);
        }
        return $mapDataLogsBean;
    }

    private function getDataLogSrcBean($dataLog) {

        if ($dataLog->ID_SRC !== null) {
            $dataLogBean = new DataLogBean();
            $dataLogBean->setId($dataLog->ID_SRC);
            $dataLogBean->setSideId($dataLog->SIDE_ID_SRC);
            $dataLogBean->setSideCode($dataLog->SIDE_CODE_SRC);
            $dataLogBean->setSideName($dataLog->SIDE_NAME_SRC);
            $dataLogBean->setSymbolId($dataLog->SYMBOL_ID_SRC);
            $dataLogBean->setVolume($dataLog->VOLUME_SRC);
            $dataLogBean->setPrice($dataLog->PRICE_SRC);
            $dataLogBean->setAmount($dataLog->AMOUNT_SRC);
            $dataLogBean->setVat($dataLog->VAT_SRC);
            $dataLogBean->setNetAmount($dataLog->NET_AMOUNT_SRC);
            $dataLogBean->setDate($dataLog->DATE_SRC);
            $dataLogBean->setBrokerId($dataLog->BROKER_ID_SRC);
            $dataLogBean->setMapVol($dataLog->MAP_VOL_SRC);

            return $dataLogBean;
        }
        return NULL;
    }

    private function getLogMapBean($dataLog) {

        if ($dataLog->MAP_ID !== null) {
            $logMapBean = new LogMapBean();
            $logMapBean->setId($dataLog->MAP_ID);
            $logMapBean->setMapSrc($dataLog->MAP_SRC);
            $logMapBean->setMapDesc($dataLog->MAP_DESC);
            $logMapBean->setMapVol($dataLog->MAP_VOL);
            return $logMapBean;
        }
        return NULL;
    }

    private function getDataLogDescBean($dataLog) {

        if ($dataLog->ID_DESC !== null) {
            $dataLogBean = new DataLogBean();
            $dataLogBean->setId($dataLog->ID_DESC);
            $dataLogBean->setSideId($dataLog->SIDE_ID_DESC);
            $dataLogBean->setSideCode($dataLog->SIDE_CODE_DESC);
            $dataLogBean->setSideName($dataLog->SIDE_NAME_DESC);
            $dataLogBean->setSymbolId($dataLog->SYMBOL_ID_DESC);
            $dataLogBean->setVolume($dataLog->VOLUME_DESC);
            $dataLogBean->setPrice($dataLog->PRICE_DESC);
            $dataLogBean->setAmount($dataLog->AMOUNT_DESC);
            $dataLogBean->setVat($dataLog->VAT_DESC);
            $dataLogBean->setNetAmount($dataLog->NET_AMOUNT_DESC);
            $dataLogBean->setDate($dataLog->DATE_DESC);
            $dataLogBean->setBrokerId($dataLog->BROKER_ID_DESC);
            $dataLogBean->setMapVol($dataLog->MAP_VOL_DESC);
            return $dataLogBean;
        }
        return NULL;
    }

//    function autoMapSell($symbols, &$idIsUse) {
//
//        foreach ($symbols as $symbol => $sides) {
//            if (array_key_exists($this->buyCode, $sides) && array_key_exists($this->sellCode, $sides)) {
//                $buySides = $sides["001"];
//                $sellSides = $sides["002"];
//
//                foreach ($sellSides as $sellSide) {
//                    $sellSide = new DataLogBean($sellSide);
//                    $sellPrice = (float) $sellSide->getPrice();
//                    $sellVolume = (int) $sellSide->getVolume();
//                    $sellId = (int) $sellSide->getId();
//
//                    if (in_array($sellId, $idIsUse)) {
//                        continue;
//                    }
//
//                    $buySideTemp = NULL;
//                    $plans = array();
//                    $diffTemp = 0;
//                    foreach ($buySides as $id => $buySide) {
//                        if (in_array($buySide->getId(), $idIsUse)) {
//                            continue;
//                        }
//                        $buyVolume = (int) $buySide->getVolume();
//                        $buyPrice = (float) $buySide->getPrice();
//                        $diff = (float) $buyPrice - $sellPrice;
//                        if ($sellVolume == $buyVolume && ($diff > $diffTemp)) {
//                            $buySideTemp = $buySide;
//                            $diffTemp = $diff;
////                            break;
//                        } else if ($buyVolume < $sellVolume) {
//                            foreach ($plans as $key => $plan) {
//                                $totalVolume = $plan->getTotalVolume();
//                                $buyTotalVolume = $buySide->getVolume();
//
//                                if ($totalVolume + $buyTotalVolume <= $sellVolume) {
//                                    $plan->addDataLogBeanArr($buySide);
//                                }
//                            }
//                            $this->addPlan($plans, $buySide);
//                        }
//                    }
//
//                    if ($buySideTemp !== NULL) {
//                        $this->saveDataToDB($idIsUse, $sellSide, array($buySideTemp));
//                    } else {
//                        if (!empty($plans)) {
//                            usort($plans, array($this, "plansCmp"));
//
//                            $plan = $this->selectPlan($plans, $sellSide);
//                            if ($plan !== null) {
//                                $this->saveDataToDB($idIsUse, $sellSide, $plan->getDataLogBeanArr());
//                            }
//                        }
//                    }
//                }
//            }
//        }
//    }
}
