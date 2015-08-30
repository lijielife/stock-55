<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MasSymbol;

use App\Models\History;

class SingleStockService extends Controller {

    public function getAllSymbol($param = null) {

//    Cache::forget('getAllSymbol');

//        $ret = Cache::get('getAllSymbol', function() {
                    $masSymbols = MasSymbol::lists('SYMBOL');
                    if (count($masSymbols)) {

//                        Cache::add('getAllSymbol', json_encode($masSymbols), date('Y-m-d H:i:s'));

                        return json_encode($masSymbols);
                    }
                    return json_encode([]);
//                });

//        return $ret;
    }
    
    public function getAllBroker($param = null) {
        $masBroker = \App\Models\MasBroker::all();
        return json_encode($masBroker);
    }

    public function getSingleStock($param = null) {

//        $symbol = Request::input('symbol');
//        $fromDate = Request::input('fromDate');
//        $toDate = Request::input('toDate');
//        $cacheName = 'getSingleStock' . $symbol . $fromDate . $toDate;
//        Cache::forget($cacheName);
        
//        $ret = Cache::get($cacheName, function() use( &$cacheName) {
                    $symbol = Request::input('symbol');
                    $fromDate = Request::input('fromDate');
                    $toDate = Request::input('toDate');

                    $historys = History::where("symbol", $symbol)
                            ->whereBetween("millisec", array($fromDate, $toDate)
                            )
                            ->get();

                    if (count($historys)) {

                        $datas = array();

                        foreach ($historys as $history) {
                            $data = array();
                            $millisec = ($history->MILLISEC? $history->MILLISEC:$history->millisec);
                            array_push($data, $millisec * 1000);
                            array_push($data, (double) $history->OPEN);
                            array_push($data, (double) $history->HIGH);
                            array_push($data, (double) $history->LOW);
                            array_push($data, (double) $history->CLOSE);
                            array_push($data, (double) $history->VOLUME);

                            array_push($datas, $data);

                        }

//                        Cache::add($cacheName, json_encode($datas), date('Y-m-d H:i:s'));

                        return json_encode($datas);
                    }
                    return json_encode([]);
//                });

//        return $ret;
    }

}
