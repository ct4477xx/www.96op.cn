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

    function house()
    {
        return view('sys.house');
    }

    function console()
    {
        return view('sys.pages.console');
    }

    function weather()
    {
        return view('sys.pages.weather');
    }

}
