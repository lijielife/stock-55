<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;
use Illuminate\Support\Facades\Request;
use App\Utils\SystemUtils;

abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;

    protected $USER_ID;

    public function __construct() {
        $users = Session::get('username');
        if($users){
            $this->USER_ID = $users->id;
        }
        $this->middleware('admins');
    }
    
    protected function getTableName($masSymbol, $origin = null) {
        return SystemUtils::getTableName($masSymbol, $origin);
    }
    
    protected function getRequestParam($param) {
        return Request::input($param);
    }
}
