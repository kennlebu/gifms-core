<?php

namespace App\Models\ApprovalsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Approval extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['migration_approver_id','ref','deleted_at'];

    public function approvable()
    {
        return $this->morphTo();
    }
    public function approver()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','approver_id');
    }
    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
}
