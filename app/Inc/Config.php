<?php

//后台动态参数配置
use http\Url;

function site()
{
    $data = [];
    $data['doMain'] = URL::previous();
    $data['siteWebName'] = '新疆峰景门店小助手管理系统';
    $data['title'] = '新疆峰景门店小助手';
    $data['siteICP'] = '粤ICP备18113405号-3';
    $data['ico'] = '/images/favicon.ico';
    $data['keywords'] = '新疆峰景门店小助手管理系统';
    $data['description'] = '新疆峰景门店小助手管理系统';
    $data['moneyRatio'] = '15';
    return $data;
}

//针对前端框架的初始值
function frame()
{
    $data = [];
    $data['limit'] = 15;//默认每页数量
    $data['limits'] = '15, 30, 45, 60, 75, 100';//默认每页显示数量
    $data['message']='1.状态在停用下才可以被删除哦~\t\r 2.如果发现没有操作按钮,请先确定是否有相应权限~';

    return $data;
}

