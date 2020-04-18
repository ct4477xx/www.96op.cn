<?php

namespace App\Http\Controllers\Sys\Pages;

use App\Http\Controllers\Controller;
use App\SysModel\Pages\Member\AdmUser;
use App\SysModel\Pages\Member\AdmUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    //
    //控制台
    function console()
    {
        return view('.sys.pages.console');
    }

    //天气预报
    function weather()
    {
        return view('.sys.pages.weather');
    }

    //个人信息页面
    function userInfo()
    {
        //个人用户信息
        //在没有找到用户资料时,创建用户资料
        if (getIsExist('adm_user_info', 'adm_code', _admCode()) == 0) {
            $info = new AdmUserInfo();
            $info['adm_code'] = _admCode();
            $info['name'] = _admName();
            $info->save();
        }
        $db = AdmUserInfo::where('adm_code', _admCode())
            ->first();
        return view('.sys.pages.member.userInfo', $db);
    }

    //个人信息更新
    function userInfoUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        $info = $db = AdmUserInfo::where('adm_code', _admCode())
            ->update(
                [
                    'name' => $inp['data']['name'],
                    'sex' => $inp['data']['sex'] == 'false' ? 0 : 1,
                    'birth_date' => $inp['data']['birth_date'],
                    'mobile' => $inp['data']['mobile'],
                    'email' => $inp['data']['email']
                ]
            );

        $bool = AdmUser::where('code', _admCode())->update(['up_code' => _admCode(), 'up_time' => getTime(1)]);
        if ($info || $bool) {
            $time = 1 * 60 * 12;//缓存时间
            \Cookie::queue('admName', $inp['data']['name'], $time);
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //个人密码页面
    function userPwd()
    {
        //个人密码
        return view('.sys.pages.member.userPwd');
    }

    //个人密码更新
    function userPwdUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        //查找用户
        $db = AdmUser::where('code', _admCode())
            ->select(['pass_word'])
            ->first();
        $is_pwd = json_encode(Hash::check($inp['data']['old_pwd'], $db['pass_word']));
        if ($is_pwd == 'false') {
            return getSuccess('旧密码错误，请重新输入！');
        }
        $data = AdmUser::where('code', _admCode())
            ->update(['pass_word' => Hash::make($inp['data']['pass_word'])]);
        if ($data) {
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //菜单
    function menu()
    {
        //无限极菜单
//        $data = Menu::where(['father_id' => 0, 'is_del' => 0])
//            ->select('id', 'title', 'href', 'fontFamily', 'icon', 'spread', 'isCheck')
//            ->with(['children:id,father_id,title,href,fontFamily,icon,spread'])
//            ->get();
//        return $data;
        return getRoute(1);
    }

}
