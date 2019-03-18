<?php

namespace App\Models\ProgramModels;

use Illuminate\Database\Eloquent\Model;

class ProgramStaff extends Model
{
    // use SoftDeletes;
    protected $table = 'program_teams';

    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program', 'program_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'staff_id');
    }

}
