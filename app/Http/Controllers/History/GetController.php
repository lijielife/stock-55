<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\HistoryController;
use App\Beans\SymbolBean;

//use App\Models\History;
class GetController extends HistoryController {

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน
    public function getIndex() {

        $respone = $this->process(null);

        return view('admin.history.index', ['respone' => $respone]);
    }

    public function process($symbolName) {

        $respone = new \stdClass();
        if ($symbolName !== null) {
            $this->setSymbol($symbolName);
        }
        $url = $this->getUrlCri();

        $homepage = file_get_contents($url);

        $json = json_decode($homepage);

        $respone->data = $this->convertJsonToArray($json);
        $respone->obj = $this;
        $respone->count = count($respone->data);

        return $respone;
    }

    protected function convertJsonToArray($json) {
        $datas = array();
        if ($json->s == "ok") {
            for ($i = 0; $i < count($json->t); $i++) {
                $symbolBean = new SymbolBean();

                $symbol = $this->getSymbol();

                $symbolBean->setSymbol(str_replace("*BK", "", $symbol));
                $symbolBean->setResolution($this->getResolution());
                $symbolBean->setMillisec($json->t[$i]);
                $symbolBean->setTime(date("Y-m-d", $json->t[$i]));
                $symbolBean->setOpen(number_format($json->o[$i], 2));
                $symbolBean->setHigh(number_format($json->h[$i], 2));
                $symbolBean->setLow(number_format($json->l[$i], 2));
                $symbolBean->setClose(number_format($json->c[$i], 2));
                $symbolBean->setVolume(number_format($json->v[$i], 0));
                $symbolBean->setUpdated_at(date('Y-m-d H:i:s'));
                $symbolBean->setCreated_at(date('Y-m-d H:i:s'));
                $symbolBean->setOrigin("investor");

                array_push($datas, $symbolBean);
            }

            $this->historyInsert($datas);
        }
        return $datas;
    }
    
    public function getSymbol() {
        $symbol = parent::getSymbol();
        if (!isset($symbol) || trim($symbol) == "") {
            $symbol = "ADVANC*BK";
        } else if (!strrpos($symbol, '*')) {
            $symbol = $symbol . "*BK";
        }
        return $symbol;
    }

}
