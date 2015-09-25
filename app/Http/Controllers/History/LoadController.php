<?php

namespace App\Http\Controllers\History;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\GetController;

//use App\Models\History;
class LoadController extends GetController {

    protected $is_insert = true;

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน

    public function getIndex() {
        return view('admin.history.load', ["urlLoad" => url('/history/loadData')
            , "urlGetStatus" => url('/history/getStatus')]);
    }

    public function loadData() {
        set_time_limit(0);

//        $this->resetData();
        $this->resetDataInPort();

//        $respone = array();

        $masSymbols = $this->getSymbolIsUse();

        foreach ($masSymbols as $masSymbol) {
            if (strpos($masSymbol, "&") !== false) {
                continue;
            }

            try {
                $data = $this->process($masSymbol);
//                array_push($respone, array("symbolName" => $masSymbol
//                    , "count" => $data->count));
            } catch (Exception $e) {
                continue;
            } finally {
                $this->updateIsNotUse($masSymbol);
            }
        }

//        if(empty($respone)){
            $respone = parent::getCount('investor');
//        }
        return json_encode($respone);
    }

    public function getStatus($origin = 'investor') {
        return json_encode(parent::getStatus($origin));
    }

}
