<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryName extends Model
{
    
    public function description()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryDescription','description_id');
    }
}
