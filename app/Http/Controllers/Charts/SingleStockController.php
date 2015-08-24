<?php

namespace App\Http\Controllers\Charts;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class SingleStockController extends Controller {

    public function getIndex() {

        return view('charts.singlestock');
    }
    
    protected function getSymbolIsUse(){
    
        $symbolNames = DB::table('SYMBOL_NAME')
        ->where('IS_USE' , 1)
        ->lists('SYMBOL');
        
        return $symbolNames;
    }
}
