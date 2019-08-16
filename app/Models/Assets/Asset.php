<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    public function type()
    {
        return $this->belongsTo('App\Models\Assets\AssetType', 'type_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Assets\AssetStatus', 'status_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier', 'supplier_id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Assets\AssetClass', 'class_id');
    }

    public function insurance_type()
    {
        return $this->belongsTo('App\Models\Assets\AssetInsuranceType', 'insurance_type_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Assets\AssetLocation', 'location_id');
    }

    public function assigned_to()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'assigned_to_id');
    }

    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'added_by_id');
    }

    public function last_updater()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'last_updated_by');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Assets\AssetGroup', 'asset_group_id');
    }
}
