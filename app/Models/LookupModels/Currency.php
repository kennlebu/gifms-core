<?php

namespace App\Models\LookupModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Currency extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['base_currency','created_at','currency_sign','deleted_at','migration_id','updated_at'];
}
