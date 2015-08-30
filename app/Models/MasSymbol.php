<?php

namespace App\Models; // การกำหนดที่อยู่ของ Model

use Illuminate\Database\Eloquent\Model; // การเรียกใช้งาน Eloquent ใน laravel
use Illuminate\Support\Facades\DB;

class MasSymbol extends Model {

    
    
    protected $table = 'MAS_SYMBOL'; // กำหนดชื่อของตารางที่ต้องการเรียกใช้
    protected $fillable = array('ID', 'SYMBOL', 'MARKET', 'IS_SET', 'IS_USE', 'IS_W', 'IS_DW', 'UPDATED_AT', 'CREATED_AT');
    
    public static function getSymbolIsUse(){
    
        $masSymbols = DB::table('MAS_SYMBOL')
        ->where('IS_USE' , 1)
        ->lists('SYMBOL');
        
        return $masSymbols;
    }
    
//    public static function getSymbolAll(){
//        $masSymbols = DB::table('MAS_SYMBOL')->lists('SYMBOL');
//
//        return $masSymbols;
//    }
    
}