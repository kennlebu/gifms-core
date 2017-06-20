<?php

namespace App\Models\ClaimsModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\ClaimsModels\ClaimApproval;
use App\Models\ClaimsModels\ClaimStatus;
use App\Models\LookupModels\Currency;

class Claim extends Model
{
    
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function request_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','request_action_by_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\LPOModels\LpoStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function claim_approvals()
    {
        return $this->hasMany('App\Models\ClaimsModels\ClaimApproval');
    }

}
