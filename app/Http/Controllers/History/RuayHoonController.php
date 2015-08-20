<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class RuayHoonController extends Controller {

    private $symbol;
    private $resolution;
    private $from;
    private $to;
    private $url = 'http://www.ruayhoon.com/loadvar.php?';
    private $criteria = 'stock={symbol}';

    public function __construct() {
        parent::__construct();
        $this->symbol = Request::input('symbol');
        $this->resolution = Request::input('resolution');
        $this->from = Request::input('from');
        $this->to = Request::input('to');
    }

    protected function getHistoryFromJson($json) {
        $histories = array();

        for ($i = 0; $i < count($json->t); $i++) {
            $history = array('SYMBOL' => $this->getSymbol()
                , 'RESOLUTION' => $this->getResolution()
                , 'TIME' => $json->t[$i]
                , 'OPEN' => $json->o[$i]
                , 'CLOSE' => $json->c[$i]
                , 'HIGH' => $json->h[$i]
                , 'LOW' => $json->l[$i]
                , 'VOLUME' => $json->v[$i]);

            array_push($histories, $history);
        }

        return $histories;
    }

    protected function historyInsert($symbolBeans) {

        $times = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();
            $times[count($times)] = $timeMillisec;
        }

        $symbol = $this->getSymbol();

        $timeInUse = DB::table('history')
                ->where('SYMBOL', $symbol)
                ->where('ORIGIN', 'ruayhoon')
                ->whereIn('TIME', $times)
                ->lists('TIME');

        $historiesInsert = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();

            if (!in_array($timeMillisec, $timeInUse)) {
                array_push($historiesInsert, (array) $symbolBean);
            }
        }
//        $historiesInserts = array();
//        $highValue = 100000;
//        $slice = count($historiesInsert) / $highValue;
//
//        if ($slice > 1) {
//            for ($i = 0; $i < $slice; $i++) {
//                array_push($historiesInserts, array_slice($historiesInsert, $slice * $highValue, ($slice + 1) * $highValue));
//            }
//        } else {
//            array_push($historiesInserts, $historiesInsert);
//        }


        
        
                
                
        foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
            DB::table('history')->insert($insertValue);
        }
    }

    function getSymbol() {

//        $symbol = $this->symbol;
        if (!isset($this->symbol) || trim($this->symbol) == "") {
            $this->symbol = "ADVANC";
        }
//        else if (!strrpos($this->symbol, '*')) {
//            $this->symbol = $this->symbol . "*BK";
//        }
        return strtoupper($this->symbol);
    }

    function getResolution() {
        if (isset($this->resolution) || trim($this->resolution) == "") {
            $this->resolution = "D";
        }
        return $this->resolution;
    }

    function getFrom() {
        if (isset($this->from) || trim($this->from) == "") {
            $this->from = strtotime(date("Y-m-d H:i:s"));
        }
        return $this->from;
    }

    function getTo() {
        if (isset($this->to) || trim($this->to) == "") {
            $this->to = strtotime(date("Y-m-d H:i:s"));
        }
        return $this->to;
    }

    function getUrl() {
        $url = $this->url . $this->getCriteria();
        $symbol = $this->getSymbol();
//        $resolution = $this->getResolution();
//        $from = $this->getFrom();
//        $to = $this->getTo();

        $url = str_replace("{symbol}", $symbol, $url);
//        $url = str_replace("{resolution}", $resolution, $url);
//        $url = str_replace("{from}", $from, $url);
//        $url = str_replace("{to}", $to, $url);

        return $url;
    }

    function getCriteria() {
        return $this->criteria;
    }

    function setSymbol($symbol) {
        $this->symbol = $symbol;
    }

    function setResolution($resolution) {
        $this->resolution = $resolution;
    }

    function setFrom($from) {
        $this->from = $from;
    }

    function setTo($to) {
        $this->to = $to;
    }

    protected function getSymbolIsUse() {

        $symbolNames = DB::table('SYMBOL_NAME')
                ->where('IS_USE', 1)
                ->lists('SYMBOL');

        return $symbolNames;
    }

    protected function updateIsNotUse($symbolName) {

        DB::table('SYMBOL_NAME')->where('SYMBOL', $symbolName)->update(['IS_USE' => 0]);
    }

//    function setUrl($url) {
//        $this->url = $url;
//    }
//    function setCriteria($criteria) {
//        $this->criteria = $criteria;
//    }
}
