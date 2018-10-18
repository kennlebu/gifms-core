<?php

namespace App\Models\AssetsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class FixedAssetLocation extends BaseModel
{
    use SoftDeletes;
}
