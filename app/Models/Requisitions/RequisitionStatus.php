<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionStatus extends BaseModel
{
    use SoftDeletes;

    protected $appends = ['count'];

    public function getCountAttribute(){
        return Requisition::where('status_id', $this->id)->count();
    }
}
