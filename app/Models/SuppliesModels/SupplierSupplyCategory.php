<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;

class SupplierSupplyCategory extends Model
{
    protected $fillable = ['supplier_id', 'supply_category_id'];

    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier','supplier_id');
    }
}
