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
use App\Http\Controllers\Sys\IndexController;

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
    Route::get('register', 'LoginController@register');//注册页面
    Route::post('register', 'LoginController@registerReg');//注册页面
    Route::get('forget', 'LoginController@forget');//找回密码页面
});
//
//登录验证路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys', 'middleware' => 'IsLogin'], function () {
    Route::get('', 'IndexController@index');
    Route::get('index', 'IndexController@index');
    Route::get('pages/logout', 'LoginController@logout');
    Route::get('pages/console', 'IndexController@console');
    Route::get('pages/weather', 'IndexController@weather');
    Route::get('welcome1', 'IndexController@welcome1');
    Route::resource('house', 'HouseController');
    Route::PATCH('houseRoom', 'HouseController@houseRoomStore');
    Route::get('houseRoom/{id}', 'HouseController@houseRoomEdit');
    //
    Route::resource('tenant', 'TenantController');
});
//Route::get('/clear-cache', function() {
//    Artisan::call('cache:clear');
//    return "Cache is cleared";
//});
