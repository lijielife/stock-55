<?php

use App\Models\SymbolName;
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



