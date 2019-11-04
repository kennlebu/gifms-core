<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;

    public function service()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplierService','service_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Requisitions\RequisitionItemStatus','status_id');
    }
}
