<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Model\House;
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
        $data = House::where('is_del', 0)
            ->orderBy('by_sort', 'desc')
            ->orderBy('is_lock', 'asc')
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
        $data['father_id'] = 0;
        $data['hideBySort'] = 0;//稍后更新为自己的id
        $data['name'] = $inp['cate_name'];
        $data['by_sort'] = $inp['by_sort'];
        $data['mixBySort'] = 1000;
        $data['is_lock'] = 0;
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
        $by_sort = $inp['data']['by_sort'];
        //
        $data = House::find($id);
        $data['name'] = $inp['data']['name'];
        $data['by_sort'] = $by_sort;
        $data['is_lock'] = $inp['data']['is_lock'] ? 1 : 0;
        if ($data->save()) {
            //父级更新成功后将子级进行更新排序
            $up = House::where('hideBySort', $id);
            $up->update(['by_sort' => $by_sort, 'is_lock' => $inp['data']['is_lock'] ? 1 : 0]);
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
        $db = House::where(['is_del' => 0, 'father_id' => $id])
            ->get()
            ->count();
        //return $db;
        if ($db == 0) {
            $data = House::find($id);
            $data['is_del'] = 1;
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
        $data['by_sort'] = $db['mixBySort'];
        $data['is_lock'] = $db['is_lock'];
        //return $data;
        return view('sys/houseRoom', $data);
    }

    public function houseRoomStore(Request $request)
    {
        $inp = $request->all();
        //
        if ($inp['data']['sid'] == 1) {

            //获取到主排序
            $db = House::find($inp['data']['father_id']);
            //
            $data = new House;
            $data['father_id'] = $inp['data']['father_id'];
            $data['hideBySort'] = $inp['data']['father_id'];
            $data['by_sort'] = $db['by_sort'];
            $data['name'] = $inp['data']['name'];
            $data['is_lock'] = $inp['data']['is_lock'] ? 1 : 0;
            $data->save();
            return getSuccess(1);
        } else {
            $db = House::find($inp['data']['id']);
            //如果父级被锁定,则不允许解锁与修改子级
            $fatherDb = House::find($db['id']);
            if ($fatherDb['is_lock'] == 1) {
                return getSuccess('当楼层已被锁定的状态下, 不允许修改房间信息');
            } else {
                $db['mixBySort'] = $inp['data']['by_sort'];
                $db['name'] = $inp['data']['name'];
                $db['is_lock'] = $inp['data']['is_lock'] ? 1 : 0;
                $db->save();
                return getSuccess(1);
            }
        }
    }
}
