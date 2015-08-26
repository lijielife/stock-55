<?php

use App\Models\SymbolName;
use App\Models\History;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//  defualt
// Route::get('/', function () {
// 	return view('welcome');
// });

Route::get('/', 'Admins\LoginController@getIndex');


// User Test 

// Route DB
Route::get('check-connect',function(){
	if(DB::connection()->getDatabaseName())
	{
		return "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
	}else{
		return 'Connection False !!';
	}

});


// model
Route::get('check-model','HistoryController@getIndex');

// Route::controller('admin/index','Admins\DashboardController');

//  // Offline Login Page
// Route::controller('admin/login','Admins\LoginController');
 
// Start Online Page


// User
Route::group(['prefix'=>'admin','middleware'=>'auth','namespace'=>'Admins'],function($param){
    Route::controller('blank','BlankController');
    Route::controller('login','LoginController');
    Route::controller('dashboard','DashboardController');
    Route::controller('user','UserController');
    Route::controller('index', 'DashboardController');
});

// History

Route::get('history/get', 'History\GetController@getIndex');
Route::get('history/load', 'History\LoadController@getIndex');

Route::get('history/get2', 'History\RuayHoonGetController@getIndex');
Route::get('history/load2', 'History\RuayHoonLoadController@getIndex');

Route::get('backup', 'Backup\BackupDBController@backup');


Route::get('sub-table','History\SubTableController@getIndex');


// All Page
Route::get('pages/{category}', function($category){
    return view('admin.pages.'.$category);
});


// Chart 
Route::get('single-stock','Charts\SingleStockController@getIndex');



//service


//Route::get('getAllSymbol',function(){
//    
//    $symbolNames = DB::table('SYMBOL_NAME')->lists('SYMBOL');
//
//    return json_encode($symbolNames);
//    
//});


Route::get('getAllSymbol',function(){
    
//    Cache::forget('getAllSymbol');

    $ret = Cache::get('getAllSymbol', function() { 
        $symbolNames = SymbolName::lists('SYMBOL');
        if(count($symbolNames)){
        
            Cache::add('getAllSymbol', json_encode($symbolNames), date('Y-m-d H:i:s'));

            return json_encode($symbolNames);
            
        }
        return json_encode([]);
        
    });
    
    return $ret;
    
});

Route::get('getSingleStock', function(){
    
    $symbol = Request::input('symbol');
    $cacheName = 'getSingleStock' . $symbol;
    
//    Cache::forget($cacheName);
//    
//    
//    header('Content-type: application/json');
//
//  // Do not cache the response
//    header('Cache-Control: no-cache, must-revalidate');
//    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');  
     
    $ret = Cache::get($cacheName, function() use( &$cacheName) { 
        $symbol = Request::input('symbol');
        $historys = App\Models\History::where("symbol", $symbol)->get();
        if(count($historys)){
        
            $datas = array();
            
            foreach ($historys as $history) {
                $data = array();
                array_push($data, $history->millisec * 1000);
                array_push($data, (double)$history->OPEN);
                array_push($data, (double)$history->HIGH);
                array_push($data, (double)$history->LOW);
                array_push($data, (double)$history->CLOSE);
                array_push($data, (double)$history->VOLUME);
                  
                array_push($datas , $data);
                        
//    protected $fillable = array('ID', 'SYMBOL', 'RESOLUTION', 'MILLISEC', 'TIME'
//        , 'OPEN', 'CLOSE', 'HIGH', 'LOW', 'VOLUME', 'ORIGIN', 'UPDATED_AT', 'CREATED_AT');
                
            }
            
            
//            $symbol = Request::input('symbol');
//            $cacheName = 'getSingleStock' . $symbol;

            Cache::add($cacheName, json_encode($datas), date('Y-m-d H:i:s'));

            return json_encode($datas);
            
        }
        return json_encode([]);
        
    });
    
    return $ret;
});




