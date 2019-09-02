<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetLoss extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
    protected static $logOnlyDirty = true;

    public function asset()
    {
        return $this->belongsTo('App\Models\Assets\Asset', 'asset_id');
    }

    public function insurer()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier', 'insurer_id');
    }

    public function submitted_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'submitted_by_id');
    }
    // public function logs()
    // {
    //     return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    // }
}
