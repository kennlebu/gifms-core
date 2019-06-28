<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ApprovalsModels\ApprovalLevel;

class MobilePaymentApproval extends BaseModel
{
    //
    use SoftDeletes;
    protected $appends = [ 'approver', 'approval_level' ];

    public function getApproverAttribute()
    {
	    return Staff::find($this->attributes['approver_id']);
    }

    public function getApprovalLevelAttribute()
    {
	    return ApprovalLevel::find($this->attributes['approval_level_id']);
    }
}
