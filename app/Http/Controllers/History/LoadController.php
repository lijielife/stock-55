<?php

namespace App\Http\Controllers\History;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
//use Auth;
use App\Http\Controllers\History\GetController;

use App\Threads\LoadControllerThread;
use App\Beans\Status;
class LoadController extends GetController {

    protected $is_insert = true;

    // สำหรับแสดงรายชื่อสมาชิก หรือ admin ที่มีอยู่ในปัจจุบัน

    public function getIndex() {
        return view('admin.history.load', ["urlLoad" => url('/history/loadData')
            , "urlGetStatus" => url('/history/getStatus')]);
    }

    public function loadData() {
        set_time_limit(0);


        $symbols = (Request::input('symbols') === null ? null : explode(",", Request::input('symbols')));
        $isLoadAll = (Request::input('isLoadAll') === null ? false : true);
        $this->log->info(" isLoadAll : " . '' . ($isLoadAll ? 'true' : 'false') );

        if($isLoadAll){
            $this->resetData();
        } else {
            $this->resetDataInPort($symbols);
        }

//        $respone = array();

        $masSymbols = $this->getSymbolIsUse();

        $statusArr = array();
        $sessionId = Session::getId();
        foreach ($masSymbols as $masSymbol) {
            $status = new Status();
            $status->setSession_id($sessionId);
            $status->setSymbol($masSymbol);
            $status->setStatus_desc("process");
            $status->setCreate_date(date('Y-m-d'));
            $status->setCreate_time(date('H:i:s'));
            $statusArr[$masSymbol] = (array) $status;
        }

        DB::table('LOAD_STATUS')->where('SESSION_ID', $sessionId)->delete();

        DB::table("LOAD_STATUS")->insert($statusArr);
//        \Illuminate\Contracts\Queue\ShouldQueue::
//        $threadedMethod = new LoadControllerThread($this);
//        $threadedMethod->start();

//        load normal loop
        $this->loadDataLoop($masSymbols, $statusArr);

//        load error loop
        $loadErrorSymbols = $this->getLoadStatusError($sessionId);
        $this->loadDataLoop($loadErrorSymbols, $statusArr);


//        reset data
        $this->resetDataInPort();

//        if(empty($respone)){
            $respone = parent::getCount($sessionId);
//        }
        return json_encode($respone);
    }



    public function executeByThread($masSymbols, $statusArr) {

//        $this->loadDataLoop($masSymbols, $statusArr);
//
//        $loadErrorSymbols = $this->getLoadStatusError();
//        $this->loadDataLoop($loadErrorSymbols, $statusArr);


    }

    public function loadDataLoop($masSymbols, $statusArr) {

        foreach ($masSymbols as $masSymbol) {
            if (strpos($masSymbol, "&") !== false) {
                continue;
            }

            $this->log->info($masSymbol);

            $error_desc = null;
            $status_desc = 'success';
            try {
                
                $data = $this->process($masSymbol);
//                array_push($respone, array("symbolName" => $masSymbol
//                    , "count" => $data->count));

            } catch (\Exception $e) {
                $this->log->info(" Error LoadData Symbol : " . $masSymbol );
                $error_desc = $e->getMessage();
                $status_desc = 'error';
            } finally {
                $this->updateIsNotUse($masSymbol);
                $status = $statusArr[$masSymbol];
                DB::table('LOAD_STATUS')
                    ->where($status)
                    ->update(['STATUS_DESC' => $status_desc,
                        'ERROR_DESC' => $error_desc]);

                usleep(1000);
            }
        }
    }

    public function getStatus($origin = 'investor') {
        $sessionId = Session::getId();
        return json_encode(parent::getStatus($sessionId));
    }

}
