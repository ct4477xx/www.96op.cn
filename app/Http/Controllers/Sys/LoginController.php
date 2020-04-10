<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use App\SysModel\AdmUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    //
    function index()
    {
        if (\Session()->get('admId')) {
            return view('.sys.index');
        } else {
            return view('.sys.login');
        }
    }


    function login(Request $request)
    {
        $inp = $request->all();

        $db_data = AdmUser::where([
            'userName' => $inp['data']['username'],
            'isDel' => 0
        ])
            ->select('id', 'code', 'userName', 'passWord','isLock')
            ->with(['admUserInfo:admId,name'])
            ->first();
        if (!$db_data) {
            return getSuccess('用户名不存在, 再仔细想想?');
        }
        if ($db_data['isLock']==1) {
            return getSuccess('当前账号已被锁定, 请联系系统管理员');
        }
        $is_Pwd = json_encode(Hash::check($inp['data']['password'], $db_data['passWord']));
        if ($is_Pwd == 'true') {
            $time = 1 * 60 * 12;//缓存时间
            \Session()->put('admId', $db_data['code']);
            \Cookie::queue('admId', $db_data['code'], $time);
            \Cookie::queue('admName', $db_data['admUserInfo']['name'] ? $db_data['admUserInfo']['name'] : $db_data['code'], $time);
            \Cookie::queue('admCode', $db_data['code'], $time);
            \Cookie::queue('captcha', null, -1);
            $data = [
                'success' => true
            ];
        } else {
            \Cookie::get('captcha') ? \Cookie::get('captcha') : 0;
            \Cookie::queue('captcha', \Cookie::get('captcha') + 1, 0);
            $data = [
                'success' => false,
                'msg' => '用户名或密码错误, 仔细想想吧',
                'username' => $inp['data']['username'],
                'password' => $inp['data']['password']
            ];
        }
        return $data;
    }

    function logout()
    {
        \Session()->forget('admId');
        \Cookie::queue('admId', null, -1);
        \Cookie::queue('admName', null, -1);
        \Cookie::queue('admCode', null, -1);
        return redirect('sys');
    }

    function register()
    {
        return view('sys.register');
    }

    function registerReg(Request $request)
    {
        $inp = $request->all();
        //判断用户名是否存在
        if (isHas('adm_user', 'userName', $inp['data']['username']) > 0) {
            return [
                'success' => false, 'msg' => '用户名已存在, 请换个用户名试试!',
                'username' => $inp['data']['username'],
                'password' => $inp['data']['password'],
            ];
        }
        $data = new AdmUser();
        $data['code'] = getNewId();
        $data['username'] = $inp['data']['username'];
        $data['password'] = Hash::make($inp['data']['password']);
        if ($data->save()) {
            //创建admInfo信息
            $info = new AdmUserInfo();
            $info['admId'] = $data['code'];
            $info['name'] = $inp['data']['username'];
            $info->save();
            return getSuccess(1);
        } else {
            return [
                'success' => false, 'msg' => '操作失败!',
                'username' => $inp['data']['username'],
                'password' => $inp['data']['password'],
            ];
        }
    }

    function forget()
    {
        return view('sys.pages.forget');
    }
}
