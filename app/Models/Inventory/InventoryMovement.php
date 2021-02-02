<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMovement extends Model
{
    use SoftDeletes;
    protected $appends = ['absolute_qty'];

    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory\Inventory','inventory_id');
    }

    public function description()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryDescription','description_id');
    }

    public function name()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryName','inventory_name_id');
    }

    public function initiated_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','initiated_by_id');
    }

    public function staff_to() {
        return $this->belongsTo('App\Models\StaffModels\Staff','to');
    }

    public function getAbsoluteQtyAttribute() {
        $val = 0;
        if($this->quantity != 0) {
            $val = abs($this->quantity);
        }
        return $val;
    }
}
