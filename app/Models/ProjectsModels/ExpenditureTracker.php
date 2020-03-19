<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ExpenditureTracker extends BaseModel
{
    use SoftDeletes;
}
