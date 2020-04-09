<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\AdmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $query->where('a.isLock',  $inp['status']=="n"?1:0);
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
            ->where($where)
            ->paginate($inp['limit'])
            ->all();
        //
        //总记录
        $total = DB::table('adm_User as a')
            ->leftJoin('adm_userinfo as b', 'a.code', '=', 'b.admId')
            ->select('1')
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}