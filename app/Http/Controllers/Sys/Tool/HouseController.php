<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = House::where('isDel', 0)
            ->orderBy('bySort', 'desc')
            ->orderBy('isLock', 'asc')
            ->orderBy('hideBySort', 'desc')
            ->orderBy('mixBySort', 'desc')
            ->get();
        return view('sys.pages.house.house', ['data' => $data]);
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
        $inp = $request->all();
        if (!$inp['cate_name']) {
            return redirect('sys/house');
        }
        $data = new House;
        $data['fatherId'] = 0;
        $data['hideBySort'] = 0;//稍后更新为自己的id
        $data['name'] = $inp['cate_name'];
        $data['bySort'] = $inp['bySort'];
        $data['mixBySort'] = 1000;
        $data['isLock'] = 0;
        $data->save();
        //
        $up = House::find($data['id']);
        $up['hideBySort'] = $data['id'];
        $up->save();
        return redirect('sys/house');
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
        $db = House::find($id);
        $db['fatherName'] = '';
        // return $db;
        return view('sys/houseRoom', $db);
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
        $data = House::find($id);
        return view('sys/houseEdit', $data);
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
        $bySort = $inp['data']['bySort'];
        //
        $data = House::find($id);
        $data['name'] = $inp['data']['name'];
        $data['bySort'] = $bySort;
        $data['isLock'] = $inp['data']['isLock'] ? 1 : 0;
        if ($data->save()) {
            //父级更新成功后将子级进行更新排序
            $up = House::where('hideBySort', $id);
            $up->update(['bySort' => $bySort, 'isLock' => $inp['data']['isLock'] ? 1 : 0]);
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
        //
        //删除前先检查是否存在下级,如果存在则删除失败
        $db = House::where(['isDel' => 0, 'fatherId' => $id])
            ->get()
            ->count();
        //return $db;
        if ($db == 0) {
            $data = House::find($id);
            $data['isDel'] = 1;
            $res = $data->save();
            if ($res) {
                return getSuccess(1);
            } else {
                return getSuccess(2);
            }
        }
        return getSuccess('当存在下级时删除失败');
    }


    public function houseRoomEdit($id)
    {
        $db = House::where('id', $id)
            ->with(['houseFather:id,name'])
            ->first();
        $data = [];
        $data['id'] = $db['id'];
        $data['fatherName'] = $db['houseFather']['name'];
        $data['name'] = $db['name'];
        $data['bySort'] = $db['mixBySort'];
        $data['isLock'] = $db['isLock'];
        //return $data;
        return view('sys/houseRoom', $data);
    }

    public function houseRoomStore(Request $request)
    {
        $inp = $request->all();
        //
        if ($inp['data']['sid'] == 1) {

            //获取到主排序
            $db = House::find($inp['data']['fatherId']);
            //
            $data = new House;
            $data['fatherId'] = $inp['data']['fatherId'];
            $data['hideBySort'] = $inp['data']['fatherId'];
            $data['bySort'] = $db['bySort'];
            $data['name'] = $inp['data']['name'];
            $data['isLock'] = $inp['data']['isLock'] ? 1 : 0;
            $data->save();
            return getSuccess(1);
        } else {
            $db = House::find($inp['data']['id']);
            //如果父级被锁定,则不允许解锁与修改子级
            $fatherDb = House::find($db['id']);
            if ($fatherDb['isLock'] == 1) {
                return getSuccess('当楼层已被锁定的状态下, 不允许修改房间信息');
            } else {
                $db['mixBySort'] = $inp['data']['bySort'];
                $db['name'] = $inp['data']['name'];
                $db['isLock'] = $inp['data']['isLock'] ? 1 : 0;
                $db->save();
                return getSuccess(1);
            }
        }
    }
}
