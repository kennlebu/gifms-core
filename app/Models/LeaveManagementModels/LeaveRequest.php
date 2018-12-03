<?php

namespace App\Models\LeaveManagementModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveRequest extends BaseModel
{
    use SoftDeletes;

    protected $appends = ['ref'];

    protected $fillable = [
        'id',
        'requested_by_id',
        'leave_type_id',
        'status_id',
        'line_manager_id',
        'start_date',
        'end_date',
        'no_of_days',
        'alternate_phone_1',
        'alternate_phone_2',
        'alternate_email_1',
        'alternate_email_2',
        'rejected_by_id',
        'rejection_reason',
        'requester_comments',
        'approver_comments'
    ];



    protected static $logAttributes = [
        'id',
        'requested_by_id',
        'leave_type_id',
        'status_id',
        'line_manager_id',
        'start_date',
        'end_date',
        'no_of_days',
        'alternate_phone_1',
        'alternate_phone_2',
        'alternate_email_1',
        'alternate_email_2',
        'rejected_by_id',
        'rejection_reason',
        'requester_comments',
        'approver_comments'
    ];
    
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function leave_type()
    {
        return $this->belongsTo('App\Models\LeaveManagementModels\LeaveType','leave_type_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\LeaveManagementModels\LeaveStatus');
    }
    public function line_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','line_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('approver_id', '!=', 0);
    }
    public function getRefAttribute(){
        return '#'.$this->pad(5, $this->attributes['id']);
    }
}
