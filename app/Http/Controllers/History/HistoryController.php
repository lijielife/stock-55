<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

//use App\Beans\SymbolBean;

class HistoryController extends Controller {

    private $symbol;
    private $resolution;
    private $from;
    private $to;
//    http://service.bidschart.com/history?symbol=.SET&resolution=D&from=1465784260&to=1466648260
    private $url = 'http://service.bidschart.com/history?';
//    private $url = 'http://chart.investorz.com/achart/history/query.ashx?';
    private $criteria = 'symbol={symbol}&resolution={resolution}&from={from}&to={to}';
    protected $is_insert = false;

    public function __construct() {
        parent::__construct();
        $this->symbol = Request::input('symbol');
        $this->resolution = Request::input('resolution');
        $this->from = Request::input('from');
        $this->to = Request::input('to');
    }

    protected function getSymbolIsUse() {

        $masSymbols = DB::table('MAS_SYMBOL')
                ->where('IS_USE', 1)
                ->lists('SYMBOL');

        return $masSymbols;
    }

    protected function getLoadStatusError($sessionId) {

        $masSymbols = DB::table('LOAD_STATUS')
                ->whereNotNull('ERROR_DESC')
                ->where('SESSION_ID', $sessionId)
                ->lists('SYMBOL');

        return $masSymbols;
    }

    protected function updateIsNotUse($masSymbol) {

        DB::table('MAS_SYMBOL')->where('SYMBOL', $masSymbol)->update(['IS_USE' => 0]);
    }

//    protected function historyInsert($symbolBeans, $origin){
//
//        if (!$this->is_insert) {
//            return;
//        }
//
//        $times = array();
//        foreach ($symbolBeans as $symbolBean) {
//            $timeMillisec = $symbolBean->getMillisec();
//            $times[count($times)] = $timeMillisec;
//        }
//
//        $symbol = str_replace("*BK", "", $this->getSymbol());
//
//        $timeInUse = DB::table('HISTORY')
//                ->where('SYMBOL', $symbol)
//                ->where('ORIGIN', $origin)
//                ->whereIn('MILLISEC', $times)
//                ->lists('MILLISEC');
//
//        $historiesInsert = array();
//        foreach ($symbolBeans as $symbolBean) {
//            $timeMillisec = $symbolBean->getMillisec();
//
//            if (!in_array($timeMillisec, $timeInUse)) {
//
//                $symbolBean->setUpdated_at(date('Y-m-d H:i:s'));
//                $symbolBean->setCreated_at(date('Y-m-d H:i:s'));
//
//                array_push($historiesInsert, (array) $symbolBean);
//                array_push($timeInUse, $timeMillisec);
//            }
//        }
//
//        foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
//            DB::table('HISTORY')->insert($insertValue);
//        }
//
//    }

    protected function historyInsert($symbolBeans, $tableName, $origin) {

        if (!$this->is_insert) {
            return;
        }

        $times = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();
            $times[count($times)] = $timeMillisec;
        }

        $symbol = str_replace("*BK", "", $this->getSymbol());

        $this->createTable($tableName, $symbol, $origin);

        $timeInUse = DB::table($tableName)
                ->select('MILLISEC', DB::raw('md5(concat(TIME, OPEN, CLOSE, HIGH, LOW, VOLUME)) as MD'))
                ->where('SYMBOL', $symbol)
                ->where('ORIGIN', $origin)
                ->whereIn('MILLISEC', $times)
                ->lists('MD', 'MILLISEC');

        $historiesInsert = array();
        $historiesUpdate = array();
        foreach ($symbolBeans as $symbolBean) {
            $timeMillisec = $symbolBean->getMillisec();

            if (!array_key_exists($timeMillisec, $timeInUse)) {

                $symbolBean->setUpdated_at(date('Y-m-d H:i:s'));
                $symbolBean->setCreated_at(date('Y-m-d H:i:s'));

                array_push($historiesInsert, (array) $symbolBean);
                array_push($timeInUse, $timeMillisec);
            } else {
                $md5DB = $timeInUse[$timeMillisec];
                $time = $symbolBean->getTime();
                $open = $symbolBean->getOpen();
                $close = $symbolBean->getClose();
                $high = $symbolBean->getHigh();
                $low = $symbolBean->getLow();
                $volume = $symbolBean->getVolume();
                $md = md5($time.$open.$close.$high.$low.$volume);
                if($md != $md5DB){
                    array_push($historiesUpdate, (array) $symbolBean);
                }
            }
        }

        foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
            DB::table($tableName)->insert($insertValue);
        }

        foreach (array_chunk($historiesUpdate, 1000) as $updateValue) {
            DB::table($tableName)
                    ->where('MILLISEC', $updateValue[0]["millisec"])
                    ->update($updateValue[0]);
        }
    }

    public function createTable($tableName = null, $masSymbol = null, $origin = "ALL") {
        if ($tableName != null) {
//            $tableIsExit = $this->checkTableIsExit($tableName);
//            if (!$tableIsExit) {
//              DB::statement('drop table IF EXISTS ' . $tableName);
                DB::statement('CREATE TABLE IF NOT EXISTS `' . $tableName . '` (
                  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                  `SYMBOL` varchar(11) NOT NULL,
                  `RESOLUTION` varchar(2) NOT NULL,
                  `MILLISEC` bigint(20) NOT NULL,
                  `TIME` varchar(15) NOT NULL,
                  `OPEN` decimal(10,2) NOT NULL,
                  `CLOSE` decimal(10,2) NOT NULL,
                  `HIGH` decimal(10,2) NOT NULL,
                  `LOW` decimal(10,2) NOT NULL,
                  `VOLUME` bigint(20) NOT NULL,
                  `ORIGIN` varchar(255) NOT NULL,
                  `UPDATED_AT` date NOT NULL,
                  `CREATED_AT` date NOT NULL,
                  PRIMARY KEY (`ID`),
                  KEY `TIME` (`TIME` DESC)

                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2474868 ;');

                $is_check = DB::table('TABLE_NAME')->where(["table_name" => $tableName])->get();

                if (!$is_check) {
                    DB::table('TABLE_NAME')->insert(["table_name" => $tableName
                        , "symbol" => $masSymbol
                        , "origin" => $origin]);
                }
//            }

            return true;
        }
        return false;
    }

//    private function checkTableIsExit($tableName) {
//        $result = DB::select("SELECT COUNT(*) as CNT FROM information_schema.tables WHERE table_schema = '$tableName'");
//        return ($result[0]->CNT > 0 ? true : false);
////        SHOW TABLES LIKE 'history_abpif'
//    }

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
        $from = 1433116800;//$this->getFrom();
        $to = $this->getTo();

        $url = str_replace("{symbol}", $symbol, $url);
        $url = str_replace("{resolution}", $resolution, $url);
        $url = str_replace("{from}", $from, $url);
        $url = str_replace("{to}", $to, $url);

//        http://service.bidschart.com/history?symbol=ADVANC&resolution=D&from=1433116800&to=1466923244
//        http://service.bidschart.com/history?symbol=ADVANC*BK&resolution=D&from=1433116800&to=1466923467
//
        return str_replace("&amp;", "&", $url);
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

    public function resetData($default = '1') {
        DB::update('update MAS_SYMBOL SET IS_USE = ?', [$default]);
    }

    public function resetDataInPort($symbols = null) {

        if($symbols){
            $this->resetData(0);
            \App\Models\MasSymbol::whereIn("SYMBOL", $symbols)->update(array("IS_USE" => 1));
        } else {

            $this->resetData(0);
            DB::update('update MAS_SYMBOL SET IS_USE = ?'
                . ' WHERE ID IN (SELECT distinct SYMBOL_ID FROM DATA_LOG) OR SYMBOL = ?', ['1', 'SET']);
        }
    }

//    function setUrl($url) {
//        $this->url = $url;
//    }
//    function setCriteria($criteria) {
//        $this->criteria = $criteria;
//    }

    public function getStatus($sessionId) {
//        $commit = DB::table('HISTORY')
//                ->where('origin', $origin)
//                ->distinct()
//                ->count('symbol');


        $commit = DB::table('LOAD_STATUS')
                ->where('SESSION_ID', $sessionId)
                ->where('STATUS_DESC', 'success')
                ->distinct()
                ->count('SYMBOL');

        $total = DB::table('LOAD_STATUS')
                ->where('SESSION_ID', $sessionId)
                ->distinct()
                ->count('SYMBOL');

        return array("commit" => $commit, "total" => $total, "percent" => (int) floor(($commit / $total) * 100));
//        SELECT count(distinct symbol) FROM super_stock_db.history;
    }

    public function getCount($sessionId) {

        $ret = array();
//        $values = DB::table('HISTORY')
//                ->select(DB::raw('count(*) as cnt, symbol'))
//                ->where('origin', $origin)
//                ->groupBy('symbol')
//                ->get();


//        $values = DB::table('LOAD_STATUS')
//                ->where('SESSION_ID', $sessionId)
//                ->lists('SYMBOL', 'status_desc');

        $values = DB::table('LOAD_STATUS')
                ->select(DB::raw('count(*) as CNT, SYMBOL'))
//                ->where('origin', $origin)
                ->where('SESSION_ID', $sessionId)
                ->groupBy('SYMBOL')
                ->get();

        foreach ($values as $value) {
            array_push($ret, array("symbolName" => $value->SYMBOL
                , "count" => $value->CNT));
        }
        return $ret;
    }

}
