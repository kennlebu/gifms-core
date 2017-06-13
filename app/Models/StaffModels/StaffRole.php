<?php

namespace App\Models\StaffModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class StaffRole extends BaseModel
{
    //
    use SoftDeletes;
}
