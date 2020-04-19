<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Model\House;
use App\Model\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $db = Tenant::paginate(10);
        return view('sys.tenant', ['db' => $db]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $inp = [];
        $inp['id'] = '';
        $inp['name'] = '';
        $inp['mobile'] = '';
        $inp['idCardNo'] = '';
        $inp['address'] = '';
        $inp['house'] = '';
        $inp['houseRoom'] = '';
        $inp['remarks'] = '';
        $inp['show'] = '';
        return view('sys.tenantCreate', $inp);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //新增与修改均用此方法,使用id判断
        $inp = $request->all();
        $id = $inp['data']['id'];
        if ($id == '') {
            $data = new Tenant();
        } else {
            $data = Tenant::find($id);
        }
        $data['name'] = $inp['data']['name'];
        $data['mobile'] = $inp['data']['mobile'];
        $data['idCardNo'] = $inp['data']['idCardNo'];
        $data['address'] = $inp['data']['address'];
        $data['houseRoom'] = $inp['data']['houseRoom'];
        $data['remarks'] = $inp['data']['remarks'];
        if ($data->save()) {
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
        $db = Tenant::find($id);
        //获取楼层id
        $odb = House::where('id', $db['houseRoom'])->select(['father_id'])->first();
        $db['house'] = $odb['father_id'];
        $db['show'] = 1;
        return view('sys.tenantCreate', $db);
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
        $db = Tenant::find($id);
        //获取楼层id
        $odb = House::where('id', $db['houseRoom'])->select(['father_id'])->first();
        $db['house'] = $odb['father_id'];
        $db['show'] = '';
        return view('sys.tenantCreate', $db);
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
        $inp = Tenant::find($id);
        $inp->delete();
        return getSuccess(1);
    }
}
