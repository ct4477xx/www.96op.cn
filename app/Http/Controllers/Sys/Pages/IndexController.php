<?php

namespace App\Http\Controllers\Sys\Pages;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use App\SysModel\AdmUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    //

    function console()
    {
        //控制台
        return view('sys.pages.console');
    }

    function weather()
    {
        //天气预报
        return view('sys.pages.weather');
    }


    function userInfo()
    {
        //个人用户信息
        //在没有找到用户资料时,创建用户资料
        if (getIsExist('adm_user_info', 'admCode', \Cookie::get('admCode')) == 0) {
            $info = new AdmUserInfo();
            $info['admCode'] = \Cookie::get('admCode');
            $info['name'] = \Cookie::get('admName');
            $info->save();
        }
        $db = AdmUserInfo::where('admCode', \Cookie::get('admCode'))
            ->first();
        return view('sys.pages.member.userInfo', $db);
    }

    function userInfoUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        $info = $db = AdmUserInfo::where('admCode', _admCode())
            ->update(
                [
                    'name' => $inp['data']['name'],
                    'sex' => $inp['data']['sex'] == 'false' ? 0 : 1,
                    'birthDate' => $inp['data']['birthDate'],
                    'mobile' => $inp['data']['mobile'],
                    'mail' => $inp['data']['mail']
                ]
            );

        $bool = AdmUser::where('code', _admCode())->update(['upCode' => _admCode(), 'upTime' => getTime(1)]);
        if ($info || $bool) {
            $time = 1 * 60 * 12;//缓存时间
            \Cookie::queue('admName', $inp['data']['name'], $time);

            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    function userPwd()
    {
        //个人密码
        return view('sys.pages.member.userPwd');
    }

    function userPwdUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        //查找用户
        $db = AdmUser::where('code', \Cookie::get('admCode'))
            ->select(['password'])
            ->first();
        $is_Pwd = json_encode(Hash::check($inp['data']['oldPwd'], $db['password']));
        if ($is_Pwd == 'false') {
            return getSuccess('旧密码错误，请重新输入！');
        }
        $data = AdmUser::where('code', \Cookie::get('admCode'))
            ->update(['password' => Hash::make($inp['data']['password'])]);
        if ($data) {
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    function menu()
    {
        //无限极菜单
//        $data = Menu::where(['fatherId' => 0, 'isDel' => 0])
//            ->select('id', 'title', 'href', 'fontFamily', 'icon', 'spread', 'isCheck')
//            ->with(['children:id,fatherId,title,href,fontFamily,icon,spread'])
//            ->get();
//        return $data;
        return getRoute(1);
    }
}
