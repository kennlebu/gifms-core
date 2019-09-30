<?php

namespace App\Models\Requisitions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionStatus extends Model
{
    use SoftDeletes;

    protected $appends = ['count'];

    public function getCountAttribute(){
        return Requisition::where('status_id', $this->id)->count();
    }
}
