<?php

use App\UserModel\signStreet;

function GetCommunity($fatherId, $Id)
{
    $fatherId ?: 0;
    $Id ?: '';
    $data = signStreet::where('fatherId', $fatherId)
        ->orderBy('bySort', 'desc')
        ->get();

    $res = "<option value=''>请选择社区</option>";
    foreach ($data as $v) {
        echo "<option value=" . $v['Id'] . ">" . $v['name'] . "</option>";
    }
    return $res;
}
