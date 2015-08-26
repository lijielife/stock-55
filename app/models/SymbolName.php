<?php

namespace App\Models; // การกำหนดที่อยู่ของ Model

use Illuminate\Database\Eloquent\Model; // การเรียกใช้งาน Eloquent ใน laravel
use Illuminate\Support\Facades\DB;

class SymbolName extends Model {

    
    
    protected $table = 'SYMBOL_NAME'; // กำหนดชื่อของตารางที่ต้องการเรียกใช้
    protected $fillable = array('ID', 'SYMBOL', 'IS_USE', 'UPDATED_AT', 'CREATED_AT');
    
    public static function getSymbolIsUse(){
    
        $symbolNames = DB::table('SYMBOL_NAME')
        ->where('IS_USE' , 1)
        ->lists('SYMBOL');
        
        return $symbolNames;
    }
    
//    public static function getSymbolAll(){
//        $symbolNames = DB::table('SYMBOL_NAME')->lists('SYMBOL');
//
//        return $symbolNames;
//    }
    
}