<?php

namespace App\ToolModel;

use Illuminate\Database\Eloquent\Model;

class signUser extends Model
{
    //
    protected $table = 'signUser';
    const CREATED_AT = 'addTime';
    const UPDATED_AT = 'upTime';
}
