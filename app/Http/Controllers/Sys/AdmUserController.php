<?php

namespace App\Http\Controllers\Sys;

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
                if (!empty($inp['status'])) {
                    $query->where('a.isLock', $inp['status'] == "n" ? 1 : 0);
                }
                if (!empty($inp['username'])) {
                    $query->where('a.username', $inp['username']);
                }
//                if (!empty($inp['role'])) {
//                    $query->where('a.role', $inp['role']);
//                }
                if (!empty($inp['name'])) {
                    $query->where('b.name', 'like', '%' . $inp['name'] . '%');
                }
                if (!empty($inp['email'])) {
                    $query->where('b.mail', 'like', '%' . $inp['email'] . '%');
                }
                if (!empty($inp['mobile'])) {
                    $query->where('b.mobile', 'like', '%' . $inp['mobile'] . '%');
                }
                if (!empty($inp['startTime']) && !empty($inp['endTime'])) {
                    $query->where('a.addTime', '>=', $inp['startTime'])
                        ->where('a.addTime', '<=', $inp['endTime']);
                } else if (!empty($inp['startTime'])) {
                    $query->where('a.addTime', '>=', $inp['startTime']);
                } else if (!empty($inp['endTime'])) {
                    $query->where('a.addTime', '<=', $inp['endTime']);
                }
            };
        $db = DB::table('adm_User as a')
            ->leftJoin('adm_userinfo as b', 'a.code', '=', 'b.admId')
            ->select('a.id', 'a.userName as username', 'a.isLock as status', 'b.sex', 'b.name', 'b.birthDate', 'b.mobile', 'b.mail', 'a.addTime', 'a.upTime')
            ->where('a.isDel', 0)
            ->where($where)
            ->paginate($inp['limit'])
            ->all();
        //
        //总记录
        $total = DB::table('adm_User as a')
            ->leftJoin('adm_userinfo as b', 'a.code', '=', 'b.admId')
            ->select('1')
            ->where('a.isDel', 0)
            ->where($where)
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询用户成功';
        $data['data'] = $db;
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
        return view('.sys.pages.member.admUserAdd');
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
        if (getIsExist('adm_user', 'userName', $inp['username'])) {
            return getSuccess('用户名已存在, 重新换一个吧.');
        }
        $adm = new AdmUser();
        $adm['code'] = getNewId();
        $adm['userName'] = $inp['username'];
        $adm['password'] = Hash::make($inp['password']);
        $adm['isLock'] = empty($inp['isLock']) ? 1 : 0;
        $adm['isDel'] = 0;
        $adm['addId'] = _admId();
        $adm['addTime'] = getTime(1);
        if ($adm->save()) {
            //创建admInfo信息
            $info = new AdmUserInfo();
            $info['admId'] = $adm['code'];
            $info['name'] = $inp['name'];
            $info['sex'] = $inp['sex'] == 0 ? 0 : 1;
            $info['mobile'] = $inp['phone'];
            $info['mail'] = $inp['email'];
            $info['birthDate'] = $inp['birthDate'];
            $info['isDel'] = 0;
            $info->save();
            //保存角色
            if ($inp['role']) {
                DB::table('adm_user_role')->insert(['uid' => $adm['id'], 'roleId' => $inp['role'], 'addTime' => getTime(1)]);
            }
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
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
            ->select('id', 'code', 'userName', 'isLock')
            ->with('admUserInfo:admId,name,sex,birthDate,mobile,mail')
            ->get();
        $role = DB::table('adm_user_role')->where('uid', $id)->select('roleId')->get();
        $result = json_decode($role, true);
        return view('.sys.pages.member.admUserEdit', ['db' => $db[0], 'role' => $result[0]['roleId']]);
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
        if ($inp['password']) {
            $adm['password'] = Hash::make($inp['password']);
        }
        $adm['isLock'] = empty($inp['isLock']) ? 1 : 0;
        $adm['upId'] = _admId();
        $adm['upTime'] = getTime(1);
        $adm->save();

        //修改admInfo信息
        $info = AdmUserInfo::where('admId', $adm['code'])
            ->update([
                'name' => $inp['name'],
                'sex' => $inp['sex'] == 0 ? 0 : 1,
                'mobile' => $inp['mobile'],
                'mail' => $inp['mail'],
                'birthDate' => $inp['birthDate'],
            ]);
        //保存角色
        if ($inp['role']) {
            DB::table('adm_user_role')->where('uid', $adm['id'])->delete();
            DB::table('adm_user_role')->insert(['uid' => $adm['id'], 'roleId' => $inp['role'], 'addTime' => getTime(1)]);
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
