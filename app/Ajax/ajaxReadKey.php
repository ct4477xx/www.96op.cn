<?php

use App\ToolModel\signStreet;

function getReadKeyCommunity($fatherId, $Id)
{
    $fatherId ?: 0;
    $Id ?: '';
    $db = getStreet($fatherId);
    echo "<option value=''>请选择社区</option>";
    foreach ($db as $v) {
        echo "<option value=" . $v['Id'] . ">" . $v['name'] . "</option>";
    }
    return '';
}


function getReadKeyHouseRoom($fatherId, $Id)
{
    $fatherId ?: 0;
    $Id ?: '';
    $db = getHouseRoom($fatherId);
    echo "<option value=''>请选择房间</option>";
    foreach ($db as $v) {
        echo "<option value=" . $v['id'] . ">" . $v['name'] . "</option>";
    }
    return '';
}
