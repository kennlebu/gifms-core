<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetStatus extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
}
