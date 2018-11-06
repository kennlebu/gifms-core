<?php

namespace App\Models\PaymentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\BankingModels\BankTransaction;

class VoucherNumber extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['bank_transaction'];

    public function getBankTransactionAttribute(){
        return BankTransaction::where("deleted_at",null)
		        ->where('chai_ref', $this->attributes['voucher_number'])
		        ->first();
    }
}
