<?php

namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class MapDataLogsPlanBean {

//    private $avgPrice;
//    private $totalVolume;
    private $dataLogBeanArr = array();
    
    function __construct($mapDataLogsPlanBean = null) {
        if($mapDataLogsPlanBean !== null){
//            $this->avgPrice = $mapDataLogsPlanBean->getAvgPrice();
//            $this->totalVolume = $mapDataLogsPlanBean->getTotalVolume();
            $this->dataLogBeanArr = $mapDataLogsPlanBean->getDataLogBeanArr();
        }
    }
    
    
    function getId() {
        return md5(serialize($this->dataLogBeanArr));
    }
    
    function getAvgPrice() {
        $avgPrice = 0;
        $totalVolume = 0;
        foreach ($this->dataLogBeanArr as $dataLogBean) {
            $avgPrice += (float)$dataLogBean->getAmount();
            $totalVolume += (int)$dataLogBean->getVolume();
        }
        return ($totalVolume > 0 ? $avgPrice / $totalVolume : -1);
    }

    function getTotalVolume() {
        $totalVolume = 0;
        foreach ($this->dataLogBeanArr as $dataLogBean) {
            $totalVolume += (int)$dataLogBean->getVolume();
        }
        return $totalVolume;
    }

    function getDataLogBeanArr() {
        return $this->dataLogBeanArr;
    }

//    function setAvgPrice($avgPrice) {
//        $this->avgPrice = $avgPrice;
//    }
//
//    function setTotalVolume($totalVolume) {
//        $this->totalVolume = $totalVolume;
//    }

    function setDataLogBeanArr($dataLogBeanArr) {
        $this->dataLogBeanArr = $dataLogBeanArr;
    }
    
    function addDataLogBeanArr($dataLogBeanArr) {
        array_push($this->dataLogBeanArr, $dataLogBeanArr);
    }
}
