<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use App\SysModel\AdmUserInfo;
use App\SysModel\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    //
    function index()
    {
        return view('sys.index');
    }


    function console()
    {
        return view('sys.pages.console');
    }

    function weather()
    {
        return view('sys.pages.weather');
    }

    function welcome()
    {
        return view('sys.pages.welcome');
    }

    function userInfo()
    {
        $db = AdmUserInfo::where('admId', \Cookie::get('admId'))
            ->first();
        return view('sys.pages.member.userInfo', $db);
    }

    function userInfoUp(Request $request)
    {
        $inp = $request->all();
        $db = AdmUserInfo::where('admId', \Cookie::get('admId'))
            ->update(
                [
                    'name' => $inp['data']['name'],
                    'birthDate' => $inp['data']['birthDate'],
                    'mobile' => $inp['data']['mobile'],
                    'mail' => $inp['data']['mail']
                ]
            );
        if ($db) {
            $time = 1 * 60 * 12;//缓存时间
            \Cookie::queue('admName', $inp['data']['name'], $time);
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    function userPwd()
    {
        return view('sys.pages.member.userPwd');
    }

    function userPwdUp(Request $request)
    {
        $inp = $request->all();
        //查找用户
        $db = AdmUser::where('code', \Cookie::get('admId'))
            ->select(['password'])
            ->first();
        $is_Pwd = json_encode(Hash::check($inp['data']['oldPwd'], $db['password']));
        if ($is_Pwd == 'false') {
            return getSuccess('旧密码错误，请重新输入！');
        }
        $data = AdmUser::where('code', \Cookie::get('admId'))
            ->update(['password' => Hash::make($inp['data']['password'])]);
        if ($data) {
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    function alertSkin()
    {
        return view('sys.pages.system.alertSkin');
    }

    function menu()
    {
        //首先获取所有菜单
        $data = Menu::where(['fatherId'=>0,'isDel'=>0])
            ->select('id','title','href','fontFamily','icon','spread','isCheck')
            ->with(['children:fatherId,title,href,fontFamily,icon,spread'])
            ->get();
        //遍历数据
//        $list = [];
//        foreach ($data as $key => $value) {
//            $fatherId = $key['fatherId'];
//            //先找到顶级菜单
//            if ($value->fatherId == $fatherId) {
//                if ($value->id !== 1) {
//                    $value->children = DB::table("menu")->where('fatherId', $value->id)->get();
//                }
//                $list[] = $value;
//            }
//        }
        return $data;
    }

}
