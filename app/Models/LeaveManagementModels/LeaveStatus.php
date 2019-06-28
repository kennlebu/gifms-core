<?php

namespace App\Models\LeaveManagementModels;

use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class LeaveStatus extends BaseModel
{
    use SoftDeletes;

    protected $appends = ['count'];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function getCountAttribute()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return LeaveRequest::where("deleted_at",null)
                ->where('status_id', $this->attributes['id'])
                ->where('requested_by_id', $user->id)
		        ->count();
    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\LeaveManagementModels\LeaveStatus','next_status_id');
    }
    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
}
