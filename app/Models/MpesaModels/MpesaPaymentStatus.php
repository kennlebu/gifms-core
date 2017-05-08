<?php

namespace App\Models\MpesaModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class MpesaPaymentStatus extends BaseModel
{
    //
    use SoftDeletes;
}
