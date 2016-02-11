<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Models; // การกำหนดที่อยู่ของ Model

//use Illuminate\Database\Eloquent\Model; // การเรียกใช้งาน Eloquent ใน laravel
use Illuminate\Support\Facades\DB;

final class StockModel {

    
    private function __construct() {}
    
     public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new StockModel();
        }
        return $inst;
    }
    
    public function getConfigByName($userId, $configName) {
        $dataLogs = DB::select(
        "SELECT uc.CONFIG_VALUE 
        FROM USER_CONFIG uc 
        JOIN CONFIG c ON(c.CONFIG_ID = uc.CONFIG_ID)
        WHERE uc.USER_ID = ? 
            AND c.CONFIG_NAME = ?"
        , [$userId, $configName]);
        
        foreach ($dataLogs as $dataLog) {
            return $dataLog->CONFIG_VALUE;
        }
        return null;
    }
    
    public function getGlobalConfigByName($configName) {
        $dataLogs = DB::select(
        "SELECT c.GLOBAL_CONFIG_VALUE 
        FROM CONFIG c 
        WHERE c.CONFIG_NAME = ?"
        , [$configName]);
        
        foreach ($dataLogs as $dataLog) {
            return $dataLog->GLOBAL_CONFIG_VALUE;
        }
        return null;
    }
    
    
    
}