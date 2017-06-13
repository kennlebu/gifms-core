<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class PaymentBatch extends BaseModel
{
    //
    use SoftDeletes;
}
