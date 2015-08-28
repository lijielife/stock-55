<?php

namespace App\Http\Controllers\Logs;

//use Illuminate\Http\Request;
//use Auth;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class LogsActiveController extends Controller {

    public function getIndex() {
        return view('logs.active');
    }
    
}
