<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use App\Models\ClaimsModels\Claim;
use App\Models\DeliveriesModels\Delivery;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo;
use App\Models\LPOModels\LpoItem;
use App\Models\MobilePaymentModels\MobilePayment;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['transaction_status', 'dates', 'transaction', 'can_continue', 'qty_unit', 'action'];

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

    public function requisition()
    {
        return $this->belongsTo('App\Models\Requisitions\Requisition','requisition_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account','account_id');
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
            $status = $prefix.' '.($lpo_item->lpo->status->lpo_status ?? 'N/A');
        }
        elseif($this->status_id == 4){
            $inv = Invoice::where('requisition_id', $this->requisition_id)->orderBy('id','desc')->first();
            $status = 'Invoice '.($inv->status->invoice_status ?? 'N/A');
        }
        elseif($this->status_id == 6){
            $mb = MobilePayment::where('requisition_id', $this->requisition_id)->orderBy('id','desc')->first();
            $status = 'Mobile Payment '.($mb->status->mobile_payment_status ?? 'N/A');
        }
        elseif($this->status_id == 3){
            // $grn = Delivery::where('requisition_id', $this->requisition_id)->orderBy('id','desc')->first();
            $status = $this->status->status;
        }
        elseif($this->status_id == 7){
            $claim = Claim::where('requisition_id', $this->requisition_id)->first();
            $status = 'Claim '.$claim->status->claim_status;
        }
        return $status;
    }

    public function getTransactionAttribute(){
        $transaction = null;
        if($this->status_id == 2){
            $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
            $transaction = ['type'=>'lpo', 'id'=>$lpo_item->lpo_id ?? 0];
        }
        if($this->status_id == 3){
            if($this->module == 'mobile_payment'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'mobile_payment', 'id'=>$lpo_item->lpo_id ?? 0];
            }
            else if($this->module == 'claim'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'claim', 'id'=>$lpo_item->lpo_id ?? 0];
            }
            else if($this->module == 'invoice'){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                $transaction = ['type'=>'invoice', 'id'=>$lpo_item->lpo_id ?? 0];
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

    public function getCanContinueAttribute(){
        if($this->status_id != 2) return false;
        $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
        $lpo = null;
        if($lpo_item)
        $lpo = Lpo::find($lpo_item->lpo_id);
        if($lpo && $lpo->status_id != 7){   // Dispatched
            return false;
        }
        return true;
    }

    public function getQtyUnitAttribute(){
        $unit = '';
        if(!empty($this->qty_description)){
            $arr = explode(' ', $this->qty_description);
            if(is_numeric($arr[0])){
                array_shift($arr);
            }
            $unit = implode(' ' ,$arr);
        }
        return $unit;
    }

    public function getActionAttribute(){
        $action = '';
        $requisition = Requisition::find($this->requisition_id);
        if($requisition && $requisition->status_id == 3){
            if($this->status_id == 1){
                $action = 'lpo';
            }
            elseif($this->status_id == 2){
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                if($lpo_item){
                    $lpo = Lpo::find($lpo_item->lpo_id);
                    if($lpo && $lpo->status_id == 7){
                        if($this->module == 'mobile_payment') $action = 'mobile_payment';
                        elseif($this->module == 'invoice' || $this->module == 'lpo'){
                            if($lpo->preferred_supplier && $lpo->preferred_supplier->supply_category && 
                                ($lpo->preferred_supplier->supply_category->service_type == 'goods')
                            ){
                                $action = 'grn';
                            }
                            else {
                                $action = 'invoice';
                            }
                        }
                    }
                }
            }
            elseif($this->status_id == 3){  // GRN Received
                $lpo_item = LpoItem::where('requisition_item_id', $this->id)->first();
                if($lpo_item){
                    $lpo = Lpo::find($lpo_item->lpo_id);
                    if($lpo && $lpo->status_id == 8 && $lpo->can_invoice){
                        $action = 'invoice';
                    }
                }
            }
        }
        return $action;
    }

}
