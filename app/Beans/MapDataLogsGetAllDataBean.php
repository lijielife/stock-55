<?php namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class MapDataLogsGetAllDataBean {
    
    private $symbols = array();

    function pullSymbols($key) {
        return array_pull($this->symbols, $key);
    }

    function pushSymbols($symbol, $dataLogSrc) {
        array_add($this->symbols, $symbol, $dataLogSrc);
    }
}