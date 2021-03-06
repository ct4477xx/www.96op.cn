<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use App\ini\Base;
use App\ToolModel\urlMaxToMin;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function index()
    {
        //
        $data = [];
        $data['siteWebName'] = site()['siteWebName'];
        $data['doMain'] = site()['doMain'];
        return view('tool.url.index', $data);
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
        //获取前端表单信息
        $inp = $request->all();
        //
        //查找是否存在如果已经存在并且有效则返回短网址
        $old_data = urlMaxToMin::where(['oldUrl' => $inp['data']['url']])
            ->where('endTime', '>=', date("Y-m-d h:i:s"))
            ->orderBy('id', 'desc')
            ->first();

        if (!$old_data) {
            $data = new urlMaxToMin;
            $data['oldUrl'] = $inp['data']['url'];//原地址
            $data['minUrl'] = getrandstr('3');//随机码
            $data['infoBak'] = $inp['data']['infoBak'];//备注说明
            $expiration = $inp['data']['expiration'] ?: 30;
            $data['expiration'] = $expiration;//过期时间
            $data['endTime'] = date('Y-m-d', strtotime("+" . $expiration . "day"));//到期日期
            $data['ip'] = $_SERVER['REMOTE_ADDR'];//生成ip
            $res = $data->save();
            if ($res) {
                return [
                    'success' => true,
                    'oldUrl' => $data['oldUrl'],
                    'endTime' => $data['endTime'],
                    'minUrl' => $data['minUrl']

                ];
            } else {
                return [
                    'success' => false,
                    'msg' => '网址压缩失败，请重试'
                ];
            }
        } else {
            return [
                'success' => true,
                'oldUrl' => $old_data['oldUrl'],
                'endTime' => $old_data['endTime'],
                'minUrl' => $old_data['minUrl']
            ];
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
        //
        $old_data = urlMaxToMin::where(['minUrl' => $id])
            ->where('endTime', '>=', date("Y-m-d h:i:s"))
            ->orderBy('id', 'desc')
            ->first();
        if (!$old_data) {
            return redirect('t');
        } else {
            //
            $new_data = urlMaxToMin::find($old_data['id']);
            $new_data['count'] = $new_data['count'] + 1;
            $new_data['visitTime'] = date("Y-m-d h:i:s");
            $new_data->save();
            return redirect('http://' . str_replace('http://','',$old_data['oldUrl']));
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
