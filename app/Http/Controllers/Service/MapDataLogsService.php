<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use App\Models\DataLog;

//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
//use Illuminate\Support\Facades\DB;
//
//use App\Models\MasSymbol;
//
//use App\Models\History;

class MapDataLogsService extends Controller {
    
    public function getAllSymbol($param = null) {
        
        DataLog::where()->all();
        $masSymbols = MasSymbol::lists('SYMBOL');
        if (count($masSymbols)) {
            return json_encode($masSymbols);
        }
        return json_encode([]);
    }
    
}