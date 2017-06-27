<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\MobilePaymentModels\MobilePaymentType;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePaymentStatus;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;
use App\Models\MobilePaymentModels\MobilePaymentPayee;
use App\Models\MobilePaymentModels\MobilePaymentApproval;

class MobilePayment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['amounts','total_withdrawal_charges','totals'];

 

    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function requested_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_action_by_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }
    public function mobile_payment_type()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentType');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoicesModels\Invoice');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function region()
    {
        return $this->belongsTo('App\Models\LookupModels\Region');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function payees_upload_mode()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\PayeesUploadMode');
    }
    public function payees()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePaymentPayee');
    }
    public function mobile_payment_approvals()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePaymentApproval');
    }
    public function allocations()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePaymentProjectAccountAllocation');
    }
    public function comments()
    {
        return $this->morphMany('App\OtherModels\Comment', 'commentable');
    }




    /**
     * @deprecated 
     */
    public function getAmountsAttribute(){

        // trigger_error("This function is deprecated [getAmountsAttribute()]. Use getTotalsAttribute() instead",E_USER_NOTICE);

        $payees     =   $this->payees;
        $amounts    =   0;

        foreach ($payees as $key => $value) {
            $amounts    +=  (float) $value->amount;
        }

        return $amounts;

    }

    public function getTotalWithdrawalChargesAttribute(){

        $payees     =   $this->payees;
        $total_withdrawal_charges    =   0;

        foreach ($payees as $key => $value) {
            $total_withdrawal_charges    +=  (float) $value->withdrawal_charges;
        }

        return $total_withdrawal_charges;

    }

    public function getTotalsAttribute(){

        $payees     =   $this->payees;
        $totals    =   0;

        foreach ($payees as $key => $value) {
            $totals    +=  (float) $value->total;
        }

        return $totals;

    }




}
