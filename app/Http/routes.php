<?php

//use App\Models\SymbolName;

//use App\Models\History;
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
Route::get('check-connect', function() {
    if (DB::connection()->getDatabaseName()) {
        return "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
    } else {
        return 'Connection False !!';
    }
});


// model
Route::get('check-model', 'HistoryController@getIndex');

// Route::controller('admin/index','Admins\DashboardController');
//  // Offline Login Page
// Route::controller('admin/login','Admins\LoginController');
// Start Online Page
// User
Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admins'], function($param) {
    Route::controller('blank', 'BlankController');
    Route::controller('login', 'LoginController');
    Route::controller('dashboard', 'DashboardController');
    Route::controller('user', 'UserController');
    Route::controller('index', 'DashboardController');
});


// History

Route::get('history/get', 'History\GetController@getIndex');
//Route::get('history/load', 'History\LoadController@getIndex');



Route::get('history/load', 'History\LoadController@getIndex');
Route::get('history/loadData', 'History\LoadController@loadData');
Route::get('history/getStatus', 'History\LoadController@getStatus');

Route::get('history/get2', 'History\RuayHoonGetController@getIndex');

Route::get('history/load2', 'History\RuayHoonLoadController@getIndex');
Route::get('history/loadData2', 'History\RuayHoonLoadController@loadData');
Route::get('history/getStatus2', 'History\RuayHoonLoadController@getStatus');

//Route::get('history/load2', 'History\RuayHoonLoadController@getIndex');

Route::get('backup', 'Backup\BackupDBController@backup');


Route::get('sub-table', 'History\SubTableController@getIndex');


// All Page
Route::get('pages/{category}', function($category) {
    return view('admin.pages.' . $category);
});


// Chart 
Route::get('single/stock', 'Charts\SingleStockController@getIndex');



// Logs
//Route::get('logs/active', 'Logs\LogsActiveController@getIndex');
//Route::get('logs/import', 'Logs\LogsImportController@getIndex');

Route::group(['prefix'=>'logs','middleware'=>'auth','namespace'=>'Logs'],function(){
//    Route::controller('index','BlankController');
//    Route::controller('user','UserController');
    
    Route::controller('active', 'LogsActiveController');
    Route::controller('import','LogsImportController');
//    Route::controller('profile','LogsProfileController');
//    Route::controller('dividend','LogsDividendController');
//    [
//        'getData' => 'getData',
//    ]);
    
    
//    Route::group(['prefix' => 'save'], function()
//    {
//        Route::get('/', 'LogsImportController@saveDataLogs');
//    });
//    
//    Route::group(['prefix' => 'save'], function()
//    {
//        Route::get('/', 'LogsImportController@saveDataLogs');
//    });
    
    Route::group(['prefix' => 'profile'], function()
    {
        Route::get('/', 'LogsProfileController@getIndex');
        Route::get('getData', 'LogsProfileController@data_json');
        Route::get('save', 'LogsImportController@saveDataLogs');
        Route::get('deleteTest', 'LogsImportController@deleteTest');
        Route::get('deleteTestAll', 'LogsImportController@deleteTestAll');
    });
    
    Route::group(['prefix' => 'dividend'], function()
    {
        Route::get('/', 'LogsDividendController@getIndex');
        Route::get('getData', 'LogsDividendController@data_json');
    });

    Route::group(['prefix' => 'total'], function()
    {
        Route::get('/', 'LogsTotalController@getIndex');
        Route::get('getData', 'LogsTotalController@data_json');
        Route::get('showActive', 'LogsTotalController@changeShowActive');
    });
    
    Route::group(['prefix' => 'inday'], function()
    {
        Route::get('/', 'LogsInDayController@getIndex');
        Route::get('getData', 'LogsInDayController@data_json');
    });
    
    Route::group(['prefix' => 'snapshot'], function()
    {
        Route::get('/', 'SnapShotController@getIndex');
        Route::get('getData', 'SnapShotController@data_json');
    });
    
    
    
//    Route::controller('upload','LogsUploadController');
});


//Route::post('logs/active', 'Logs\LogsActiveController@getIndex');
//Route::post('logs/profile', 'Logs\LogsProfileController@getIndex');
//Route::post('logs/dividend', 'Logs\LogsDividendController@getIndex');

//service
//Route::get('getAllSymbol',function(){
//    
//    $masSymbols = DB::table('MAS_SYMBOL')->lists('SYMBOL');
//
//    return json_encode($masSymbols);
//    
//});


//Route::get('getAllSymbol', 'Service\SingleStockService@getAllSymbol');
//
//Route::get('getSingleStock', 'Service\SingleStockService@getSingleStock');
//
//Route::get('getAllBroker', 'Service\SingleStockService@getAllBroker');



Route::group(['prefix'=>'service/single','middleware'=>'auth','namespace'=>'Service'],function(){
    
    Route::get('{name}', function($name) {
        $servicess = new App\Http\Controllers\Service\SingleStockService();
        return $servicess->$name();
    });

});


Route::group(['prefix'=>'service/mapdata','middleware'=>'auth','namespace'=>'Service'],function(){
    
    Route::get('{name}', function($name) {
        $servicess = new App\Http\Controllers\Service\MapDataLogsService();
        return $servicess->$name();
    });


//    Route::post('{name}', function($name) {
//        $servicess = new App\Http\Controllers\Service\MapDataLogsService();
//        return $servicess->$name();
//    });
    
});



Route::group(['prefix'=>'service/cleardata','middleware'=>'auth','namespace'=>'Service'],function(){
    
    Route::get('{name}', function($name) {
        $servicess = new App\Http\Controllers\Service\ClearDataService();
        return $servicess->$name();
    });
    
    
//Route::get('service/cleardatadup', 'Service\ClearDataService@dataDupicate');


});



//Route::any('foo/bar', 'AuthController@login');

//    Route::any('foo/bar', function()
//    {
//        return 'Hello World';
//    });


