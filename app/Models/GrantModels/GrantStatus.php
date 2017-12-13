<?php

namespace App\Models\GrantModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class GrantStatus extends BaseModel
{
    //
    use SoftDeletes;
}
