<?php

namespace App\Models\ApprovalsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ApprovalEmailType extends BaseModel
{
    //
    use SoftDeletes;
}
