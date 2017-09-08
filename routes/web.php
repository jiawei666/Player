<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('aaa/{id?}/{name?}','TestController@testFunction');

Route::group(['middleware'=>['activity']],function(){
    Route::any('activity1','TestController@activity1');
});

Route::any('activity0','TestController@activity0');

Route::any('index',['uses'=>'Index@index','as'=>'index']);

Route::any('recommend',['uses'=>'Index@recommend']);

Route::any('getMusic',['uses'=>'Index@getMusic']);

Route::any('search',['uses'=>'Index@search']);

Route::any('downloadSearch',['uses'=>'Index@downloadSearch']);

Route::any('test',['uses'=>'Index@test']);

Route::any('sql',['uses'=>'Sql@index']);


