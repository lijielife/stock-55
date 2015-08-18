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
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        $respone = new \stdClass();

        $url = $this->getUrl();
        $homepage = file_get_contents($url);

        $json = json_decode($homepage);

        $respone->data = $this->convertJsonToArray($json);
        $respone->obj = $this;
        $respone->count = count($respone->data);

        return view('admin.history.index', ['respone' => $respone]);
    }

    private function convertJsonToArray($json) {
        $datas = array();
        if ($json->s == "ok") {
            for ($i = 0; $i < count($json->t); $i++) {
                $datas[$i] = new SymbolBean();

                $datas[$i]->setMillisec($json->t[$i]);
                $datas[$i]->setTime(date("Y-m-d", $json->t[$i]));
                $datas[$i]->setOpen(number_format($json->o[$i], 2));
                $datas[$i]->setHigh(number_format($json->h[$i], 2));
                $datas[$i]->setLow(number_format($json->l[$i], 2));
                $datas[$i]->setClose(number_format($json->c[$i], 2));
                $datas[$i]->setVolume(number_format($json->v[$i], 0));

            }
            
            $histories = $this->getHistoryFromJson($json);
//            History::create($histories[0]);
//            $this->historyInsert($histories);
        }
        return $datas;
    }

}
