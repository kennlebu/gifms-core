<?php

namespace App\Models\Requisitions;

use JWTAuth;
use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionStatus extends BaseModel
{
    use SoftDeletes;

    protected $appends = ['count'];

    public function getCountAttribute(){
        $user = JWTAuth::parseToken()->authenticate();
        return Requisition::where('status_id', $this->id)->where('requested_by_id', $user->id)->count();
    }
}
