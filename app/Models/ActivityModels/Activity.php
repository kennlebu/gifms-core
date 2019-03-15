<?php

namespace App\Models\ActivityModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\ProjectsModels\Project;
use App\Models\AllocationModels\Allocation;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\LPOModels\Lpo;

class Activity extends BaseModel
{
    
    use SoftDeletes;

    protected $appends = ['grant','has_transactions'];
    protected $hidden = ['deleted_at','updated_at'];
    
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function program_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','program_manager_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ActivityModels\ActivityStatus', 'status_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective', 'objective_id');
    }
    public function objectives()
    {
        return $this->hasMany('App\Models\ActivityModels\ActivityObjective', 'activity_id');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->orderBy('approval_level_id', 'asc');
    }
    
    public function getGrantAttribute(){
        if(!empty($this->attributes['project_id'])){
            $project = Project::with('grant')->find($this->attributes['project_id']);
            return $project->grant;
        }
        else{
            return 'N/A';
        }
    }

    public function getHasTransactionsAttribute(){
        $has_transactions = false;
        $invoice = Invoice::where('program_activity_id', $this->id)->first();
        if(!empty($invoice)) return true;

        $mobile_payment = MobilePayment::where('program_activity_id', $this->id)->first();
        if(!empty($mobile_payment)) return true;

        $lpo = Lpo::where('program_activity_id', $this->id)->first();
        if(!empty($lpo)) return true;

        $claim = Allocation::where('activity_id', $this->id)
                            ->where('allocatable_type', 'claims')
                            ->first();
        if(!empty($claim)) return true;

        return $has_transactions;
    }
}
