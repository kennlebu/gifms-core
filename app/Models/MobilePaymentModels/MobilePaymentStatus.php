<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class MobilePaymentStatus extends BaseModel
{
    //
    use SoftDeletes;


    protected $hidden = ['created_at','deleted_at','updated_at','approvable','deleted_at','migration_id','migration_status_security_level',
                        'status_security_level'];
    protected $appends = ['mobile_payments_count'];


    public function getMobilePaymentsCountAttribute()
    {
        return MobilePayment::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentStatus','next_status_id');
    }
    public function new_next_status()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentStatus','new_next_status_id');
    }
    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
    public function getStatusAttribute(){
        return $this->mobile_payment_status;
    }

}
