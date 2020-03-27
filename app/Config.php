<?php

function site()
{
    $data = [];
    $data['doMain'] = URL::previous();
    $data['siteWebName'] = '96OP';
    $data['siteICP'] = '粤ICP备18113405号-3';
    return $data;
}
