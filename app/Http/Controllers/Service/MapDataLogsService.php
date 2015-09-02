<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use App\Models\DataLog;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
//
//use App\Models\MasSymbol;
//
//use App\Models\History;

class MapDataLogsService extends Controller {

    public function getAllData($param = null) {


       $dataLogs = DB::select(
        "SELECT *
            FROM super_stock_db.data_log da
            LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_SRC)
            LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
            ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price
"
        );

//        DataLog::where()->all();
//        $masSymbols = MasSymbol::lists('SYMBOL');
//        if (count($masSymbols)) {
//            return json_encode($masSymbols);
//        }
        return json_encode([]);
    }

}
