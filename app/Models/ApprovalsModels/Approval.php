<?php

namespace App\Models\ApprovalsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Approval extends BaseModel
{
    //
    use SoftDeletes;

    public function approvable()
    {
        return $this->morphTo();
    }
}
