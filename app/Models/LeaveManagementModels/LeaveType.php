<?php

namespace App\Models\LeaveManagementModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class LeaveType extends BaseModel
{
    //
    use SoftDeletes;
}
