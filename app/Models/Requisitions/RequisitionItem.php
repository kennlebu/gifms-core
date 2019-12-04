<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use App\Models\LPOModels\LpoItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['transaction_status', 'dates'];

    public function supplier_service()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplierService','service_id');
    }

    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County','county_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Requisitions\RequisitionItemStatus','status_id');
    }

    public function getTransactionStatusAttribute(){
        $status = $this->status->status;
        if($this->status_id == 2){
            $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
            $status = 'LPO '.$lpo_item->lpo->status->lpo_status;
        }
        return $status;
    }

    public function getDatesAttribute(){
        $dates = [null, null];
        if(!empty($this->start_date)){
            $dates[0] = date('D M d Y H:i:s O', strtotime($this->start_date));
        }
        if(!empty($this->end_date)){
            $dates[1]  = date('D M d Y H:i:s O', strtotime($this->end_date));
        }
        
        return $dates;
    }

}
