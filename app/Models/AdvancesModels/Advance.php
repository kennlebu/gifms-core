<?php

namespace App\Models\AdvancesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\AdvancesModels\AdvanceApproval;
use App\Models\AdvancesModels\AdvanceStatus;
use App\Models\LookupModels\Currency;

class Advance extends BaseModel
{
    
    use SoftDeletes;

    
    protected $appends = ['amount_allocated'];
    
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
    public function status()
    {
        return $this->belongsTo('App\Models\AdvancesModels\AdvanceStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
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
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function vouchers()
    {
        return $this->morphMany('App\Models\PaymentModels\PaymentVoucher', 'vouchable')->orderBy('created_at','asc');
    }    
    public function getAmountAllocatedAttribute(){

        $allocations  =   $this->allocations;
        $amount_allocated   =   0;

        foreach ($allocations as $key => $value) {
            $amount_allocated   +=   (float)   $value->amount_allocated;
        }

        return $amount_allocated;



    }
//
}
