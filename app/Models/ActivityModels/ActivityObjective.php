<?php

namespace App\Models\ActivityModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ActivityObjective extends BaseModel
{
    
    use SoftDeletes;

    protected $hidden = ['created_at','deleted_at','updated_at'];
    
    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective', 'objective_id');
    }
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity', 'activity_id');
    }
}
