<?php

namespace App\Models\AllocationModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class OfficeCostAllocationItem extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function office_cost_allocation()
    {
        return $this->belongsTo('App\Models\AllocationModels\OfficeCostAllocation');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }  
}
