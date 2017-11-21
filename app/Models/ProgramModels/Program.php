<?php

namespace App\Models\ProgramModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Program extends BaseModel
{
    //
    use SoftDeletes;

    public function managers()
    {
        return $this->hasMany('App\Models\ProgramModels\ProgramManager');
    }

    public function staffs()
    {
        return $this->hasMany('App\Models\ProgramModels\ProgramStaff');
    }
}
