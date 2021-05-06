<?php

namespace App\Models\DeliveriesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\Inventory\Inventory;
use App\Models\SuppliesModels\Supplier;

class Delivery extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['in_assets', 'in_inventory'];
    
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function received_for()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_for_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo')->with('supplier');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function items()
    {
        return $this->hasMany('App\Models\DeliveriesModels\DeliveryItem', 'delivery_id');
    }
    public function requisition()
    {
        return $this->belongsTo('App\Models\Requisitions\Requisition')->with('items');
    }

    public function getInAssetsAttribute(){
        $in_assets = true;
        foreach($this->items as $item){
            if((empty($item->is_asset) || $item->is_asset == 'asset') && !$item->in_assets){
                $in_assets = false;
            }
        }
        return $in_assets;
    }

    public function getNextRefNumber(){
        $number = 1;
        if(!empty($this->requisition_id)){
            $delivery = Delivery::where('requisition_id', $this->requisition_id)->whereNotNull('ref')->orderBy('created_at', 'desc')->first();
            if(!empty($delivery)){
                $arr = explode('-', $delivery->ref);
                $number = ((int) $arr[count($arr)-1] + 1);
            }                      
        }
        return $number;
    }

    public function getInInventoryAttribute(){
        $in_inventory = true;
        foreach($this->items as $item){
            if(empty($item->is_asset) || $item->is_asset == 'inventory') {
                $inventory = Inventory::where('delivery_item_id', $item->id)->exists();
                if(!$inventory){
                    $in_inventory = false;
                }
            }
        }

        return $in_inventory;
    }
}
