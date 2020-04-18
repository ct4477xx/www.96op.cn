<?php

namespace App\SysModel\Pages\Member;

use Illuminate\Database\Eloquent\Model;

class AdmUserRole extends Model
{
    //
    public $timestamps = false;//自定义表名（protected $table）
    protected $table = 'adm_user_role';//主键字段，默认为id
    protected $primaryKey = 'id';//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true

}
