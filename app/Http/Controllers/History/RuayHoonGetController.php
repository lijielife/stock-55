<?php

namespace App\Http\Controllers\History;

use App\Beans\SymbolBean;
//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\RuayHoonController;

//use App\Models\History;
class RuayHoonGetController extends RuayHoonController {

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน
    public function getIndex() {
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        $respone = $this->process(null); 

        
        return view('admin.history.index', ['respone' => $respone]);
//        return view('admin.blank', ['respone' => $symbolBeans]);
    }

//    private function addData($function, $symbolBeans, $datas, $preFunc = null, $postFunc = null) {
//        $count = 0;
//        $symbol = $this->getSymbol();
//        foreach ($datas as $data) {
//            if (count($datas) == $count + 1) {
//                break;
//            }
//
//            $symbolBean = null;
//            if (isset($symbolBeans[$count])) {
//                $symbolBean = $symbolBeans[$count];
//            } else {
//                $symbolBean = new SymbolBean();
//            }
//            if($preFunc){
//                $data = $this->$preFunc($data);
//            }
//            $symbolBean->setSymbol($symbol);
//            $symbolBean->setResolution("D");
//            $symbolBean->$function($data);
//
//            if($postFunc){
//                $this->$postFunc($symbolBean, $data);
//            }
//            
//            $symbolBeans[$count++] = $symbolBean;
//        }
//        return $symbolBeans;
//    }
//
//    public function setMillisec($symbolBean, $data) {
//        $symbolBean->setMillisec(strtotime($data));
//    }
//    
//    public function dateFormat($data) {
//        return date("Y-m-d", strtotime($data));
//    }
//
//    public function numberFormat($data) {try {
//            number_format((float)$data, 2);
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
//
//        return number_format((float)$data, 2);
//    }
//
//    private function addCDate($symbolBeans, $datas) {
//        return $this->addData("setTime", $symbolBeans, $datas, "dateFormat", "setMillisec");
//    }
//
//    private function addCOpen($symbolBeans, $datas) {
//        return $this->addData("setOpen", $symbolBeans, $datas, "numberFormat");
//    }
//
//    private function addCHigh($symbolBeans, $datas) {
//        return $this->addData("setHigh", $symbolBeans, $datas, "numberFormat");
//    }
//
//    private function addCClose($symbolBeans, $datas) {
//        return $this->addData("setClose", $symbolBeans, $datas, "numberFormat");
//    }
//
//    private function addCLow($symbolBeans, $datas) {
//        return $this->addData("setLow", $symbolBeans, $datas, "numberFormat");
//    }
//
//    private function addCVloume($symbolBeans, $datas) {
//        return $this->addData("setVolume", $symbolBeans, $datas, "numberFormat");
//    }

//    private function convertJsonToArray($json) {
//        $datas = array();
//        if ($json->s == "ok") {
//            for ($i = 0; $i < count($json->t); $i++) {
//                $datas[$i] = new \stdClass();
//
//                $datas[$i]->millisec = $json->t[$i];
//                $datas[$i]->time = date("Y-m-d", $json->t[$i]);
//                $datas[$i]->open = number_format($json->o[$i], 2);
//                $datas[$i]->high = number_format($json->h[$i], 2);
//                $datas[$i]->low = number_format($json->l[$i], 2);
//                $datas[$i]->close = number_format($json->c[$i], 2);
//                $datas[$i]->volume = number_format($json->v[$i], 0);
//            }
//
//            $histories = $this->getHistoryFromJson($json);
////            History::create($histories[0]);
//            $this->historyInsert($histories);
//        }
//        return $datas;
//    }
}
