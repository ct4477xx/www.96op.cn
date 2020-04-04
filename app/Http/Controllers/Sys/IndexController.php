<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    function index()
    {
        return view('sys.index');
    }

    function welcome()
    {
        return view('sys.welcome');
    }


}
