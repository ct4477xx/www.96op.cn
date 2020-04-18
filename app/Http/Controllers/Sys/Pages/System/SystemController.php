<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    //
    function alertSkin()
    {
        //配色设置
        return view('.sys.pages.system.alertSkin');
    }
}
