<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class SupplierService extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function supply_category()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplyCategory','supply_category_id');
    }
}
