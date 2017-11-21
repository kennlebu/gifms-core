<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Project extends BaseModel
{
    //
    use SoftDeletes;


    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ProjectsModels\ProjectStatus','status_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\LookupModels\Country','country_id');
    }
    public function staffs()
    {
        return $this->belongsToMany('App\Models\StaffModels\Staff', 'project_teams', 'project_id', 'staff_id');
    }
}
