<?php

namespace App\Models\MobilePaymentModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\PaymentModels\VoucherNumber;

class MobilePayment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['amounts','total_withdrawal_charges','totals','amount_allocated', 'bank_transaction', 'bank_transactions'];
    protected $hidden = ['brevity','county','county_id','deleted_at','invoice','invoice_id','meeting','migration_account_id',
                        'migration_deleted','migration_id','migration_invoice_id','migration_project_id','migration_project_manager_id',
                        'migration_requested_by_id','payment_document','project','project_id','region','region_id','reporting_categories_id',
                        'reporting_objective_id'];
 

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
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable');
    }
    public function payments()
    {
        return $this->morphMany('App\Models\PaymentModels\Payment', 'payable')->orderBy('created_at','asc');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('created_at', 'NOT LIKE', '%2018-04-28 22%')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc');
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
    public function voucher_number()
    {
        return $this->belongsTo('App\Models\PaymentModels\VoucherNumber', 'voucher_no');
    }
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','program_activity_id');
    }
    public function requisition()
    {
        return $this->belongsTo('App\Models\Requisitions\Requisition','requisition_id');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
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

    public function getAmountAllocatedAttribute(){

        $allocations  =   $this->allocations;
        $amount_allocated   =   0;

        foreach ($allocations as $key => $value) {
            $amount_allocated   +=   (float)   $value->amount_allocated;
        }
        return $amount_allocated;
    }

    public function getBankTransactionAttribute(){
        if(!empty($this->attributes['voucher_no'])){
            $voucher = VoucherNumber::find($this->attributes['voucher_no']);

            if(!empty($voucher->bank_transaction)){
                return $voucher->bank_transaction;
            }
            else return null;
        }
        else return null;
    }

    public function getBankTransactionsAttribute(){
        $bank_trans = [];
        if(!empty($this->attributes['voucher_no'])){
            $vouchers = VoucherNumber::where('payable_id', $this->attributes['id'])
                        ->where('payable_type', 'mobile_payments')
                        ->get();
            
            foreach($vouchers as $p){
                if(!empty($p->bank_transaction)){
                    $bank_trans[] = $p->bank_transaction;
                }
            }
        }
        return $bank_trans;
    }

    // public function getRequisitionedByAttribute(){
    //     if($this->requisition){
    //         return $this->requisition->requested_by;
    //     }
    //     return null;
    // }




}
