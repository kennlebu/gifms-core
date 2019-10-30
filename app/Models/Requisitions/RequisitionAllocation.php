<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionAllocation extends BaseModel
{
    use SoftDeletes;
}
