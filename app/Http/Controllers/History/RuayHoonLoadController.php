<?php

namespace App\Http\Controllers\History;

use App\Beans\SymbolBean;
//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\History\RuayHoonController;

//use App\Models\History;
class RuayHoonLoadController extends RuayHoonController {

    protected $is_insert = true; 
    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน
    public function getIndex() {

        return view('admin.history.load', ["urlLoad" => url('/history/loadData2')
            , "urlGetStatus" => url('/history/getStatus2')]);
    }

    public function loadData() {
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        set_time_limit(0);

//        $this->resetData();
        
//        $respone = array();

        $masSymbols = $this->getSymbolIsUse();

        foreach ($masSymbols as $masSymbol) {

            try {
                $data = $this->process($masSymbol);
//                array_push($respone, array("symbolName" => $masSymbol
//                    , "count" => $data->count));
                
            } catch (Exception $e) {
                continue;
            } finally {
                
            }
            $this->updateIsNotUse($masSymbol);
        }

//        if(empty($respone)){
            $respone = parent::getCount('ruayhoon');
//        }
        
        return json_encode($respone);
    }
    
    public function getStatus($origin = 'ruayhoon') {
        return json_encode(parent::getStatus($origin));
    }
}
