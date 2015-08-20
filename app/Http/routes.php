<?php

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
Route::group(['prefix'=>'admin','middleware'=>'auth','namespace'=>'Admins'],function($param){
    Route::controller('blank','BlankController');
    Route::controller('login','LoginController');
    Route::controller('dashboard','DashboardController');
    Route::controller('user','UserController');
    Route::controller('index', 'DashboardController');
});


Route::get('history/get', 'History\GetController@getIndex');

Route::get('history/get2', 'History\RuayHoonGetController@getIndex');

Route::get('history/get2', 'History\RuayHoonLoadController@getIndex');



Route::get('pages/{category}', function($category){
    return view('admin.pages.'.$category);
});


//Route::bind('pages', function($slug) {
//  return Page::whereSlug($slug)->first();
//});


// Start Online Page
// Route::group(['prefix'=>'admin','middleware'=>'auth','namespace'=>'Admins'],function(){
//  Route::get('index',function(){
//  return 'Login Success!!';
//  });
// });

