<?php

namespace App\ToolModel;

use Illuminate\Database\Eloquent\Model;

class signUser extends Model
{
    //
    protected $table = 'signUser';
    const CREATED_AT = 'add_time';
    const UPDATED_AT = 'up_time';
}
