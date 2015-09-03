<?php

namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class LogMapBean {

    public $id;
    public $mapSrc;
    public $mapDesc;
    public $mapVol;
    private $dataLogSrc;
    private $dataLogDesc;

    function __construct($logMapBean = null) {
        if($logMapBean !== null){
            foreach ($logMapBean as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    
    function getId() {
        return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }
    public function getMapSrc() {
        return $this->mapSrc;
    }

    public function getMapDesc() {
        return $this->mapDesc;
    }

    public function getMapVol() {
        return $this->mapVol;
    }

    public function setMapSrc($mapSrc) {
        $this->mapSrc = $mapSrc;
    }

    public function setMapDesc($mapDesc) {
        $this->mapDesc = $mapDesc;
    }

    public function setMapVol($mapVol) {
        $this->mapVol = $mapVol;
    }

    
    function getDataLogSrc() {
        return $this->dataLogSrc;
    }

    function setDataLogSrc($dataLogSrc) {
        $this->dataLogSrc = $dataLogSrc;
    }

    function getDataLogDesc() {
        return $this->dataLogDesc;
    }

    function setDataLogDesc($dataLogDesc) {
        $this->dataLogDesc = $dataLogDesc;
    }

}
