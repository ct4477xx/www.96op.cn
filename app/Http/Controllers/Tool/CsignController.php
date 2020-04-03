<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use App\ini\Base;
use App\ToolModel\signDese;
use App\ToolModel\signUser;
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
        return view('tool.csign.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tool.csign.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inp = $request->all();
        //
        //查找手机号码是否存在
        $old_data = signUser::where('mobile', $inp['data']['mobile'])
            ->orderBy('addTime', 'desc')
            ->first();
        if (!$old_data) {
            $data = new signUser;
            $data['id'] = getNewId();
            $data['street'] = $inp['data']['street'];
            $data['community'] = $inp['data']['community'];
            $data['homeName'] = $inp['data']['homeName'];
            $data['users'] = $inp['data']['users'];
            $data['mobile'] = $inp['data']['mobile'];
            $data['addTime'] = date('Y-m-d');
            $data['upTime'] = $inp['data']['street'];
            $data['addId'] = getNewId();
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
        //
        $old_data = signUser::where('mobile', $inp['data']['mobile'])
            ->orderBy('addTime', 'desc')
            ->first();
        $old_Id = $old_data['Id'];
        if ($old_Id) {
            $data = new signDese();
            $data['Id'] = getNewId();
            $data['userId'] = $old_Id;
            $data['types'] = $inp['data']['types'];
            $data['status'] = $inp['data']['status'];
            $data['addId'] = getNewId();
            $res = $data->save();
            if ($res) {
                $v = [];
                $v['street'] = signStreet_String($old_data['street']);
                $v['community'] = signStreet_String($old_data['community']);
                $v['homeName'] = $old_data['homeName'];
                $v['users'] = $old_data['users'];
                $v['mobile'] = $old_data['mobile'];
                $v['success'] = true;
                return $v;
            } else {
                return ['success' => false];
            }
        }else{
            return ['success'=>false,'msg'=>'用户不存在'];
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
