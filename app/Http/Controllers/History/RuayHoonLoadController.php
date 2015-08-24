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
//        $user = Users::orderBy('username')->paginate(50); //ทำการกำหนด จำนวน 50 แถวต่อ 1 หน้า

        set_time_limit(0);

//        $this->resetData();
        
        $respone = new \stdClass();

        $symbolNames = $this->getSymbolIsUse();

        foreach ($symbolNames as $symbolName) {

            try {
                $respone = $this->process($symbolName);
//                echo $symbolName . " : " . count($respone->data) . " Rows <br/>";
            } catch (Exception $e) {
                continue;
            } finally {
                
            }
            $this->updateIsNotUse($symbolName);
        }

        return view('admin.history.index', ['respone' => $respone]);
    }
}
