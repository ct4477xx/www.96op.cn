<?php

namespace App\Http\Controllers;

use App\Base;
use App\signDese;
use App\signUser;
use Illuminate\Http\Request;

class CsignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('csign.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('csign.create');
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
        //查找手机号码是否存在
        $old_data = signUser::where('mobile', $inp['data']['mobile'])
            ->orderBy('addTime', 'desc')
            ->get();
        $is_data = $old_data->count();
        if (!$is_data) {
            $data = new signUser;
            $data['id'] = GetNewId();
            $data['street'] = $inp['data']['street'];
            $data['community'] = $inp['data']['community'];
            $data['homeName'] = $inp['data']['homeName'];
            $data['users'] = $inp['data']['users'];
            $data['mobile'] = $inp['data']['mobile'];
            $data['addTime'] = date('Y-m-d');
            $data['upTime'] = $inp['data']['street'];
            $data['addId'] = GetNewId();
            $res = $data->save();
            if ($res) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        } else {
            return ['success' => false, 'msg' => '手机号码已存在，请确认'];
        }

    }

    public function select(Request $request)
    {

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
        $inp = $request->all();
        $old_data = signUser::where('mobile', $inp['data']['mobile'])->get();
        $old_Id = $old_data[0]['Id'];
        $data = new signDese();
        $data['Id'] = GetNewId();
        $data['userId'] = $old_Id;
        $data['types'] = $inp['data']['types'];
        $data['status'] = $inp['data']['status'];
        $data['addId'] = GetNewId();
        $res = $data->save();
        if ($res) {
            $v = [];
            $v['street'] = signStreet_String($old_data[0]['street']);
            $v['community'] = signStreet_String($old_data[0]['community']);
            $v['homeName'] = $old_data[0]['homeName'];
            $v['users'] = $old_data[0]['users'];
            $v['mobile'] = $old_data[0]['mobile'];
            $v['success'] = true;
            return $v;
        } else {
            return ['success' => false];
        }
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
