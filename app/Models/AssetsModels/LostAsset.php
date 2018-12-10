<?php

namespace App\Models\AssetsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class LostAsset extends BaseModel
{
    use SoftDeletes;

    public function fixed_asset()
    {
        return $this->belongsTo('App\Models\AssetsModels\FixedAsset','fixed_asset_id');
    }
}
