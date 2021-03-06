<?php

namespace App\Models\ReportModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ReportingObjective extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
}
