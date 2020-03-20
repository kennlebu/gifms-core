<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTransfer extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    public function approved_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'approved_by_id');
    }
}
