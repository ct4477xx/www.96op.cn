<?php

use App\SysModel\House;
use App\SysModel\Route;
use App\ToolModel\signStreet;


//=================================== 自定义方法类 ===================================

//输入以逗号分隔的字符串,生成带单引号的数组
function getInjoin($str)
{
    $array = explode(',', $str);
    return array_filter($array);
}

//操作返回
function getSuccess($cc)
{
    $cc ? '1' : $cc;
    $back = "";
    switch ($cc) {
        case 1:
            $back = ['code' => 0, 'success' => true, 'msg' => '操作成功!'];
            break;
        case 2:
            $back = ['code' => 1, 'success' => false, 'msg' => '操作失败!'];
            break;
        default:
            $back = ['code' => 1, 'success' => false, 'msg' => $cc];
            break;
    }
    return $back;
}

//产生随机数
function getRandstr($len)
{
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);//打乱字符串
    $rands = substr($randStr, 0, $len);//substr(string,start,length);返回字符串的一部分
    return $rands;
}


//创建唯一Id
function getNewId($type = 5, $length = 8, $time = 0)
{
    $str = $time == 0 ? '' : date('YmdHis', time());
    switch ($type) {
        case 0:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $str .= rand(0, 9);
                }
            }
            break;
        case 1:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "qwertyuioplkjhgfdsazxcvbnm";
                    $str .= $rand{mt_rand(0, 26)};
                }
            }
            break;
        case 2:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "QWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0, 26)};
                }
            }
            break;
        case 3:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0, 35)};
                }
            }
            break;
        case 4:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0, 17)};
                }
            }
            break;
        case 5:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "AB12CDabcdE34FGHefghiIJ56jklmnoKLMNOP78pqrstuQRS90TUvwxyzVWXYZ!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0, 52)};
                }
            }
            break;
    }
    return $str;
}

//获取路由的类型
function getRouteType($str)
{
    switch ($str) {
        case 0:
            return '<fount class="layui-btn layui-btn layui-btn-xs">页面</fount>';
            break;
        case 1:
            return '<fount class="layui-btn layui-btn-normal layui-btn-xs">按钮</fount>';
            break;
        default:
            break;
    }
}

//=================================== 数据库操作类 ===================================

//通过无限极获取菜单
function getMenu()
{
    $data = Route::where(['isDel' => 0, 'isOk' => 0])
        ->orderBy('fatherId', 'asc')
        ->orderBy('isOk', 'desc')
        ->orderBy('bySort', 'desc')
        ->get()
        ->toArray();
    $items = [];
    foreach ($data as $value) {
        $items[$value['id']] = $value;
    }
    $tree = [];
    foreach ($items as $k => $v) {
        if (isset($items[$v['fatherId']])) {
            $items[$v['fatherId']]['children'][] = &$items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

//通过无限极获取路由
function getRoute()
{
    $data = Route::where(['isDel' => 0])
        ->orderBy('fatherId', 'asc')
        ->orderBy('isOk', 'desc')
        ->orderBy('bySort', 'desc')
        ->get()
        ->toArray();
    $items = [];
    foreach ($data as $value) {
        $items[$value['id']] = $value;
    }
    $tree = [];
    foreach ($items as $k => $v) {
        if (isset($items[$v['fatherId']])) {
            $items[$v['fatherId']]['children'][] = &$items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

//不传参数获取楼层->获取顶级信息
function getHouse()
{
    $db = House::where(['isDel' => 0, 'fatherId' => 0])
        ->orderBy('bySort', 'desc')
        ->orderBy('isLock', 'asc')
        ->get();
    return $db;
}

//获取带楼层参数的房间号->房间信息
function getHouseRoom($fid)
{
    $db = House::where(['isDel' => 0, 'isLock' => 0, 'fatherId' => $fid])
        ->orderBy('bySort', 'desc')
        ->orderBy('isLock', 'asc')
        ->orderBy('hideBySort', 'desc')
        ->orderBy('mixBySort', 'desc')
        ->get();
    return $db;
}


//带参数获取楼层与房间号
function getHouseAndRoom($id)
{
    //根据子id获取父id,然后在获取子名称
    $db = House::where('id', $id)
        ->with(['houseFather:id,name'])
        ->first();

    return $db['houseFather']['name'] . ' / ' . $db['name'];
}

//不带参数获取街道名称->获取顶级信息
function signStreet()
{
    $db = signStreet::where('fatherId', '2')
        ->get();
    return $db;
}

//获取带父id参数的社区名称
function getStreet($fid)
{
    $db = signStreet::where('fatherId', $fid)
        ->orderBy('bySort', 'desc')
        ->get();
    return $db;
}

//获取带id参数的社区名称
function signStreet_String($Id)
{
    $Id ?: '';
    if ($Id) {
        $db = signStreet::select(['name'])
            ->where('Id', $Id)
            ->get();
        return $db[0]['name'];
    }
}


//=================================== 数据判断操作 ===================================
function getIsExist($table, $str, $val)
{
    $data = DB::table($table)
        ->where('isDel', 0)
        ->where($str, $val)
        ->count();
    return $data;
}
