<?php

namespace App\Models\AssetsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class FixedAssetStatus extends BaseModel
{
    use SoftDeletes;

    public function next_status()
    {
        return $this->belongsTo('App\Models\AssetsModels\FixedAssetStatus','next_status_id');
    }
}
