<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ProjectStatus extends BaseModel
{
    //
    use SoftDeletes;
}
