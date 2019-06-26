<?php

namespace App\Models\ApprovalsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ApprovalLevel extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['deleted_at','migration_id'];
}
