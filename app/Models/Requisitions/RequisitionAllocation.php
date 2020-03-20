<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionAllocation extends BaseModel
{
    use SoftDeletes;

    public function allocated_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','allocated_by_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }    

    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }
    
    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective','objective_id');
    }
}
