<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\DeliveriesModels\Delivery;
use App\Models\InvoicesModels\Invoice;
use App\Models\Requisitions\Requisition;
use App\Models\Requisitions\RequisitionItem;
use App\Models\SuppliesModels\Supplier;

class Lpo extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $appends = ['amount','vats','sub_totals','totals','preferred_supplier','lpo_requisition_items','can_invoice','invoices_total'];
 


    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function request_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','request_action_by_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }
    public function invoices()
    {
        return $this->hasMany('App\Models\InvoicesModels\Invoice');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\LPOModels\LpoStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function requisitioned_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requisitioned_by_id');
    }
    public function cancelled_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','cancelled_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function quotations()
    {
        return $this->hasMany('App\Models\LPOModels\LpoQuotation');
    }
    public function preffered_quotation()
    {
        return $this->belongsTo('App\Models\LPOModels\LpoQuotation','preffered_quotation_id');
    }
    public function items()
    {
        return $this->hasMany('App\Models\LPOModels\LpoItem');
    }
    public function terms()
    {
        return $this->hasMany('App\Models\LPOModels\LpoTerm');
    }
    public function deliveries()
    {
        return $this->hasMany('App\Models\DeliveriesModels\Delivery');
    }    
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->orderBy('created_at', 'asc');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','program_activity_id');
    }
    public function allocations()
    {
        return $this->morphMany('App\Models\AllocationModels\Allocation', 'allocatable')->orderBy('created_at','asc');
    }
    public function requisition()
    {
        return $this->belongsTo('App\Models\Requisitions\Requisition','requisition_id');
    }
    public function getVatPercentageAttribute($value){
        if(empty($value)) return 16;
        return $value;
    }
    public function getPreferredSupplierAttribute()
    {
        $supplier = null;
        if($this->lpo_type == 'prenegotiated' || $this->lpo_type == 'lso'){
            $supplier = Supplier::with('supply_category')->find($this->supplier_id);
        }
        else {
            if($this->preffered_quotation_id)
            $supplier = Supplier::with('supply_category')->find($this->preffered_quotation->supplier_id);
        }
        return $supplier;
    }
    public function getPreferredSupplierIdAttribute()
    {
        $supplier_id = null;
        if($this->lpo_type == 'prenegotiated' || $this->lpo_type == 'lso'){
            $supplier_id = $this->supplier_id;
        }
        else {
            if($this->preffered_quotation_id)
            $supplier_id = $this->preffered_quotation->supplier_id;
        }
        return $supplier_id;
    }



    public function getAmountAttribute(){

        $items = $this->items;
        $total = 0;

        foreach ($items as $key => $value) {
            $total += $value->calculated_total;
        }
        return (float) number_format((float)$total, 2, '.', '');
    }


    public function getVatsAttribute(){
        $items = $this->items;
        $vats = 0;

        foreach ($items as $key => $value) {
            $vats += (float) $value->calculated_vat;
        }

        return $vats;
    }

    public function getSubTotalsAttribute(){
        return round($this->amount - $this->vats, 3);
    }

    public function getTotalsAttribute(){

        $items  =   $this->items;
        $totals   =   0;

        foreach ($items as $key => $value) {
            $totals   +=   (float)   $value->calculated_total;
        }

        return round($totals, 3);



    }

    public function getLpoRequisitionItemsAttribute(){
        $items = [];
        foreach($this->items as $item){
            $items[] = RequisitionItem::where('id',$item->requisition_item_id)->orderBy('id','desc')->first();
        }
        return $items;
    }

    public function getCanInvoiceAttribute(){
        $can_invoice = true;

        if($this->requisition_id){
            $requisition = Requisition::find($this->requisition_id);
            if($requisition && $requisition->requisition_id == 3){
                return true;
            }
        }

        $deliveries = Delivery::where('lpo_id', $this->id)->get();
        if(count($deliveries) < 1){
            $can_invoice = false;
        }
        else {
            foreach($deliveries as $delivery){
                if(!$delivery->in_assets){
                    $can_invoice = false;
                }
            }
        }
        
        return $can_invoice;
    }

    public function getInvoicesTotalAttribute(){
        $total = 0;
        $inovices = Invoice::where('lpo_id', $this->id)->get();
        foreach($inovices as $invoice){
            $total += (float)$invoice->total;
        }

        return $total;
    }

    public function getVatRateAttribute(){
        $rate = 16;
        foreach($this->items as $item){
            $rate = $item->vat_rate;
        }
        return $rate;
    }

    public function getNextRefNumber(){
        $number = 1;
        if(!empty($this->requisition_id)){
            $lpo = Lpo::where('requisition_id', $this->requisition_id)->whereNotNull('ref')->orderBy('created_at', 'desc')->first();
            if(!empty($lpo)){
                $arr = explode('-', $lpo->ref);
                $number = ((int) $arr[count($arr)-1] + 1);
            }
        }
        return $number;
    }


}
