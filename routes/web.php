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
Route::redirect('/', 'csign', 301);
/**
 *
 * 公共方法
 *
 */
Route::get('ajaxReadKey/{type}/{Id}', 'AjaxReadKeyController@show');
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
//免登录路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys'], function () {
    Route::get('login', 'LoginController@index');//登录页
    Route::post('login', 'LoginController@login');//登录接口
});
//
//登录验证路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys', 'middleware' => 'IsLogin'], function () {
    Route::get('', 'IndexController@index');//后台主页
    Route::get('welcome', 'IndexController@welcome');//控制台
    //
    Route::group(['prefix' => 'blade/'], function () {
        Route::get('logout', 'LoginController@logout');
        //
        //房屋管理
        Route::group(['prefix' => 'house/'], function () {
            Route::resource('house', 'HouseController');
            Route::PATCH('houseRoom', 'HouseController@houseRoomStore');
            Route::get('houseRoom/{id}', 'HouseController@houseRoomEdit');
            Route::resource('tenant', 'TenantController');
        });
    });

});
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
