<?php

namespace App\Models\AllocationModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Allocation extends BaseModel
{
    //
    use SoftDeletes;

    public function allocatable()
    {
        return $this->morphTo();
    }
}
