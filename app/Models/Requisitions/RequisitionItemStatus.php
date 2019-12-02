<?php

namespace App\Models\Requisitions;

use Illuminate\Database\Eloquent\Model;

class RequisitionItemStatus extends Model
{
    public function service()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplierService','service_id');
    }
}
