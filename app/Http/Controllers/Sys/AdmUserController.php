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
                    $query->where('a.created_at', '>=', $inp['startTime'])
                        ->where('a.created_at', '<=', $inp['endTime']);
                } else if (!empty($inp['startTime'])) {
                    $query->where('a.created_at', '>=', $inp['startTime']);
                } else if (!empty($inp['endTime'])) {
                    $query->where('a.created_at', '<=', $inp['endTime']);
                }
            };
        $data = [];
//        $db = AdmUser::simplePaginate($request->get('limit'),['id','userName as username','created_at as createTime','updated_at as updateTime'])
//            ->all();
        $db = DB::table('adm_User as a')
            ->leftJoin('adm_userinfo as b', 'a.code', '=', 'b.admId')
            ->select('a.id', 'a.userName as username', 'a.isLock as status', 'b.sex', 'b.name', 'b.birthDate', 'b.mobile', 'b.mail', 'a.created_at as createTime', 'a.updated_at as updateTime')
            ->where('isDel', 0)
            ->where($where)
            ->paginate($inp['limit'])
            ->all();
        //
        //总记录
        $total = DB::table('adm_User as a')
            ->leftJoin('adm_userinfo as b', 'a.code', '=', 'b.admId')
            ->select('1')
            ->where('isDel', 0)
            ->where($where)
            ->count();
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
        if (ishas('adm_user', 'userName', $inp['username'])) {
            return getSuccess('用户名已存在, 重新换一个吧.');
        }
        $adm = new AdmUser();
        $adm['code'] = getNewId();
        $adm['userName'] = $inp['username'];
        $adm['password'] = Hash::make($inp['password']);
        $adm['isLock'] = empty($inp['isLock']) ? 1 : 0;
        if ($adm->save()) {
            //创建admInfo信息
            $info = new AdmUserInfo();
            $info['admId'] = $adm['code'];
            $info['name'] = $inp['name'];
            $info['sex'] = $inp['sex'] == 0 ? 0 : 1;
            $info['mobile'] = $inp['phone'];
            $info['mail'] = $inp['email'];
            $info['birthDate'] = $inp['birthDate'];
            $info->save();
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

        return view('.sys.pages.member.admUserEdit', $db[0]);
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
        $adm->save();

        //修改admInfo信息
        $info = AdmUserInfo::where('admId', $adm['code'])
            ->update([
                'name'=>$inp['name'],
                'sex'=>$inp['sex'] == 0 ? 0 : 1,
                'mobile'=> $inp['mobile'],
                'mail'=>$inp['mail'],
                'birthDate'=>$inp['birthDate'],
            ]);
        return getSuccess(1);
    }


        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public
        function destroy(Request $request)
        {
            $inp = $request->all();
            $db = AdmUser::where('isDel', 0)
                ->whereIn('id', getInjoin($inp['id']))
                ->update(['isDel' => 1]);
            return getSuccess(1);
            //
        }

        public
        function start(Request $request)
        {
            $inp = $request->all();
            $db = AdmUser::where('isLock', 1)
                ->whereIn('id', getInjoin($inp['id']))
                ->update(['isLock' => 0]);
            return getSuccess(1);
        }

        public
        function stop(Request $request)
        {
            $inp = $request->all();
            $db = AdmUser::where('isLock', 0)
                ->whereIn('id', getInjoin($inp['id']))
                ->update(['isLock' => 1]);
            return getSuccess(1);
        }
    }
