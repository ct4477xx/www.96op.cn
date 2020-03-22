<?php

function getrandstr($len)
{
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);//打乱字符串
    $rands = substr($randStr, 0, $len);//substr(string,start,length);返回字符串的一部分
    return $rands;
}


function site()
{
    $data = [];
    $data['doMain'] = 'http://www.96op.cn';
    $data['siteWebName'] = '96OP';
    $data['siteICP']='粤ICP备18113405号-3';
    return $data;
}

