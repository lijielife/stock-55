<?php

namespace App\Http\Controllers; //กำหนดที่อยู่ ของ Controller ที่เรียกใช้งาน

use Illuminate\Support\Facades\DB;

class SubTableController extends Controller {

    private $origins = array("investor");

    public function getIndex() {
//        $this->cleanData();
        
        $symbolNames = $this->getSymbolIsUse();
        foreach ($symbolNames as $symbolName) {

            try {
                foreach ($this->origins as $origin) {
                    $tableName = $this->getTableName($symbolName, $origin);
                    $historys = $this->getHistoryByName($symbolName, $origin);

                    $historiesInsert = array();
                    foreach ($historys as $history) {
                        array_push($historiesInsert, get_object_vars($history));
                    }

                    if (count($historiesInsert) > 0) {
                        if ($this->createTable($tableName, $symbolName, $origin)) {
                            foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
                                DB::table($tableName)->insert($insertValue);
                            }
                        }
                    } else {
                        DB::table("NO_DATA")->insert(["SYMBOL_ORIGIN"=>$symbolName.$origin,"SYMBOL"=>$symbolName, "ORIGIN"=>$origin]);
                    }
                }
            } catch (Exception $e) {
                continue;
            } finally {
                $this->updateIsNotUse($symbolName);
            }
        }
    }

    
    protected function cleanData() {

//                    DB::table("NO_DATA")->insert(["SYMBOL"=>$symbolName, "ORIGIN"=>$originInUse]);
        $tableNames = DB::table('table_name')->get();
        foreach ($tableNames as $tableName) {
            DB::statement('drop table IF EXISTS ' . $tableName->TABLE_NAME);
        }
        
        DB::table('table_name')->DELETE();
        DB::table('NO_DATA')->DELETE();
        DB::update('update SYMBOL_NAME set is_USE = ?', ['1']);
    }

    protected function updateIsNotUse($symbolName) {

        DB::table('SYMBOL_NAME')->where('SYMBOL', $symbolName)->update(['IS_USE' => 0]);
    }

    protected function getTableName($symbolName, $origin) {
        return ($origin == "ruayhoon" ? "RH" : "IN") . "_" . 
                str_replace("-", "", str_replace("&", "", str_replace("*", "", $symbolName)) ) 
                . "_HISTORY";
    }

    protected function getSymbolIsUse() {

        $symbolNames = DB::table('SYMBOL_NAME')
                ->where('IS_USE', 1)
                ->lists('SYMBOL');

        return $symbolNames;
    }

    protected function getHistoryByName($symbolName, $origin) {
        $historys = DB::table('HISTORY')
                ->where('SYMBOL', $symbolName)
                ->where('ORIGIN', $origin)
                ->get();
        return $historys;
    }

    public function createTable($tableName = null, $symbolName = null, $origin = null) {
        if ($tableName != null) {

            DB::statement('drop table IF EXISTS ' . $tableName);


            DB::statement('CREATE TABLE IF NOT EXISTS `' . $tableName . '` (
                  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                  `SYMBOL` varchar(11) NOT NULL,
                  `RESOLUTION` varchar(2) NOT NULL,
                  `MILLISEC` bigint(20) NOT NULL,
                  `TIME` varchar(15) NOT NULL,
                  `OPEN` decimal(10,0) NOT NULL,
                  `CLOSE` decimal(10,0) NOT NULL,
                  `HIGH` decimal(10,0) NOT NULL,
                  `LOW` decimal(10,0) NOT NULL,
                  `VOLUME` bigint(20) NOT NULL,
                  `ORIGIN` varchar(255) NOT NULL,
                  `UPDATED_AT` date NOT NULL,
                  `CREATED_AT` date NOT NULL,
                  PRIMARY KEY (`ID`),
                  KEY `NAME` (`SYMBOL`,`TIME`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2474868 ;');
            
            
            DB::table("table_name")->insert(["table_name"=>$tableName
                    , "symbol"=>$symbolName
                    , "origin"=>$origin]);
            
            
            return true;
        }
        return false;
    }

}
