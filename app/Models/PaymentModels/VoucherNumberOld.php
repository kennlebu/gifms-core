<?php

namespace App\Models\PaymentModels;
use App\Models\BaseModels\BaseModel;

class VoucherNumberOld extends BaseModel
{
    protected $table = 'voucher_numbers_old';
    protected $guarded = ['id'];
    protected $appends = ['old'];

    public function getOldAttribute() {
        return true;
    }
}
