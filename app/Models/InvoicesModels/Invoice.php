<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Invoice extends BaseModel
{
    //
    use SoftDeletes;


    protected $appends = ['amount_allocated'];

    
    public function raised_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raised_by_id');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function raise_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raise_action_by_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\InvoicesModels\InvoiceStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier','supplier_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
    }
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable');
    }
    public function payments()
    {
        return $this->morphMany('App\Models\PaymentModels\Payment', 'payable');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable');
    }
    public function allocations()
    {
        return $this->morphMany('App\Models\AllocationModels\Allocation', 'allocatable');
    }



    public function getAmountAllocatedAttribute(){

        $allocations  =   $this->allocations;
        $amount_allocated   =   0;

        foreach ($allocations as $key => $value) {
            $amount_allocated   +=   (float)   $value->amount_allocated;
        }

        return $amount_allocated;



    }
}
