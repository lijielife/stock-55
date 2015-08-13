<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\HistoryController;
use App\Models\History;
class GetController extends HistoryController {

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน
    public function getIndex() {
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        $respone = new \stdClass();

//        $respone->dateFrom = date("Y-m-d H:i:s",491702400);
//        $respone->dateTo = date("Y-m-d H:i:s",1439197096);

        $url = $this->getUrl();
        $homepage = file_get_contents($url);

        $json = json_decode($homepage);

//        $json = new \stdClass();
//        $json->s = "ko";
        $respone->data = $this->convertJsonToArray($json);
        $respone->obj = $this;
        $respone->count = count($respone->data);

        return view('admin.history.index', ['respone' => $respone]);
    }

    private function convertJsonToArray($json) {
        $datas = array();
        if ($json->s == "ok") {
            for ($i = 0; $i < count($json->t); $i++) {
                $datas[$i] = new \stdClass();

                $datas[$i]->millisec = $json->t[$i];
                $datas[$i]->time = date("Y-m-d", $json->t[$i]);
                $datas[$i]->open = number_format($json->o[$i], 2);
                $datas[$i]->high = number_format($json->h[$i], 2);
                $datas[$i]->low = number_format($json->l[$i], 2);
                $datas[$i]->close = number_format($json->c[$i], 2);
                $datas[$i]->volume = number_format($json->v[$i], 0);

            }
            
            $histories = $this->getHistoryFromJson($json);
//            History::create($histories[0]);
            $this->historyInsert($histories);
        }
        return $datas;
    }

}
