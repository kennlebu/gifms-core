<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['submitted_at'];

    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }

    public function program_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','program_manager_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Requisitions\RequisitionStatus','status_id');
    }

    public function allocations()
    {
        return $this->hasMany('App\Models\Requisitions\RequisitionAllocation','requisition_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Requisitions\RequisitionItem','requisition_id');
    }
    
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
}
