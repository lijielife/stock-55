<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Beans\SymbolBean;

class HistoryController extends Controller {

    private $symbol;
    private $resolution;
    private $from;
    private $to;
    private $url = 'http://chart.investor.co.th/achart/history/query.ashx?';
    private $criteria = 'symbol={symbol}&resolution={resolution}&from={from}&to={to}';

    protected $is_insert = false;
    
    public function __construct() {
        parent::__construct();
        $this->symbol = Request::input('symbol');
        $this->resolution = Request::input('resolution');
        $this->from = Request::input('from');
        $this->to = Request::input('to');
    }

    protected function getSymbolIsUse(){
    
        $symbolNames = DB::table('SYMBOL_NAME')
        ->where('IS_USE' , 1)
        ->lists('SYMBOL');
        
        return $symbolNames;
    }
        
    protected  function updateIsNotUse($symbolName){
        
        DB::table('SYMBOL_NAME')->where('SYMBOL', $symbolName)->update(['IS_USE' => 0]);
        
    }
    
    protected function historyInsert($symbolBeans){
    
        if (!$this->is_insert) {
            return;
        }
            
        $times = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();
            $times[count($times)] = $timeMillisec;
        }

        $symbol = $this->getSymbol();

        $timeInUse = DB::table('HISTORY')
                ->where('SYMBOL', $symbol)
                ->where('ORIGIN', 'ruayhoon')
                ->whereIn('TIME', $times)
                ->lists('TIME');

        $historiesInsert = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();

            if (!in_array($timeMillisec, $timeInUse)) {
                array_push($historiesInsert, (array) $symbolBean);
                array_push($timeInUse, $timeMillisec);
            }
        }
                
        foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
            DB::table('HISTORY')->insert($insertValue);
        }
        
    }
    
    public function getSymbol() {
                
        if (!isset($this->symbol) || trim($this->symbol) == "") {
            $this->symbol = "ADVANC";
        }
        return $this->symbol;
    }

    public function getResolution() {
        if (isset($this->resolution) || trim($this->resolution) == "") {
            $this->resolution = "D";
        }
        return $this->resolution;
    }

    public function getFrom() {
        if (isset($this->from) || trim($this->from) == "") {
            $this->from = strtotime(date("Y-m-d H:i:s"));
        }
        return $this->from;
    }

    public function getTo() {
        if (isset($this->to) || trim($this->to) == "") {
            $this->to = strtotime(date("Y-m-d H:i:s"));
        }
        return $this->to;
    }

    public function getUrl() {
        return $this->url;
    }
    
    public function getUrlCri() {
        $url = $this->getUrl() . $this->getCriteria();
        $symbol = $this->getSymbol();
        $resolution = $this->getResolution();
        $from = $this->getFrom();
        $to = $this->getTo();

        $url = str_replace("{symbol}", $symbol, $url);
        $url = str_replace("{resolution}", $resolution, $url);
        $url = str_replace("{from}", $from, $url);
        $url = str_replace("{to}", $to, $url);

        return $url;
    }

    public function getCriteria() {
        return $this->criteria;
    }

    public function setSymbol($symbol) {
        $this->symbol = $symbol;
    }

    public function setResolution($resolution) {
        $this->resolution = $resolution;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function setTo($to) {
        $this->to = $to;
    }
    protected function setUrl($url) {
        $this->url = $url;
    }

    protected function setCriteria($criteria) {
        $this->criteria = $criteria;
    }

    public function resetData(){
        DB::update('update SYMBOL_NAME SET IS_USE = ?', ['1']);
    }
//    function setUrl($url) {
//        $this->url = $url;
//    }
//    function setCriteria($criteria) {
//        $this->criteria = $criteria;
//    }
}
