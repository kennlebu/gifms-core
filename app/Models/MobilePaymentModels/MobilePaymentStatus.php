<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class MobilePaymentStatus extends BaseModel
{
    //
    use SoftDeletes;



    protected $appends = ['mobile_payments_count'];


    public function getMobilePaymentsCountAttribute()
    {
        return MobilePayment::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }

}
