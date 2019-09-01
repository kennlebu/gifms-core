<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTransferBatch extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
    
    public function transfers()
    {
        return $this->hasMany('App\Models\Assets\AssetTransfer', 'batch_id');
    }
}
