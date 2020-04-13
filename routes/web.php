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
//免登录路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys'], function () {
    Route::get('login', 'LoginController@index');//登录页
    Route::post('login', 'LoginController@login');//登录接口
    Route::get('register', 'LoginController@register');//注册页面
    Route::post('registerReg', 'LoginController@registerReg');//注册接口
});
//
//登录验证路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys', 'middleware' => 'IsLogin'], function () {
    Route::get('', 'IndexController@index');//后台主页
    Route::get('logout', 'LoginController@logout');//退出系统
    Route::get('userInfo', 'IndexController@userInfo');//基本资料
    Route::post('userInfo', 'IndexController@userInfoUp');//基本资料接口
    Route::get('userPwd', 'IndexController@userPwd');//安全设置
    Route::post('userPwd', 'IndexController@userPwdUp');//安全设置接口
    Route::get('menu', 'IndexController@menu');//左侧菜单
    //
    Route::group(['prefix' => 'pages/'], function () {
        //
        Route::get('console', 'IndexController@console');//控制台
        Route::get('weather', 'IndexController@weather');//天气预报
        //
        //账号相关
        Route::group(['prefix' => 'member/'], function () {
            Route::resource('admUser','AdmUserController');
            Route::get('read','AdmUserController@read');//获取列表
            Route::post('admStart','AdmUserController@start');//启用用户
            Route::post('admStop','AdmUserController@stop');//禁止用户
            //
            Route::resource('admUserRole','AdmUserRoleController');
            Route::resource('admUserPower','AdmUserPowerController');
        });
        //房屋管理
        Route::group(['prefix' => 'house/'], function () {
            Route::resource('house', 'HouseController');
            Route::PATCH('houseRoom', 'HouseController@houseRoomStore');
            Route::get('houseRoom/{id}', 'HouseController@houseRoomEdit');
            Route::resource('tenant', 'TenantController');
        });
        //系统相关
        Route::group(['prefix' => 'system/'], function () {
            Route::get('alertSkin', 'IndexController@alertSkin');
        });
        //路由管理
        Route::group(['prefix' => 'routes/'], function () {
            Route::resource('route', 'RouteController');
            Route::post('storeSon','RouteController@storeSon');
            Route::get('storeSon/{id}/edit','RouteController@storeSonEdit');
        });
    });
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
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
Event::listen('illuminate.query', function($sql,$param) {
    file_put_contents(public_path().'/sql.log',$sql.'['.print_r($param, 1).']'."\r\n",8);
});
