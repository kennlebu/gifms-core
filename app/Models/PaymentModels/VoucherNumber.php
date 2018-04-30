<?php

namespace App\Models\PaymentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class VoucherNumber extends BaseModel
{
    use SoftDeletes;

    // public function payment(){
    //     $this->belongsTo('App\Models\PaymentModels\Payment', 'voucher_no');
    // }
}
