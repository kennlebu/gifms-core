<?php

namespace App\Models\ProgramModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Program extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['program_managers'];

    public function managers()
    {
        return $this->hasMany('App\Models\ProgramModels\ProgramManager');
        // return $this->hasOne('App\Models\ProgramModels\ProgramManager')->with('program_manager');
    }

    public function getProgramManagersAttribute()
    {
        $managers = [];
        $program_manager = ProgramManager::where('program_id', $this->id)->get();
        foreach($program_manager as $pm) {
            $managers[] = $pm->program_manager;
        }

        return $managers;
    }

    public function staffs()
    {
        return $this->hasMany('App\Models\ProgramModels\ProgramStaff');
    }

    public function program_manager()
    {
        $pm = ProgramManager::where('program_id', $this->id)->first();
        return $pm->program_manager ?? null;
    }
}
