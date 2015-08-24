<?php

namespace App\Beans; // การกำหนดที่อยู่ของ Model

class SymbolBean {
    
    public $symbol;
    public $resolution;
    public $millisec;
    public $time;
    public $open;
    public $close;
    public $high;
    public $low;
    public $volume;
    public $origin;
    
    public $updated_at;
    public $created_at;
    
    public function getOrigin() {
        return $this->origin;
    }

    public function setOrigin($origin) {
        $this->origin = $origin;
    }

        function getSymbol() {
        return $this->symbol;
    }

    function getResolution() {
        return $this->resolution;
    }

    function getMillisec() {
        return $this->millisec;
    }
    
    function getTime() {
        return $this->time;
    }

    function getOpen() {
        return $this->open;
    }

    function getClose() {
        return $this->close;
    }

    function getHigh() {
        return $this->high;
    }

    function getLow() {
        return $this->low;
    }

    function getVolume() {
        return $this->volume;
    }

    function setSymbol($symbol) {
        $this->symbol = $symbol;
    }

    function setResolution($resolution) {
        $this->resolution = $resolution;
    }

    function setMillisec($millisec) {
        $this->millisec = $millisec;
    }
    
    function setTime($time) {
        $this->time = $time;
    }

    function setOpen($open) {
        $this->open = $open;
    }

    function setClose($close) {
        $this->close = $close;
    }

    function setHigh($high) {
        $this->high = $high;
    }

    function setLow($low) {
        $this->low = $low;
    }

    function setVolume($volume) {
        $this->volume = $volume;
    }

    public function getUpdated_at() {
        return $this->updated_at;
    }

    public function getCreated_at() {
        return $this->created_at;
    }

    public function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }

    public function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }


}
