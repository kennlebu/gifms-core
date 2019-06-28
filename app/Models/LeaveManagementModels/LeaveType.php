<?php

namespace App\Models\LeaveManagementModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class LeaveType extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
