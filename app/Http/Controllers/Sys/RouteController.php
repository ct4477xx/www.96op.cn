<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\SysModel\Menu;
use App\SysModel\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $data = Menu::where(['isDel' => 0])
//            ->select('id', 'fatherId','title', 'href', 'fontFamily', 'icon', 'spread', 'isCheck','isOk')
//            //->with(['children:id,fatherId,title,href,fontFamily,icon,spread'])
//            ->get();
//        return $data;
        return view('sys.pages.routes.route', ['data' => getRoute(0)]);
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
        //获取到view返回参数,并进行判断是否已经存在一样的名称
        $inp = $request->all();
        if (getIsExist('menu', 'title', $inp['routeTitle'])) {
            return getSuccess('路由名称已存在');
        }
        $db = new Route();
        $db['title'] = $inp['routeTitle'];
        $db['bySort'] = 0;
        $db['fatherId'] = 0;
        if ($db->save()) {
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
        $data = [];
        $data['fatherId'] = $id;
        $data['isOk'] = 0;
        $data['fontFamily'] = 'layui-icon';
        //
        return view('.sys.pages.routes.routeSon', $data);
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
        $db = Route::find($id);
        return view('.sys.pages.routes.edit', $db);
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
        $db = Route::find($id);
        $db['title'] = $inp['title'];
        $db['href'] = $inp['href'];
        $db['fontFamily'] = $inp['fontFamily'];
        $db['icon'] = $inp['icon'];
        $db['bySort'] = $inp['bySort'];
        if ($db->save()) {
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
        if (!$id) {
            return getSuccess(2);
        }
        $db = Route::find($id);
        $db['isDel'] = 1;
        $db->save();
        return getSuccess(1);
    }


    public function storeSon(Request $request)
    {
        //获取到view返回参数,并进行判断是否已经存在一样的名称
        $inp = $request->all();
//        if (getIsExist('menu', 'title', $inp['title'])) {
//            return getSuccess('路由名称已存在');
//        }

        if ($inp['id'] == 0) {
            $db = new Route();
        } else {
            $db = Route::find($inp['id']);
        }
        $db['title'] = $inp['title'];
        $db['href'] = $inp['href'];
        $db['isOk'] = $inp['isOk'];
        $db['fontFamily'] = $inp['fontFamily'];
        $db['icon'] = $inp['icon'];
        $db['bySort'] = $inp['bySort'];
        $db['fatherId'] = $inp['fatherId'];
        if ($db->save()) {
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    public function routeSonEdit($id)
    {
        $db = Route::find($id);
//        return $db;
        return view('.sys.pages.routes.routeSon', $db);
    }

}
