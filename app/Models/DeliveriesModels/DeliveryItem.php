<?php

namespace App\Models\DeliveriesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\Inventory\Inventory;
use App\Models\SuppliesModels\Supplier;

class DeliveryItem extends BaseModel
{
    use SoftDeletes;
    protected $zppends = ['in_inventory'];
    
    public function delivery()
    {
        return $this->belongsTo('App\Models\DeliveriesModels\Delivery','delivery_id');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function asset()
    {
        return $this->belongsTo('App\Models\Assets\Asset','asset_id');
    }
    
    public function getInInventoryAttribute(){
        $in_inventory = true;
        $inventory = Inventory::where('delivery_item_id', $this->id)->exists();
        if(!$inventory){
            $in_inventory = false;
        }

        return $in_inventory;
    }
}
