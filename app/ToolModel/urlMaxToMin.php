<?php

namespace App\ToolModel;

use Illuminate\Database\Eloquent\Model;

class urlMaxToMin extends Model
{
    //
    protected $table = 'urlMaxToMin';
    const CREATED_AT = 'addTime';
    const UPDATED_AT = 'visitTime';
}
