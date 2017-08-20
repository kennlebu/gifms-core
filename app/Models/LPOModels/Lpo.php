<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\LPOModels\LpoApproval;
use App\Models\LPOModels\LpoComment;
use App\Models\LPOModels\LpoDefaultTerm;
use App\Models\LPOModels\LpoItem;
use App\Models\LPOModels\LpoQuotation;
use App\Models\LPOModels\LpoStatus;
use App\Models\LPOModels\LpoTerm;
use App\Models\InvoicesModels\Invoice;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;
use App\Models\LookupModels\Currency;
use App\Models\SuppliesModels\Supplier;

class Lpo extends BaseModel
{
    //

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $appends = ['amount','vats','sub_totals','totals'];
 


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
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoicesModels\Invoice');
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
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable');
    }



    public function getAmountAttribute(){

        $items = $this->items;
        $total = 0;


        foreach ($items as $key => $value) {

            $unit_price     = (float)   $value->unit_price;
            $vat_charge     = (int)     $value->vat_charge;
            $qty            = (int)     $value->qty;
            $vat            = 0;
            $sub_total      = 0;

            if($vat_charge==0){
                $vat = (16/100)*$unit_price*$qty;
                $sub_total = $unit_price*$qty;
            }


            if($vat_charge==1){
                $vat = (16/100)*$unit_price*$qty;
                $sub_total = (84/100)*$unit_price*$qty;
            }

            if($vat_charge==2){
                $vat = 0;
                $sub_total = $unit_price*$qty;
            }

            $total += $sub_total+$vat;

        }
        
        return $total;

    }


    public function getVatsAttribute(){

        $items  =   $this->items;
        $vats   =   0;

        foreach ($items as $key => $value) {
            $vats   +=   (float)   $value->calculated_vat;
        }

        return $vats;

    }

    public function getSubTotalsAttribute(){

        $items  =   $this->items;
        $sub_totals   =   0;

        foreach ($items as $key => $value) {
            $sub_totals   +=   (float)   $value->calculated_sub_total;
        }

        return $sub_totals;



    }

    public function getTotalsAttribute(){

        $items  =   $this->items;
        $totals   =   0;

        foreach ($items as $key => $value) {
            $totals   +=   (float)   $value->calculated_total;
        }

        return $totals;



    }


}
