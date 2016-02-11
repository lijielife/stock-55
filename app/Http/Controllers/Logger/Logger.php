<?php

namespace App\Http\Controllers\Logger;

use Log;
use App\Models\StockModel;
use App\Constants\StockConstant;

final class Logger {
    
    protected $stockModel;
    private $logIsDebug = true;
    
    protected $view = StockConstant::LOGGER_VIEW;
    protected $isDebug = StockConstant::IS_DEBUG;
    
    private function __construct() {
        $this->stockModel = StockModel::Instance();
        $this->logIsDebug = filter_var($this->stockModel->getGlobalConfigByName($this->view.$this->isDebug), FILTER_VALIDATE_BOOLEAN);
    }    
    
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Logger();
        }
        return $inst;
    }
    
    public function info($str){
        if($this->logIsDebug){
            Log::info($str);
        }
    } 
    
    public function warning($str){
        if($this->logIsDebug){
            Log::warning($str);
        }
    } 
    public function error($str){
        if($this->logIsDebug){
            Log::error($str);
        }
    } 
    
    
}
    
