<?php

namespace App\Models\PaymentModels;

use App\Models\AdvancesModels\Advance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\BankingModels\BankTransaction;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;

class Payment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['simple_date', 'bank_transaction', 'net_amount'];
    protected $with = ['payable'];
    
    public function payable()
    {
        return $this->morphTo();
    }
    public function debit_bank_account()
    {
        return $this->belongsTo('App\Models\BankingModels\BankAccount','debit_bank_account_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function paid_to_bank()
    {
        return $this->belongsTo('App\Models\BankingModels\Bank','paid_to_bank_id');
    }
    public function paid_to_bank_branch()
    {
        return $this->belongsTo('App\Models\BankingModels\BankBranch','paid_to_bank_branch_id');
    }
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentMode');
    }
    public function payment_batch()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentBatch');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentStatus');
    }
    public function getSimpleDateAttribute(){
        $timestamp = strtotime($this->attributes['created_at']); 
        return date('Ymd', $timestamp);
    }
    public function voucher_number(){
        return $this->belongsTo('App\Models\PaymentModels\VoucherNumber', 'voucher_no', 'id');
    }

    public function getBankTransactionAttribute(){
        $voucher = VoucherNumber::find($this->attributes['voucher_no']);
        if(!empty($voucher))
            return BankTransaction::where('chai_ref', $voucher->voucher_number)->first();
        else
            return null;
    }

    public function getNetAmountAttribute(){
        $payable = null;
        if($this->payable_type == 'invoices') $payable = Invoice::find($this->payable_id);
        elseif($this->payable_type == 'claims') $payable = Claim::find($this->payable_id);
        elseif($this->payable_type == 'advances') $payable = Advance::find($this->payable_id);

        $vat_rate = $payable->vat_rate ?? 6;

        $net_amount = $this->amount;
        if(!empty($this->amount)){
            if(!empty($this->income_tax_amount_withheld)){
                $net_amount -= ceil($this->income_tax_amount_withheld);
            }
            
            if(!empty($this->vat_amount_withheld)){
                $vat_withhold_amount = $vat_rate*$this->vat_amount_withheld;
                $net_amount -= ceil($vat_withhold_amount);
            }
        }
        return $net_amount;
    }
}
