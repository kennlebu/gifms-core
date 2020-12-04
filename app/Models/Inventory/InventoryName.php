<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryName extends Model
{
    
    public function descriptions()
    {
        return $this->hasMany('App\Models\Inventory\InventoryDescription','inventory_name_id');
    }
}
