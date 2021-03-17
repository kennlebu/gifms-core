<?php

namespace App\Models\AllocationModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class OfficeCostAllocation extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['month'];

    public function items()
    {
        return $this->hasMany('App\Models\AllocationModels\OfficeCostAllocationItem');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'created_by_id');
    }

    public function getMonthAttribute() {
        return date('M Y', strtotime($this->date));
    }
}
