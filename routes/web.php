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
Route::redirect('/', 'sys', 301);
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
    Route::get('login', 'IndexController@index');//登录页
    Route::post('login', 'IndexController@login');//登录接口
    Route::get('register', 'IndexController@register');//注册页面
    Route::post('registerReg', 'IndexController@registerReg');//注册接口
});
//
//登录验证路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys', 'middleware' => ['isLogin', 'hasRole']], function () {
    Route::get('demo', 'IndexController@demo');//demo测试
    Route::get('', 'IndexController@index');//后台主页
    Route::get('logout', 'IndexController@logout');//退出系统
    //
    Route::group(['prefix' => 'pages/', 'namespace' => 'Pages'], function () {
        //框架公共部分
        Route::get('admInfo', 'IndexController@admInfo');//基本资料
        Route::post('admInfo', 'IndexController@admInfoUp');//基本资料接口
        Route::get('admPwd', 'IndexController@admPwd');//安全设置
        Route::post('admPwd', 'IndexController@admPwdUp');//安全设置接口
        Route::get('menu', 'IndexController@menu');//左侧菜单
        Route::get('console', 'IndexController@console');//控制台
        Route::get('weather', 'IndexController@weather');//天气预报
        //
        //管理员账号相关
        Route::group(['prefix' => 'admin/', 'namespace' => 'Admin'], function () {
            //账户列表
            Route::resource('admUser', 'AdmUserController');
            Route::get('admUserRead', 'AdmUserController@read');//获取页面数据
            Route::post('admUserDel', 'AdmUserController@del');//删除用户
            Route::post('admUserStart', 'AdmUserController@start');//启用用户
            Route::post('admUserStop', 'AdmUserController@stop');//禁止用户
            //
            //角色列表
            Route::resource('admUserRole', 'AdmUserRoleController');
            Route::get('admUserRoleRead', 'AdmUserRoleController@read');//获取页面数据
            Route::post('admUserRoleDel', 'AdmUserRoleController@del');//删除用户
            Route::post('admUserRoleStart', 'AdmUserRoleController@start');//启用用户
            Route::post('admUserRoleStop', 'AdmUserRoleController@stop');//禁止用户
            //角色授权页
            Route::get('admUserRoleReadEdit', 'AdmUserRoleController@edit');//编辑角色权限页面
        });
        //会员账号相关
        Route::group(['prefix' => 'member/', 'namespace' => 'Member'], function () {

        });
        //路由管理
        Route::group(['prefix' => 'routes/', 'namespace' => 'Routes'], function () {
            Route::resource('route', 'RouteController');
            Route::post('storeSon', 'RouteController@storeSon');//子项路由添加
            Route::get('routeSon/{id}/edit', 'RouteController@routeSonEdit');//路由编辑页
        });
        //框架相关
        Route::group(['prefix' => 'system/', 'namespace' => 'System'], function () {
            Route::get('alertSkin', 'SystemController@alertSkin');
        });
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
Event::listen('illuminate.query', function ($sql, $param) {
    file_put_contents(public_path() . '/sql.log', $sql . '[' . print_r($param, 1) . ']' . "\r\n", 8);
});
