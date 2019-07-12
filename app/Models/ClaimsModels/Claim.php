<?php

namespace App\Models\ClaimsModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\PaymentModels\Payment;
use DB;

class Claim extends BaseModel
{
    
    use SoftDeletes;
    
    protected $appends = ['amount_allocated', 'bank_transaction', 'bank_transactions','calculated_withdrawal_charges','calculated_total'];
    protected $hidden = ['reporting_categories_id','reporting_objective_id','migration_requested_by_id','migration_project_id',
                        'migration_project_manager_id','migration_id','deleted_at '];
    
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
        return $this->belongsTo('App\Models\ClaimsModels\ClaimStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentMode','payment_mode_id');
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
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('created_at', 'NOT LIKE', '%2018-04-28 22%')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc');
    }
    public function allocations()
    {
        return $this->morphMany('App\Models\AllocationModels\Allocation', 'allocatable');
    }
    public function vouchers()
    {
        return $this->morphMany('App\Models\PaymentModels\PaymentVoucher', 'vouchable')->orderBy('created_at','asc');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
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
        $payment = Payment::where('payable_id', $this->attributes['id'])
                    ->where('payable_type', 'claims')
                    ->first();
                
        if(!empty($payment->bank_transaction)){
            return $payment->bank_transaction;
        }
        else return null;
    }

    public function getBankTransactionsAttribute(){
        $payment = Payment::with('paid_to_bank', 'paid_to_bank_branch')->where('payable_id', $this->attributes['id'])
                    ->where('payable_type', 'claims')
                    ->get();
                
        $bank_trans = [];
        foreach($payment as $p){
            if(!empty($p->bank_transaction)){
                $bank_trans[] = $p->bank_transaction;
            }
        }
        return $bank_trans;
    }

    public function getCalculatedWithdrawalChargesAttribute(){

    	$amount = (double) $this->attributes['total'];
        $withdrawal_charges = 0 ;

        if(!empty($this->payment_mode_id) && $this->payment_mode_id == 2){
            $tariff_res = DB::table('mobile_payment_tariffs')
                        ->select(DB::raw('tariff'))
                        ->where('min_limit', '<=', $amount)
                        ->where('max_limit', '>=', $amount)
                        ->get();

            foreach ($tariff_res as $key => $value) {            
                $withdrawal_charges = (double) $value['tariff'] ;
            }
        }

        return $withdrawal_charges;        
    }

    public function getCalculatedTotalAttribute(){
    	$amount = (double) $this->attributes['total'];
        return  $amount + (double)	$this->calculated_withdrawal_charges;
    }
}
