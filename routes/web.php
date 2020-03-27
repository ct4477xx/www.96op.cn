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

//Route::get('/', function () {
//    return view('csign.index');
//});
Route::redirect('/', 'csign', 301);

/**
 *
 * 公共方法
 *
 */
Route::get('AjaxReadKey/{type}/{Id}', 'Tool\AjaxReadKeyController@show');
//
//
//
Route::group(['namespace' => 'Tool'], function () {
//创建资源类
    Route::resource('test', 'TestController');
//网址缩短
    Route::resource('t', 'UrlController');
//小区出入签到系统
    Route::resource('csign', 'CsignController');
});
//
//
//
Route::get('sys/login', 'Sys\LoginController@index');
//
Route::group(['namespace' => 'Sys', 'middleware' => ['IsLogin']], function () {
    Route::get('sys', 'IndexController@index');

});
