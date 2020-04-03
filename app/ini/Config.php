<?php

function site()
{
    $data = [];
    $data['doMain'] = URL::previous();
    $data['siteWebName'] = '96OP';
    $data['siteICP'] = '粤ICP备18113405号-3';
    $data['ico'] = '/resource/images/favicon.ico';
    $data['keywords'] = 'admin v2.0,ok-admin网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载';
    $data['description'] = 'ok-admin v2.0，顾名思义，很赞的后台模版，它是一款基于Layui框架的轻量级扁平化且完全免费开源的网站后台管理系统模板，适合中小型CMS后台系统。';

    return $data;
}


function home()
{
    $data = [];
    $data['title'] = '后台管理系统';
    return $data;
}
