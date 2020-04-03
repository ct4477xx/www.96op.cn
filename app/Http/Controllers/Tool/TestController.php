<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use App\ToolModel\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $u = Test::paginate(5);;
        $title = '首页 ' . now();
        return view('tool.test.index', ['title' => $title, 'data' => $u]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //添加页面
        //
        return view('tool.test.create', ['title' => '首页 ' . now()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //保存数据
        $input = $request->all();

        //查找名称是否存在
        $is_name = Test::get()
            ->where('name', $input['name'])
            ->count();
        if ($is_name > 0 || $input['name'] == '') {
            return back();
        }
        $data = new Test;
        $data['name'] = $input['name'];
        $data['password'] = crypt::encrypt($input['password'] ?: "123456");
        $res = $data->save();
        if ($res) {
            return redirect('test');
        } else {
            return back();
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
        $data = [];
        $data = Test::find($id);
        $data['title'] = '正在预览 ' . $data['name'] . ' / ' . now();
        //预览
        return view('tool.test.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //进行修改
        $data = [];
        $data = Test::find($id);
        $data['title'] = '正在修改: ' . $data['name'];
        return view('tool.test.edit', $data);
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
        //更新
        $inp = $request->all();

        $is_name = Test::get()
            ->where('name', $inp['name'])
            ->count();
        if ($is_name > 1) {
            return back();
        }
        $data = Test::find($id);
        $data['name'] = $inp['name'];
        $data['password'] = Crypt::encrypt($inp['password'] ?: 123456);
        $res = $data->save();
        if ($res) {
            return redirect('test');
        } else {
            return back();
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
        $inp = Test::find($id);
        $inp->delete();
        return ['success' => 0, 'msg' => '删除成功'];
    }
}
