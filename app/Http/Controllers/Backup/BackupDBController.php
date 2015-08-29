<?php namespace App\Http\Controllers\Backup;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class BackupDBController extends Controller {

    public function backup() {

//        $masSymbols = $this->getSymbolIsUse();
        $re = exec('mysqldump --user=root --password=root --host=localhost  --no-data > c:/xampp/backup.sql');
        echo $re;
    }

    
    
    protected function getSymbolIsUse() {

        $masSymbols = DB::table('TABLE_NAME')
                ->where('IS_USE', 1)
                ->lists('SYMBOL');

        return $masSymbols;
    }
    
}
