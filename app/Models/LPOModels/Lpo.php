<?php

namespace App\Models\LPOModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lpo extends Model
{
    //

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
