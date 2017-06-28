<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Payment extends BaseModel
{
    //
    use SoftDeletes;

    
    public function payable()
    {
        return $this->morphTo();
    }
}
