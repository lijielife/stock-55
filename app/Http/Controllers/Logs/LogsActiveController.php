<?php

namespace App\Http\Controllers\Logs;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class LogsActiveController extends Controller {

    public function getIndex() {
        $stocks = $this->getActiveDataLogs();
        return view('logs.active', ['stocks' => $stocks]);
    }

    private function getActiveDataLogs() {
        $dataLogs = DB::select('SELECT msy.symbol, ms.side_name as side, dl.volume, dl.price, dl.amount, dl.vat, dl.net_amount, dl.date, mbk.broker_name  as broker, us.name
            FROM DATA_LOG dl
            LEFT JOIN MAS_SYMBOL msy ON (dl.SYMBOL_ID = msy.ID)
            LEFT JOIN MAS_BROKER mbk ON (dl.BROKER_ID = mbk.ID)
            LEFT JOIN MAS_SIDE ms ON (dl.SIDE_ID = ms.ID)
            LEFT JOIN USERS us ON (dl.USER_ID = us.ID)
            WHERE dl.UPDATED_AT = (
                            SELECT MAX(UPDATED_AT) FROM DATA_LOG WHERE UPDATED_AT IS NOT NULL
            ) 
            AND dl.USER_ID = ?
            ORDER BY BROKER, SYMBOL, SIDE desc, dl.date', [$this->USER_ID]);
        $stocks = array();
        foreach ($dataLogs as $dataLog) {
            $symbol = $dataLog->symbol;
            $side = $dataLog->side;
            if (array_key_exists($symbol, $stocks)) {
                $sides = &$stocks[$symbol];
                $this->checkSide($sides, $side, $dataLog);
            } else {
                $this->getNewChild($stocks, $symbol, $side, $dataLog);
            }
        }
        return $stocks;
    }

    private function getNewChild(&$stocks, $symbol, $side, $dataLog) {
        $sides = array();
        $datas = array();
        array_push($datas, $dataLog);
        $sides[$side] = $datas;
        $stocks[$symbol] = $sides;
    }

    private function getNewSide(&$sides, $side, $dataLog) {
        $datas = array();
        array_push($datas, $dataLog);
        $sides[$side] = $datas;
    }

    private function checkSide(&$sides, $side, $dataLog) {
        if (array_key_exists($side, $sides)) {
            $datas = &$sides[$side];
            array_push($datas, $dataLog);
        } else {
            $this->getNewSide($sides, $side, $dataLog);
        }
    }

}
