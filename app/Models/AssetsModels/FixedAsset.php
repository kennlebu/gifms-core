<?php

namespace App\Models\AssetsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class FixedAsset extends BaseModel
{
    use SoftDeletes;

    public function status()
    {
        return $this->belongsTo('App\Models\AssetsModels\FixedAssetStatus','status_id');
    }
    public function location()
    {
        return $this->belongsTo('App\Models\AssetsModels\FixedAssetLocation','asset_location_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\AssetsModels\FixedAssetCategory','asset_category_id');
    }
    public function assigned_to()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','assigned_to_id');
    }
    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','added_by_id');
    }public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
}
