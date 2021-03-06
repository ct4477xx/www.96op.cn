<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmUser;
use Illuminate\Http\Request;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class IndexController extends Controller
{
    //首页 / 登录页
    function index()
    {
        if (_admCode()) {
            return view('.sys.index');
        } else {
            return view('.sys.login');
        }
    }

    //登录接口
    function login(Request $request)
    {
        $inp = $request->all();

        $db_data = AdmUser::where([
            'user_name' => $inp['data']['user_name'],
            'is_del' => 0
        ])
            ->select('id', 'code', 'user_name', 'pass_word', 'is_lock')
            ->with(['admUserInfo:adm_code,name'])
            ->first();
        if (!$db_data) {
            return getSuccess('用户名不存在, 再仔细想想?');
        }
        if ($db_data['is_lock'] == 1) {
            $res = [
                'success' => false,
                'msg' => '当前账号已被锁定, 请联系系统管理员',
                'user_name' => $inp['data']['user_name'],
                'pass_word' => $inp['data']['pass_word']
            ];
            return $res;
        }
        $is_Pwd = json_encode(Hash::check($inp['data']['pass_word'], $db_data['pass_word']));
        if ($is_Pwd == 'true') {
            $time = base()['cacheTime'];//缓存时间
            //\Session()->put('admId', $db_data['code']);
            \Cookie::queue('admId', $db_data['id'], $time);
            $name = $db_data['admUserInfo']['name'];
            \Cookie::queue('admName', $name ? $name : $db_data['code'], $time);
            \Cookie::queue('admCode', $db_data['code'], $time);
            \Cookie::queue('captcha', null, -1);//验证码校验
            //
            _admCache($db_data['code']);//销毁缓存
            $res = [
                'success' => true
            ];
        } else {
            \Cookie::get('captcha') ? \Cookie::get('captcha') : 0;
            \Cookie::queue('captcha', \Cookie::get('captcha') + 1, 0);
            $res = [
                'success' => false,
                'msg' => '用户名或密码错误, 仔细想想吧',
                'user_name' => $inp['data']['user_name'],
                'pass_word' => $inp['data']['pass_word']
            ];
        }
        return $res;
    }

    //退出接口
    function logout()
    {
        //\Session()->forget('admId');
//        \Session()->forget('routeIds');
        \Cookie::queue('admId', null, -1);
        \Cookie::queue('admName', null, -1);
        \Cookie::queue('admCode', null, -1);
        return redirect('sys');
    }

    //注册接口
    function register()
    {
        return view('.sys.register');
    }

    //注册提交接口
    function registerReg(Request $request)
    {
        $inp = $request->all();
        //判断用户名是否存在
        if (getIsExist('adm_user', 'user_name', $inp['data']['user_name']) > 0) {
            return [
                'success' => false, 'msg' => '用户名已存在, 请换个用户名试试!',
                'user_name' => $inp['data']['user_name'],
                'pass_word' => $inp['data']['pass_word'],
            ];
        }
        $data = new AdmUser();
        $data['code'] = getNewId();
        $data['user_name'] = $inp['data']['user_name'];
        $data['pass_word'] = Hash::make($inp['data']['pass_word']);
        $data['is_lock'] = 1;
        $data['is_del'] = 0;
        $data['add_code'] = '';
        $data['add_time'] = getTime(1);
        if ($data->save()) {
            //用户注册后自动生成关联信息表
            $info = new AdmUserInfo();
            $info['adm_code'] = $data['code'];
            $info['name'] = $data['code'];
            $info->save();
            return getSuccess(1);
        } else {
            return [
                'success' => false, 'msg' => '操作失败!',
                'user_name' => $inp['data']['user_name'],
                'pass_word' => $inp['data']['pass_word'],
            ];
        }
    }


    function demo()
    {


//
//        Redis::set('name',123);


        $arrs = [];
        $listKey = 'LIST:ADM';
        $hashKey = 'HASH:ADM:';

        if (Redis::exists($listKey)) {
            $lists = Redis::Lrange($listKey, 0, -1);
            foreach ($lists as $k => $v) {
                $arrs[] = Redis::hgetall($hashKey . $v);
            }
        } else {
            $arrs= AdmUser::get()->toArray();
            foreach ($arrs as $k => $v) {
                Redis::rpush($listKey . $v['id']);
                Redis::hmset($hashKey . $v['code'], $v);
            }
        }

//        return $arrs;


        //Redis::set('name', _admName());
//        $values = Redis::get('name');

//        return $values;


//        if (Cache::has('as-' . _admCode())) {
//            echo Cache::get('as-' . _admCode());
//            Cache::forget('as-' . _admCode());//权限值
//        } else {
//            Cache::put('as-' . _admCode(), "3");
//        }


        //return objectToArray(Cache::get('routeIds-' . _admCode()));
        //Cache::forget('routeIds-' . _admCode());//权限值
        //Cache::put('321-' . _admCode(), "321");


//        $getMenu = getMenu();
//        return $getMenu;

//        return father(52, getRouteData(3));
    }

    //找回密码
//    function forget()
//    {
//        return view('.sys.pages.forget');
//    }
}
