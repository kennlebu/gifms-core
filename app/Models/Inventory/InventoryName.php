<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryName extends Model
{
    protected $appends = ['deliveries'];
    
    public function descriptions()
    {
        return $this->hasMany('App\Models\Inventory\InventoryDescription','inventory_name_id');
    }

    public function getDeliveriesAttribute()
    {
        $deliveries = [];
        $inventory = Inventory::where('id', $this->inventory_id)->get();
        foreach ($inventory as $i) {
            if(!empty($i->delivery)){
                $deliveries[] = $i->delivery;
            }
        }

        return $deliveries;
    }
}
