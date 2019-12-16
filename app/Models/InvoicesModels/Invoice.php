<?php

namespace App\Models\InvoicesModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use DB;
use App\Models\PaymentModels\Payment;

class Invoice extends BaseModel
{
    //
    use SoftDeletes;

    protected $fillable = [
        'ref',
        'expense_desc',
        'expense_purpose',
        'external_ref',
        'invoice_date',
        'received_at',
        'received_by_id',
        'raised_at',
        'raised_by_id',
        'raise_action_by_id',
        'total',
        'invoice_document',
        'project_manager_id',
        'supplier_id',
        'status_id',
        'payment_mode_id',
        'currency_id',
        'lpo_id'
    ];



    protected static $logAttributes = [

        'ref',
        'expense_desc',
        'expense_purpose',
        'external_ref',
        'invoice_date',
        'received_at',
        'received_by_id',
        'raised_at',
        'raised_by_id',
        'raise_action_by_id',
        'total',
        'invoice_document',
        'project_manager_id',
        'supplier_id',
        'status_id',
        'payment_mode_id',
        'currency_id',
        'lpo_id'
    ];

    protected $appends = ['amount_allocated', 'bank_transaction', 'bank_transactions','calculated_withdrawal_charges','calculated_total'];
    protected $hidden = ['staff_advance','reconcilliation_date','comments','country_id','reporting_categories_id','reporting_objective_id',
                        'approver_id','claim_id','advance_id','mpesa_id','bank_ref_no','shared_cost','recurring_period','recurr_end_date',
                        'excise_duty','catering_levy','zero_rated','exempt_supplies','other_levies','other_amounts','migration_supplier_id',
                        'migration_project_manager_id','migration_management_approval_id','migration_raised_by_id','migration_approver_id',
                        'migration_claim_id',' migration_lpo_id','migration_advance_id','migration_mpesa_id','migration_id','deleted_at'];
    
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
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
    }
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable')->orderBy('created_at','asc');
    }
    public function payments()
    {
        return $this->morphMany('App\Models\PaymentModels\Payment', 'payable')->orderBy('created_at','asc');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('created_at', 'NOT LIKE', '%2018-04-28 22%')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc')->orderBy('created_at','asc');
    }
    public function allocations()
    {
        return $this->morphMany('App\Models\AllocationModels\Allocation', 'allocatable')->orderBy('created_at','asc');
    }
    public function vouchers()
    {
        return $this->morphMany('App\Models\PaymentModels\PaymentVoucher', 'vouchable')->orderBy('created_at','asc');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
       
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','program_activity_id');
    }
    public function getVatRateAttribute($value)   // return the default (6) if it
    {                                             // has not been set.
        if(empty($value)) return 6;
        return $value;
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
                    ->where('payable_type', 'invoices')
                    ->first();
                
        if(!empty($payment->bank_transaction)){
            return $payment->bank_transaction;
        }
        else return null;
    }

    public function getBankTransactionsAttribute(){
        $payment = Payment::with('paid_to_bank', 'paid_to_bank_branch')->where('payable_id', $this->attributes['id'])
                    ->where('payable_type', 'invoices')
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
