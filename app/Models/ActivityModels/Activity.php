<?php

namespace App\Models\ActivityModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\ProjectsModels\Project;

class Activity extends BaseModel
{
    
    use SoftDeletes;

    protected $fillable = [
        'ref',
        'requested_by_id',
        'title',
        'description',
        'project_id',
        'program_id',
        'program_manager_id',
        'status_id',
        'start_date',
        'end_date',
        'rejection_reason',
        'rejected_at',
        'rejected_by_id'
    ];

    protected static $logAttributes = [
        'ref',
        'requested_by_id',
        'title',
        'description',
        'project_id',
        'program_id',
        'program_manager_id',
        'status_id',
        'start_date',
        'end_date',
        'rejection_reason',
        'rejected_at',
        'rejected_by_id'
    ];

    protected $appends = ['grant'];
    
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project','project_id');
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
    
    public function getGrantAttribute(){
        $project = Project::with('grant')->find($this->attributes['project_id']);
        return $project->grant;
    }
}
