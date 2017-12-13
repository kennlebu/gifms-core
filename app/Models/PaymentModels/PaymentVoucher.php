<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class PaymentVoucher extends BaseModel
{
    //
    use SoftDeletes;

    
    public function vouchable()
    {
        return $this->morphTo();
    }
}
