<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;
    protected $table = 'inventory';
    protected $appends = ['totals', 'description_text'];

    public function status()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryStatus','status_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryCategory','inventory_category_id');
    }

    public function description()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryDescription','description_id');
    }

    public function name()
    {
        return $this->belongsTo('App\Models\Inventory\InventoryName','inventory_name_id');
    }

    public function movement()
    {
        return $this->hasMany('App\Models\Inventory\InventoryMovement','inventory_id');
    }

    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo','lpo_id');
    }

    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','added_by_id');
    }

    public function getTotalsAttribute() {
        $total = 0;
        $total = InventoryMovement::where('inventory_name_id', $this->inventory_name_id)->sum('quantity');
        return $total;
    }

    public function getDescriptionTextAttribute() {
        $text = '';
        $inventory = Inventory::where('inventory_name_id', $this->inventory_name_id)->get();
        $count = 1;
        foreach($inventory as $i) {
            if($count === 1) {
                $text = $text . $i->description->description;
            }
            else {
                $text = $text. ', ' . $i->description->description;
            }
            $count++;
        }
        return $text;
    }
}
