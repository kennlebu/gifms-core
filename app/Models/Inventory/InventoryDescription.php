<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDescription extends Model
{
    use SoftDeletes;
    protected $appends = ['total'];

    public function getTotalAttribute() {
        $total = 0;
        $total = InventoryMovement::where('description_id', $this->id)->sum('quantity');
        return $total;
    }
}
