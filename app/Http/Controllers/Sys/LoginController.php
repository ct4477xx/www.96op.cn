<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class LoginController extends Controller
{
    //
    function index()
    {
        if (Session()->get('admId')) {
            return view('sys.index');
        } else {
            return view('sys.pages.login');
        }
    }


    function login(Request $request)
    {
        $inp = $request->all();

        $db_data = AdmUser::where(['userName' => $inp['data']['username'], 'passWord' => $inp['data']['password']])
            ->select('id', 'userName', 'passWord')
            ->with(['admUserInfo:admId,name'])
            ->first();
        if ($db_data) {
            \Session()->put('admId', $db_data['id']);
            \Cookie::queue('admName', $db_data['admUserInfo']['name'], 1 * 60 * 12);
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
        \Cookie::queue('admName', null, -1);
        return redirect('sys');
    }

    function register()
    {
        return view('sys.pages.register');
    }

    function registerReg(Request $request)
    {
        $inp = $request->all();
        //判断用户名是否存在
        if (IsHas('adm_user', 'userName', $inp['data']['username']) > 0) {
            return [
                'success' => false, 'msg' => '用户名已存在, 请换个用户名试试!',
                'username' => $inp['data']['username'],
                'password' => $inp['data']['password'],
            ];
        }
        $data = new AdmUser();
        $data['id'] = getNewId();
        $data['username'] = $inp['data']['username'];
        $data['password'] = Crypt::encrypt($inp['data']['password']);
        if ($data->save()) {
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