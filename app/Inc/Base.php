<?php

use App\Model\Pages\Admin\AdmRole;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\Routes\Route;
use App\ToolModel\signStreet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


//=================================== 底层方法类 ===================================
function base()
{
    $data = [];
    $data['moneyRatio'] = '15';//销售提成 默认值
    $data ['cacheTime'] = 1 * 60 * 12; //系统中所有与缓存相关时间
    return $data;
}

//=================================== 自定义方法类 ===================================
function getTime($str)
{
    switch ($str) {
        case 1:
            $back = new DateTime('now');

            break;
        case 2:
            $back = date('Y/m/d');
            break;
        default:
            $back = time();
            break;
    }
    return $back;
}

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


function objectToArray($object)
{
    //先编码成json字符串，再解码成数组
    return json_decode(json_encode($object), true);
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
        case 4:
            return '<fount class="layui-btn layui-btn-normal layui-btn-xs">按钮</fount>';
            break;
        case 8:
            return '<fount class="layui-btn layui-btn-warm layui-btn-xs">数据</fount>';
            break;
        default:
            break;
    }
}


//性别   0女 1男
function getSex($str)
{
    if ($str == 1) {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">男</span>';
    } else {
        return '<span class="layui-btn layui-btn-warm layui-btn-xs">女</span>';
    }
}


//状态   0正常 1锁定
function getIsLock($str)
{
    if ($str == 0) {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">启用</span>';
    } else {
        return '<span class="layui-btn layui-btn-disabled layui-btn-xs">停用</span>';
    }
}


//=================================== 数据库操作类 ===================================
function getAdmName($code)
{
    $db = AdmUserInfo::where('adm_code', $code)->select('name')->first();
    return $db['name'];
}


//获取角色权限名称
function getRoleName($id)
{
    $db = AdmRole::where('id', $id)->select('title')->first();
    if ($db) {
        return '<span class="layui-btn layui-btn-primary layui-btn-xs">' . $db['title'] . '</span>';
    }
}

//获取用户所设置的角色
function getUserRole($v)
{
    if ($v == 0) {
        $where = [];
    } else {
        $where = ['is_lock' => 0];
    }
    $db = AdmRole::where(['is_del' => 0])
        ->where($where)
        ->select('id', 'code', 'title', 'remarks')
        ->get();
    return $db;
}

//通过无限极获取数据
function getRouteData($s)
{
    $s ?? 0;
    if ($s == 0) {//获取所有未被删除的路由
        $select = '*';
        $where = [];
    }
    if ($s == 1) {//获取所有未被删除且为页面的路由 用于作为菜单使用
        $select = '*';
        $where = ['is_type' => 0];
    }
    if ($s == 2) {//获取所有路由树(页面+数据+按钮)
        $select = getInjoin('id,father_id,title,spread');
        $where = [];
    }
    if ($s == 3) {//获取所有路由树(页面+数据+按钮) -> 用于权限使用,所有仅需要id和父id
        $select = getInjoin('id,father_id');
        $where = [];
    }
    $data = Route::where(['is_del' => 0])
        ->where($where)
        ->select($select)
        ->orderBy('father_id', 'asc')
        ->orderBy('is_type', 'desc')
        ->orderBy('by_sort', 'desc')
        ->get()
        ->toArray();
    return $data;
}

//getRouteData 的关联方法,用于生成树
function getRouteTree($data)
{
    $items = [];
    foreach ($data as $value) {
        $items[$value['id']] = $value;
    }
    $tree = [];
    foreach ($items as $k => $v) {
        if (isset($items[$v['father_id']])) {
            $items[$v['father_id']]['children'][] = &$items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

//获取带有权限控制的左侧导航
function getMenu()
{
    _admPower();
    $routeIds = objectToArray(Cache::get('routeIds-' . _admCode()));
    $data = Route::where(['is_del' => 0])
        ->where(['is_type' => 0])
        ->whereIn('id', $routeIds)
        ->orderBy('father_id', 'asc')
        ->orderBy('is_type', 'desc')
        ->orderBy('by_sort', 'desc')
        ->get()
        ->toArray();
    return getRouteTree($data);;
}

//获取路由管理页面当中的数据
function getRouteMabage()
{
    return getRouteTree(getRouteData(0));;
}

//读取所有数据与按钮的路由id,用于角色权限保存时,仅保存有数据与按钮的id  仅提供给getRouteDataValue方法使用
function getRouteTypeData()
{
    $data = Route::where(['is_del' => 0])
        ->wherein('is_type', ['4', '8'])
        ->select('id')
        ->get();

    foreach ($data as $k => $v) {
        $list[] = $v['id'];
    }
    return $list;
}

//在写入角色表时,仅保存类型为 数据和按钮的数据
function getRouteDataValue($str, $val)
{
//往角色关联表写入数据
    $list = [];
    foreach ($str as $k => $v) {
        if (strpos($k, 'layuiTreeCheck_') !== false) {
            if ($v > 0) {
                if (in_array($v, getRouteTypeData())) {
                    $list[] = array('role_id' => $val, 'route_id' => $v, 'add_time' => getTime(1));
                }
            }
        }
    }
    return $list;
}

//=================================== 数据判断操作 ===================================
function getIsExist($table, $str, $val)
{
    $data = DB::table($table)
        ->where('is_del', 0)
        ->where($str, $val)
        ->count();
    return $data;
}


//=================================== 通用操作方法类 ===================================
//伪删除
function setDel($table, $val)
{
    $data = DB::table($table)
        ->where('is_del', 0)
        ->whereIn('id', getInjoin($val))
        ->update(['is_del' => 1, 'del_code' => _admCode(), 'del_time' => getTime(1)]);
    return $data;
}

//锁定
function setLock($table, $val)
{
    $data = DB::table($table)
        ->where('is_lock', 0)
        ->whereIn('id', getInjoin($val))
        ->update(['is_lock' => 1, 'up_code' => _admCode(), 'up_time' => getTime(1)]);
    return $data;
}

//解锁
function setNoLock($table, $val)
{
    $data = DB::table($table)
        ->where('is_lock', 1)
        ->whereIn('id', getInjoin($val))
        ->update(['is_lock' => 0, 'up_code' => _admCode(), 'up_time' => getTime(1)]);
    return $data;
}


//=================================== 自定义用户私有方法类 ===================================
//获取当前用户Id
function _admId()
{
    return \Cookie::get('admId');
}

//获取当前用户Id
function _admCode()
{
    return \Cookie::get('admCode');
}

//获取当前用户名称
function _admName()
{
    return \Cookie::get('admName');
}

//获取当前私有账号所拥有的路由权限id
function _admPower()
{
    if (Cache::has('routeIds-' . _admCode())) {
        return Cache::get('routeIds-' . _admCode());
    } else {
        $roles = AdmUser::find(_admId());
        $roles = $roles->admUserRole;
        $arr = [];
        //获取到当前用户拥有的按钮权限id
        foreach ($roles as $v) {
            $routes = $v->route;
            foreach ($routes as $route) {
                $arr[] = $route->id;
            }
        }
        //获取到所有路由的id与父id 用于与当前用户id进行比较和遍历
        $routeAll = getRouteData(3);
        //1.首先遍历系统中所有的路由id
        foreach ($routeAll as $k => $v) {
            //2.从所有权限中遍历 是否存在当前的用户权限, 如果存在则记录当前权限id,并且继续找父级
            if (in_array($v['id'], $arr)) {
                $arr[] = $v['id'];
                if ($v['father_id'] > 0) {
                    //3.循环遍历
                    $arr[] = father($v['father_id'], $routeAll);
                }
            }
        }
        //将所有递归的数组合并后进行去重
        $arr = array_unique(mergeArr($arr));
        Cache::put('routeIds-' . _admCode(), $arr);
        return $arr;
    }
}


//与_admPower配合使用, 用于找到当前权限的所有父亲与顶级;
function father($f_id, $routeAll)
{
    //获取到传过来的父id后,与所有id进行匹配
    $list = [];
    foreach ($routeAll as $k => $v) {
        //2.从所有权限中遍历 是否存在当前的用户权限, 如果存在则记录当前权限id,并且继续找父级
        if (in_array($v['id'], [$f_id])) {
            $list[] = $v['id'];
            if ($v['father_id'] > 0) {
                //3.循环遍历
                $list[] = father($v['father_id'], $routeAll);
            }
        }
    }
    return $list;
}

//将某个带有层级的数组合并为一个数组集合
function mergeArr($arr)
{
    $result = array();
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $result = array_merge($result, mergeArr($val));
        } else {
            $result[] = $val;
        }
    }
    return $result;
}

//授权控制,通过数组来查找
function hasPower($routeId)
{
    //在用户权限组如果找到了对应权限 则返回真,否则返回假
    return in_array($routeId, _admPower()) ? true : false;
}

//重新生成登录账号相关的cache
function _admCache($admCode)
{
    Cache::forget('routeIds-' . $admCode);//权限值
}


//=================================== 其他方法类 ===================================
//不传参数获取楼层->获取顶级信息
function getHouse()
{
    $db = House::where(['is_del' => 0, 'father_id' => 0])
        ->orderBy('by_sort', 'desc')
        ->orderBy('is_lock', 'asc')
        ->get();
    return $db;
}

//获取带楼层参数的房间号->房间信息
function getHouseRoom($fid)
{
    $db = House::where(['is_del' => 0, 'is_lock' => 0, 'father_id' => $fid])
        ->orderBy('by_sort', 'desc')
        ->orderBy('is_lock', 'asc')
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
    $db = signStreet::where('father_id', '2')
        ->get();
    return $db;
}

//获取带父id参数的社区名称
function getStreet($fid)
{
    $db = signStreet::where('father_id', $fid)
        ->orderBy('by_sort', 'desc')
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
