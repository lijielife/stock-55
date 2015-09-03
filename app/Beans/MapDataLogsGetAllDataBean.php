<?php

namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class MapDataLogsGetAllDataBean {

    public $symbols = array();

//    $symbols                       simple
//    $side                   buy             sell
//    $dataLogSrc
    function __construct($mapDataLogsBean = null) {
        if($mapDataLogsBean !== null){
            $this->symbols = $mapDataLogsBean->symbols;
        }
    }

        function getSymbols() {
        return $this->symbols;
    }

    function getSymbolsBySymbol($symbol) {
        if (array_key_exists($symbol, $this->symbols)) {
            return $this->symbols[$symbol];
        }
        return null;
    }

    function getSideBySymbolAndSide($symbol, $side) {
        if (array_key_exists($symbol, $this->symbols)) {
            $sides = $this->symbols[$symbol];
            if (array_key_exists($side, $sides)) {
                return $sides[$side];
            }
        }
        return null;
    }

    function pushSide($dataLogSrc, $symbol, $side) {

        if (array_key_exists($symbol, $this->symbols)) {

            $sides = &$this->symbols[$symbol];
            if (array_key_exists($side, $sides)) {
                $dataLogSrcs = &$sides[$side];
                array_push($dataLogSrcs, $dataLogSrc);
            } else {
                $dataLogSrcs = array();
                array_push($dataLogSrcs, $dataLogSrc);
                $sides[$side] = $dataLogSrcs;
            }
        } else {
            $sides = array();
            $dataLogSrcs = array();
            
            array_push($dataLogSrcs, $dataLogSrc);
            $sides[$side] = $dataLogSrcs;
                
            $this->symbols[$symbol] = $sides;
        }
    }
    
}
