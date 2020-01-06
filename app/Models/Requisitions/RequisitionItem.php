<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use App\Models\ClaimsModels\Claim;
use App\Models\LPOModels\LpoItem;
use App\Models\MobilePaymentModels\MobilePayment;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['transaction_status', 'dates', 'transaction'];

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
        $prefix = '';
        if($this->status_id == 2){
            if($this->transaction_type == 'lpo'){
                $prefix = 'LPO';
            }
            elseif($this->transaction_type == 'lso'){
                $prefix = 'LSO';
            }
            $lpo_item = LpoItem::where('requisition_item_id', $this->id)->orderBy('id','desc')->first();
            $status = $prefix.' '.$lpo_item->lpo->status->lpo_status;
        }
        if($this->status_id == 6){
            $mb = MobilePayment::where('requisition_id', $this->requisition_id)->first();
            $status = 'Mobile Payment '.$mb->status->mobile_payment_status;
        }
        if($this->status_id == 7){
            // if($this->module == 'mobile_payment'){
            //     $mb = MobilePayment::where('requisition_id', $this->requisition_id)->first();
            //     $status = 'Mobile Payment '.$mb->status->mobile_payment_status;
            // }
            // if($this->module == 'claim'){
                $claim = Claim::where('requisition_id', $this->requisition_id)->first();
                $status = 'Claim '.$claim->status->claim_status;
            // }
        }
        return $status;
    }

    public function getTransactionAttribute(){
        $transaction = null;
        if($this->status_id == 2){
            $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
            $transaction = ['type'=>'lpo', 'id'=>$lpo_item->lpo_id];
        }
        if($this->status_id == 3){
            if($this->module == 'mobile_payment'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'mobile_payment', 'id'=>$lpo_item->lpo_id];
            }
            else if($this->module == 'claim'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'claim', 'id'=>$lpo_item->lpo_id];
            }
            else if($this->module == 'invoice'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'invoice', 'id'=>$lpo_item->lpo_id];
            }
        }

        return $transaction;
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
