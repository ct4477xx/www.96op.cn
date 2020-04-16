<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmUserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.member.admUserRole');
    }

    public function read(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                if (!empty($inp['status'])) {
                    $query->where('isLock', $inp['status'] == "n" ? 1 : 0);
                }
                if (!empty($inp['name'])) {
                    $query->where('name', 'like', '%' . $inp['name'] . '%');
                }
                if (!empty($inp['startTime']) && !empty($inp['endTime'])) {
                    $query->where('addTime', '>=', $inp['startTime'])
                        ->where('addTime', '<=', $inp['endTime']);
                } else if (!empty($inp['startTime'])) {
                    $query->where('addTime', '>=', $inp['startTime']);
                } else if (!empty($inp['endTime'])) {
                    $query->where('addTime', '<=', $inp['endTime']);
                }
            };

        $db = DB::table('adm_role')
            ->select('id', 'code', 'name', 'remarks', 'isLock', 'addCode', 'addTime', 'upCode', 'upTime')
            ->where('isDel', 0)
            ->where($where)
            ->paginate($inp['limit'])
            ->all();


        $dbData = [];
        foreach ($db as $k => $v) {
            $dbData[] = [
                'id' => $v->id,
                'code' => $v->code,
                'name' => $v->name,
                'remarks' => $v->remarks,
                'isLock' => getIsLock($v->isLock),
                'addName' => getAdmName($v->addCode),
                'addTime' => $v->addTime,
                'upName' => getAdmName($v->upCode),
                'upTime' => $v->upTime,
            ];
        }

        //
        //总记录
        $total = DB::table('adm_role')
            ->select(1)
            ->where('isDel', 0)
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
        $list = [];
        $list["title"] = "根目录";
        $list["spread"] = true;
        $list["children"] = getRoute(2);

        return view('.sys.pages.member.admUserRoleAdd', ['data' => json_encode($list), 'role' => '']);
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
        //
        $db = new AdmUserRole();
        $db['code'] = getNewId();
        $db['isLock'] = 0;
        $db['isDel'] = 0;
        $db['addCode'] = _admCode();
        $db['addTime'] = getTime(1);
        $db['name'] = $inp['name'];
        $db['remarks'] = $inp['remarks'];

        if ($db->save()) {
            //往角色关联表写入数据
            $list = getRouteDataValue($inp, $db['id']);
            DB::table('adm_role_route')->insert($list);
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

        $db = AdmUserRole::find($id);
        $list = [];
        $list["title"] = "根目录";
        $list["spread"] = true;
        $list["children"] = getRoute(2);
        $role = DB::table('adm_role_route')->where('roleId', $id)->select('routeId')->get();
        $roleList = collect([]);
        foreach ($role as $k) {
            $roleList->push($k->routeId);
        }
        return view('.sys.pages.member.admUserRoleEdit', ['data' => json_encode($list), 'db' => $db, 'role' => $roleList]);
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

        //
        //删除所有当前角色相关的role数据
        DB::table('adm_role_route')->where('roleId', $id)->delete();
        //
        $db = AdmUserRole::find($id);
        $db['upCode'] = _admCode();
        $db['upTime'] = getTime(1);
        $db['name'] = $inp['name'];
        $db['remarks'] = $inp['remarks'];
        if ($db->save()) {
            //往角色关联表写入数据
            $list = getRouteDataValue($inp, $db['id']);
            DB::table('adm_role_route')->insert($list);
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        if (getIsExist('adm_user_role', 'roleId', $inp['id']) > 0) {
            return getSuccess('当前权限正在被使用中,无法进行删除操作');
        }
        setDel('adm_role', $inp['id']);
        return getSuccess(1);
    }

    public
    function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('adm_role', $inp['id']);
        return getSuccess(1);
    }

    public
    function stop(Request $request)
    {
        $inp = $request->all();
        setLock('adm_role', $inp['id']);
        return getSuccess(1);
    }
}
