<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\HistoryController;
use Illuminate\Support\Facades\Request;
use App\Beans\SymbolBean;

//use Illuminate\Support\Facades\DB;

class RuayHoonController extends HistoryController {

//    private $symbol;
//    private $resolution;
//    private $from;
//    private $to;
//    private $url = 'http://www.ruayhoon.com/loadvar.php?';
//    private $criteria = 'stock={symbol}';

    public function __construct() {
        parent::__construct();
        $this->setSymbol(Request::input('symbol'));
        $this->setResolution('D');
//        $this->setResolution(Request::input('resolution'));
        $this->setUrl('http://www.ruayhoon.com/loadvar.php?');
        $this->setCriteria('stock={symbol}');
//        $this->setu (Request::input('from'));
//        $this->setc = Request::input('to');
    }

    protected function process($symbolName) {
        
        $respone = new \stdClass();
         
        if ($symbolName !== null) {
            $this->setSymbol($symbolName);
        }

        $url = $this->getUrl();
        $responeContent = file_get_contents($url);
        $contents = explode("&", $responeContent);

        $symbolBeans = array();
        foreach ($contents as $content) {
            $dataMap = explode("=", $content);
            $index = $dataMap[0];
            $datas = explode(";", $dataMap[1]);

            switch ($index) {
                case "cdate":
                    $symbolBeans = $this->addCDate($symbolBeans, $datas);
                    break;
                case "copen":
                    $symbolBeans = $this->addCOpen($symbolBeans, $datas);
                    break;
                case "chigh":
                    $symbolBeans = $this->addCHigh($symbolBeans, $datas);
                    break;
                case "cclose":
                    $symbolBeans = $this->addCClose($symbolBeans, $datas);
                    break;
                case "clow":
                    $symbolBeans = $this->addCLow($symbolBeans, $datas);
                    break;
                case "cvolume":
                    $symbolBeans = $this->addCVloume($symbolBeans, $datas);
                    break;
            }
        }
        
        $this->historyInsert($symbolBeans);

        $respone->data = $symbolBeans;
        $respone->obj = $this;
        $respone->count = count($symbolBeans);

        return $respone;
    }

    public function getSymbol() {
        $symbol = parent::getSymbol();
        if (!isset($symbol) || trim($symbol) == "") {
            $symbol = "SET";
        }
        return strtoupper($symbol);
    }


    public function getUrl() {
        $url = parent::getUrl() . $this->getCriteria();
        $symbol = $this->getSymbol();
        $url = str_replace("{symbol}", $symbol, $url);
        return $url;
    }

    
    
    private function addData($function, $symbolBeans, $datas, $preFunc = null, $postFunc = null) {
        $count = 0;
        $symbol = $this->getSymbol();
        foreach ($datas as $data) {
            if (count($datas) == $count + 1) {
                break;
            }

            $symbolBean = null;
            if (isset($symbolBeans[$count])) {
                $symbolBean = $symbolBeans[$count];
            } else {
                $symbolBean = new SymbolBean();
            }
            if ($preFunc) {
                $data = $this->$preFunc($data);
            }
            $symbolBean->setSymbol($symbol);
            $symbolBean->setResolution("D");
            $symbolBean->setOrigin("ruayhoon");
            $symbolBean->$function($data);


            if ($postFunc) {
                $this->$postFunc($symbolBean, $data);
            }

            $symbolBeans[$count++] = $symbolBean;
        }
        return $symbolBeans;
    }

    public function setMillisec($symbolBean, $data) {
        $symbolBean->setMillisec(strtotime($data));
    }

    public function dateFormat($data) {
        return date("Y-m-d", strtotime($data));
    }

    public function numberFormat($data) {
        try {
            number_format((float)$data, 2, '.', '');
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return number_format((float)$data, 2, '.', '');
    }
    
    private function addCDate($symbolBeans, $datas) {
        return $this->addData("setTime", $symbolBeans, $datas, "dateFormat", "setMillisec");
    }

    private function addCOpen($symbolBeans, $datas) {
        return $this->addData("setOpen", $symbolBeans, $datas, "numberFormat");
    }

    private function addCHigh($symbolBeans, $datas) {
        return $this->addData("setHigh", $symbolBeans, $datas, "numberFormat");
    }

    private function addCClose($symbolBeans, $datas) {
        return $this->addData("setClose", $symbolBeans, $datas, "numberFormat");
    }

    private function addCLow($symbolBeans, $datas) {
        return $this->addData("setLow", $symbolBeans, $datas, "numberFormat");
    }

    private function addCVloume($symbolBeans, $datas) {
        return $this->addData("setVolume", $symbolBeans, $datas, null);
    }

}
