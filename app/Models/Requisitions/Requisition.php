<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['submitted_at'];
}
