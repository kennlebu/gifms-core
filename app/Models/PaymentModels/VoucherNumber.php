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

    public function generated_voucher_from_previous(){        
        $default_voucher_int = '0001';
        $this_year = date('y', strtotime($this->created_at));
        $previous_voucher = VoucherNumber::where('id', '<', $this->id)->orderBy('id', 'desc')->first();
        $previous_voucher_year = (int) substr($previous_voucher->voucher_number, 2, 2);
        $next_voucher_int = ((int) substr($previous_voucher->voucher_number, 4, 4)) + 1;
        $padded_int = $this->pad(4, $next_voucher_int );

        if($this_year != $previous_voucher_year){
            $padded_int = $default_voucher_int;
        }

        return 'KE'.$this_year.$padded_int;
    }
}
