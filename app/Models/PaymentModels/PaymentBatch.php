<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class PaymentBatch extends BaseModel
{
    //
    use SoftDeletes;

    public function processed_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','processed_by_id');
    }
}
