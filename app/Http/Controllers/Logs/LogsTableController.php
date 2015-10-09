<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

abstract class LogsTableController extends Controller {
    
    abstract public function getIndex();    
    
    public function data_json(){
        return json_encode($this->calData($this->getDataLogs()));
    }
    
    public function getPriceBetweenDate($symbol, $firstDAte, $endDAte){
        
        $tableName = $this->getTableName($symbol);
        
        $isTableExist = $this->checkTableIsExit($tableName);
                
        return ($isTableExist ? DB::table($tableName)
                ->where('symbol', $symbol)
                ->whereBetween('TIME', [$firstDAte, $endDAte])
                ->lists('CLOSE', 'TIME'): array());
    }
    
    
    private function checkTableIsExit($tableName) {
        $result = DB::select("SELECT COUNT(*) as CNT FROM information_schema.tables WHERE table_name = '$tableName'");
        return ($result[0]->CNT > 0 ? true : false);
//        SHOW TABLES LIKE 'history_abpif'
    }

    
    
    abstract public function calData($stocks);

    abstract public function getDataLogs();
    
//    public function calValue($valueBeforeVat){
//        return ;
//    }
    public static function getVat($valueBeforeVat){
        return ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
    }
//    public function calValueDiv($valueBeforeVat){
//        return ($valueBeforeVat) - (($valueBeforeVat * 0.001578) + (($valueBeforeVat * 0.001578) * 7 / 100));
//    }
}