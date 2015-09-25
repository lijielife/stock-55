<?php

namespace App\Http\Controllers\Service;

//use Illuminate\Http\Request;
//use Auth;
use \App\Http\Controllers\History\HistoryController;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MasSymbol;

//use App\Models\History;

class ClearDataService extends HistoryController {

    public function dataDupicate() {
        $symbols = $this->getSymbolIsUse();
        foreach ($symbols as $symbol) {
            $tableName = $this->getTableName($symbol);
            $timeInUse = DB::table($tableName)
                    ->groupBy('MILLISEC')
                    ->having(DB::raw('COUNT(*)'), '>', 1)
                    ->lists('MILLISEC');
            $result = DB::table($tableName)
                    ->where('ORIGIN', 'ruayhoon')
                    ->whereIn('MILLISEC', $timeInUse)
                    ->delete();
            $this->updateIsNotUse($symbol);
        }
    }

    public function createTBFromMas() {
        
        $symbols = $this->getSymbolIsUse();
        
        foreach ($symbols as $symbol) {
            $tableName = $this->getTableName($symbol);
            $this->createTable($tableName, $symbol);
        }
    }
    
    public function alterTable(){
        $this->resetData();
        $symbols = $this->getSymbolIsUse();
        
        foreach ($symbols as $symbol) {
            $tableName = $this->getTableName($symbol);
//            $this->createTable($tableName, $symbol);
            try {
                
                DB::statement('ALTER TABLE `super_stock_db`.`'.$tableName.'` 
                ADD INDEX `TIME` (`TIME` DESC) ;');

            } catch (\Exception $exc) {
                continue;
//                echo $exc->getTraceAsString();
            }

        }
        
    }
}
