<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\BankingModels\BankTransaction;

class Payment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['simple_date', 'bank_transaction'];
    
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
        // $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);
        // return $myDateTime->format('Ymd');
        $timestamp = strtotime($this->attributes['created_at']); 
        return $new_date = date('Ymd', $timestamp);
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
}
