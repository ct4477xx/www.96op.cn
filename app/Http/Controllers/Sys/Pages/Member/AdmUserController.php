<?php

namespace App\Http\Controllers\Sys\Pages\Member;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use App\SysModel\AdmUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdmUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.member.admUser');
    }

    public function read(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                if (!empty($inp['is_lock'])) {
                    $query->where('a.is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (!empty($inp['user_name'])) {
                    $query->where('a.user_name', $inp['user_name']);
                }
                if (!empty($inp['role_id'])) {
                    $query->where('c.role_id', $inp['role_id']);
                }
                if (!empty($inp['name'])) {
                    $query->where('b.name', 'like', '%' . $inp['name'] . '%');
                }
                if (!empty($inp['email'])) {
                    $query->where('b.email', 'like', '%' . $inp['email'] . '%');
                }
                if (!empty($inp['mobile'])) {
                    $query->where('b.mobile', 'like', '%' . $inp['mobile'] . '%');
                }
                if (!empty($inp['start_time']) && !empty($inp['end_time'])) {
                    $query->where('a.add_time', '>=', $inp['start_time'])
                        ->where('a.add_time', '<=', $inp['end_time']);
                } else if (!empty($inp['start_time'])) {
                    $query->where('a.add_time', '>=', $inp['start_time']);
                } else if (!empty($inp['end_time'])) {
                    $query->where('a.add_time', '<=', $inp['end_time']);
                }
            };
        $db = DB::table('adm_user as a')
            ->leftJoin('adm_user_info as b', 'a.code', '=', 'b.adm_code')
            ->leftJoin('adm_user_role as c', 'a.id', '=', 'c.uid')
            ->select('a.id', 'a.code', 'a.user_name', 'a.is_lock', 'b.sex', 'b.name', 'b.birth_date', 'b.mobile', 'b.email','b.money_ratio', 'a.add_code', 'a.add_time', 'a.up_code', 'a.up_time', 'c.role_id')
            ->where('a.is_del', 0)
            ->where($where)
            ->orderBy('a.is_lock','asc')
            ->orderBy('a.add_time','asc')
            ->paginate($inp['limit'])
            ->all();

        $dbData = [];
        foreach ($db as $k => $v) {
            $dbData[] = [
                'id' => $v->id,//id
                'code' => $v->code,//编号
                'user_name' => $v->user_name,//用户名
                'is_lock_name' => getIsLock($v->is_lock),//状态
                'is_lock' => $v->is_lock,//状态
                'sex' => getSex($v->sex),//性别
                'name' => $v->name,//姓名
                'birth_date' => $v->birth_date,//出生日期
                'mobile' => $v->mobile,//手机号码
                'money_ratio' => $v->money_ratio,//提成比例
                'role_name' => getRoleName($v->role_id),//权限角色
                'email' => $v->email,//邮件地址
                'add_name' => getAdmName($v->add_code),//创建者
                'add_time' => $v->add_time,//创建时间
                'up_name' => getAdmName($v->up_code),//最后修改人
                'up_time' => $v->up_time,//修改时间
            ];
        }

        //
        //总记录
        $total = DB::table('adm_user as a')
            ->leftJoin('adm_user_info as b', 'a.code', '=', 'b.adm_code')
            ->leftJoin('adm_user_role as c', 'a.id', '=', 'c.uid')
            ->select('1')
            ->where('a.is_del', 0)
            ->where($where)
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询用户成功';
        $data['data'] = $dbData;
        $data['count'] = $total;
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $db['id'] = '';
        $db['is_lock'] = '';
        $db['admUserInfo']['sex'] = 1;
        return view('.sys.pages.member.admUserEdit', ['db' => $db, 'role_id' => []]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $db = AdmUser::where('id', $id)
            ->select('id','code', 'is_lock')
            ->with('admUserInfo:adm_code,sex')
            ->get();

        $role = DB::table('adm_user_role')->where('uid', $id)->select('role_id')->get();
        $result = json_decode($role, true);
        return view('.sys.pages.member.admUserEdit', ['db' => $db[0], 'role_id' => $result[0]['role_id'] ?? []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inp = $request->all();
        //验证用户名是否存在
        if (getIsExist('adm_user', 'user_name', $inp['user_name'])) {
            return getSuccess('用户名已存在, 重新换一个吧.');
        }
        $adm = new AdmUser();
        $adm['code'] = getNewId();
        $adm['user_name'] = $inp['user_name'];
        $adm['pass_word'] = Hash::make($inp['pass_word']);
        $adm['is_lock'] = 0;
        $adm['is_del'] = 0;
        $adm['add_code'] = _admCode();
        $adm['add_time'] = getTime(1);
        if ($adm->save()) {
            //创建admInfo信息
            $info = new AdmUserInfo();
            $info['adm_code'] = $adm['code'];
            $info['name'] = $inp['name'];
            $info['sex'] = $inp['sex'] == 0 ? 0 : 1;
            $info['mobile'] = $inp['mobile'];
            $info['email'] = $inp['email'];
            $info['birth_date'] = $inp['birth_date'];
            $info['money_ratio'] = $inp['money_ratio'];
            $info['is_del'] = 0;
            $info->save();
            //保存角色
            if ($inp['role_id']) {
                DB::table('adm_user_role')->insert(['uid' => $adm['id'], 'role_id' => $inp['role_id'], 'add_time' => getTime(1)]);
            }
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $inp = $request->all();

        $adm = AdmUser::find($id);
        if ($inp['pass_word']) {
            $adm['pass_word'] = Hash::make($inp['pass_word']);
        }
        // $adm['is_lock'] = empty($inp['is_lock']) ? 1 : 0;
        $adm['up_code'] = _admCode();
        $adm['up_time'] = getTime(1);
        $adm->save();


        //在没有找到用户资料时,创建用户资料
        if (getIsExist('adm_user_info', 'adm_code',$adm['code']) == 0) {
            $info = new AdmUserInfo();
            $info['adm_code'] = $adm['code'];
            $info['name'] = $inp['name'];
            $info['sex'] = $inp['sex'] == 0 ? 0 : 1;
            $info['mobile'] = $inp['mobile'];
            $info['email'] = $inp['email'];
            $info['birth_date'] = $inp['birth_date'];
            $info->save();
        } else {
            //修改admInfo信息
            $info = AdmUserInfo::where('adm_code', $adm['code'])
                ->update([
                    'name' => $inp['name'],
                    'sex' => $inp['sex'] == 0 ? 0 : 1,
                    'mobile' => $inp['mobile'],
                    'email' => $inp['email'],
                    'birth_date' => $inp['birth_date'],
                    'money_ratio' => $inp['money_ratio'],
                ]);
        }
        //保存角色
        if ($inp['role_id']) {
            DB::table('adm_user_role')->where('uid', $adm['id'])->delete();
            DB::table('adm_user_role')->insert(['uid' => $adm['id'], 'role_id' => $inp['role_id'], 'add_time' => getTime(1)]);
        }
        return getSuccess(1);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDel('adm_user', $inp['id']);
        return getSuccess(1);
    }

    public
    function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('adm_user', $inp['id']);
        return getSuccess(1);
    }

    public
    function stop(Request $request)
    {
        $inp = $request->all();
        setLock('adm_user', $inp['id']);
        return getSuccess(1);
    }
}
