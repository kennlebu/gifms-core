<?php

namespace App\Models\LPOModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Lpo extends BaseModel
{
    //

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
