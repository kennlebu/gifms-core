<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use App\Models\LPOModels\LpoItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['transaction_status'];

    public function supplier_service()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplierService','service_id');
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

}
