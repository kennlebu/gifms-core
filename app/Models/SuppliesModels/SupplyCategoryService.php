<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class SupplyCategoryService extends BaseModel
{
    public $timestamps = false;
    protected $guarded = ['id'];

    public function supply_category()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplyCategory','supply_category_id');
    }
    public function supplier_service_type()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplierServiceType','supplier_service_type_id');
    }
}
