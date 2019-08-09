<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;

class Asset extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
}
