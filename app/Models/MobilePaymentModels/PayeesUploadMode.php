<?php

namespace App\Models\MobilePaymentModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\Model;

class PayeesUploadMode extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at','deleted_at','updated_at'];
}
