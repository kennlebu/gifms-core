<?php

namespace App\Models\ActivityModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\ActivityModels\Activity;

class ActivityStatus extends BaseModel
{
    
    use SoftDeletes;
    protected $table = 'activities_statuses';
    protected $hidden = ['created_at','updated_at','deleted_at'];
    protected $appends = ['activities_count'];
    
    public function next_status()
    {
        return $this->belongsTo('App\Models\ActivityModels\ActivityStatus','next_status_id');
    }

    public function getActivitiesCountAttribute()
    {
        return Activity::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    
}
