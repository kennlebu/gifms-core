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
    protected $appends = ['in_inventory', 'next_action'];
    
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

    public function getInAssetsAttribute($value) {
        return (empty($this->is_asset) || $this->is_asset == 'asset') ? $value : 1;
    }
    
    public function getInInventoryAttribute(){
        $in_inventory = true;
        if(empty($this->is_asset) || $this->is_asset == 'inventory') {
            $inventory = Inventory::where('delivery_item_id', $this->id)->exists();
            if(!$inventory){
                $in_inventory = false;
            }
        }

        return $in_inventory;
    }

    public function getNextActionAttribute(){
        $next_action = '';
        $goods = 0;
        $consumables = 0;

        $delivery = Delivery::with('lpo')->find($this->delivery_id);
        $preferred_supplier = $delivery->lpo->preferred_supplier;
        if(!empty($preferred_supplier->supply_categories)){
            foreach($preferred_supplier->supply_categories as $category){
                foreach($category->service_types as $service_type){
                    if($service_type->service_type == 'goods') $goods++;
                    if($service_type->service_type == 'consumables') $consumables++;
                }
            }            
        }

        if($goods >= 1 && $consumables >= 1) {
            $next_action = 'assinv';
        }
        elseif($consumables >= 1) {
            $next_action = 'inventory';
        }
        elseif($goods >= 1) {
            $next_action = 'assets';
        }

        return $next_action;
    }
}
