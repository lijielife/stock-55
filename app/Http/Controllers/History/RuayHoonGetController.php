<?php

namespace App\Http\Controllers\History;

use App\Beans\SymbolBean;
//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\RuayHoonController;

//use App\Models\History;
class RuayHoonLoadController extends RuayHoonController {

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน
    public function getIndex() {
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        set_time_limit(0);

        $respone = new \stdClass();

        $symbolNames = $this->getSymbolIsUse();

        foreach ($symbolNames as $symbolName) {

            try {
                $this->setSymbol($symbolName);
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
                $respone->count = count($respone->data);


                echo $symbolName . " : " . count($respone->data) . " Rows <br/>";
            } catch (Exception $e) {
                continue;
            } finally {
                
            }
            $this->updateIsNotUse($symbolName);
        }

        return view('admin.history.index', ['respone' => $respone]);
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
        return number_format($data, 2);
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
        return $this->addData("setVolume", $symbolBeans, $datas, "numberFormat");
    }

}
