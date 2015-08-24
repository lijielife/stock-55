<?php

namespace App\Http\Controllers\History; //กำหนดที่อยู่ ของ Controller ที่เรียกใช้งาน

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubTableController extends Controller {

    private $origins = array("ruayhoon", "investor");

    private $is_online = false;
    
    public function getIndex() {
        
        
        
        if(!$this->is_online){
            return view('admin.dashboard.index');
        }
                
        set_time_limit(0);
        
//        $this->cleanData();
        $respone = new \stdClass();
                
        $symbolNames = $this->getSymbolIsUse();
        foreach ($symbolNames as $symbolName) {

            try {
                $historiesInsertAll = array();
                $historiesInsertTimeAll = array();
                foreach ($this->origins as $origin) {
                    $tableName = $this->getTableName($symbolName, $origin);
                    $historys = $this->getHistoryByName($symbolName, $origin);

                    $historiesInsert = array();
                    foreach ($historys as $history) {
                        $time = $history->TIME;
                        array_push($historiesInsert, get_object_vars($history));


                        if (!in_array($time, $historiesInsertTimeAll)) {
                            array_push($historiesInsertAll, get_object_vars($history));
                            array_push($historiesInsertTimeAll, $time);
                        }
                    }

//                    if (count($historiesInsert) > 0) {
//                        if ($this->createTable($tableName, $symbolName, $origin)) {
//                            foreach (array_chunk($historiesInsert, 1000) as $insertValue) {
//                                DB::table($tableName)->insert($insertValue);
//                            }
//                        }
//                    } else {
//                        DB::table('NO_DATA')->insert(["SYMBOL_ORIGIN" => $symbolName . $origin, "SYMBOL" => $symbolName, "ORIGIN" => $origin]);
//                    }
                }


                $tableName = $this->getTableName($symbolName, null);

                if (count($historiesInsertAll) > 0) {
                    if ($this->createTable($tableName, $symbolName)) {
                        foreach (array_chunk($historiesInsertAll, 1000) as $insertValue) {
                            DB::table($tableName)->insert($insertValue);
                        }
                    }
                } else {
                    DB::table('NO_DATA')->insert(["SYMBOL_ORIGIN" => $symbolName . "ALL", "SYMBOL" => $symbolName, "ORIGIN" => "ALL"]);
                }
                
                
                
                $respone->data = $historiesInsertAll;
                $respone->obj = $this;
                $respone->count = count($historiesInsertAll);

            } catch (Exception $e) {
                continue;
            } finally {
                $this->updateIsNotUse($symbolName);
            }
        }
                
        return view('admin.history.index', ['respone' => $respone]);
    }

    protected function cleanData() {

        $tableNames = DB::table('TABLE_NAME')->get();
        foreach ($tableNames as $tableName) {
            DB::statement('drop table IF EXISTS ' . $tableName->TABLE_NAME);
        }

        DB::table('TABLE_NAME')->DELETE();
        DB::table('NO_DATA')->DELETE();
        DB::update('update SYMBOL_NAME set is_USE = ?', ['1']);
    }

    protected function updateIsNotUse($symbolName) {
        DB::table('SYMBOL_NAME')->where('SYMBOL', $symbolName)->update(['IS_USE' => 0]);
    }

    protected function getTableName($symbolName, $origin) {

        $prefix = "";
        if ($origin !== null) {
            $prefix = ($origin == "ruayhoon" ? "RH" : "IN") . "_";
        }

        return $prefix .
                str_replace("-", "", str_replace("&", "", str_replace("*", "", $symbolName)))
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

    public function createTable($tableName = null, $symbolName = null, $origin = "ALL") {
        if ($tableName != null) {

            DB::statement('drop table IF EXISTS ' . $tableName);


            DB::statement('CREATE TABLE IF NOT EXISTS `' . $tableName . '` (
                  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                  `SYMBOL` varchar(11) NOT NULL,
                  `RESOLUTION` varchar(2) NOT NULL,
                  `MILLISEC` bigint(20) NOT NULL,
                  `TIME` varchar(15) NOT NULL,
                  `OPEN` decimal(10,2) NOT NULL,
                  `CLOSE` decimal(10,2) NOT NULL,
                  `HIGH` decimal(10,2) NOT NULL,
                  `LOW` decimal(10,2) NOT NULL,
                  `VOLUME` bigint(20) NOT NULL,
                  `ORIGIN` varchar(255) NOT NULL,
                  `UPDATED_AT` date NOT NULL,
                  `CREATED_AT` date NOT NULL,
                  PRIMARY KEY (`ID`),
                  KEY `NAME` (`SYMBOL`,`TIME`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2474868 ;');


            $is_check = DB::table('TABLE_NAME')->where(["table_name" => $tableName])->get();

            if (!$is_check) {
                DB::table('TABLE_NAME')->insert(["table_name" => $tableName
                    , "symbol" => $symbolName
                    , "origin" => $origin]);
            }


            return true;
        }
        return false;
    }

}
