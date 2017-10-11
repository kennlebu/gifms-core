<?php

namespace App\Models\AllocationModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Allocation extends BaseModel
{
    //
    use SoftDeletes;

    public function allocatable()
    {
        return $this->morphTo();
    }
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
}