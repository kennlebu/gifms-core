<?php

namespace App\Models\PaymentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class PaymentStatus extends Model
{
    //
    use SoftDeletes;
}