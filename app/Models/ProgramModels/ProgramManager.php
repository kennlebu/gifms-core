<?php

namespace App\Models\ProgramModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ProgramManager extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function program_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','program_manager_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
}
