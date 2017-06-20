<?php

namespace App\Models\ClaimsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\ClaimsModels\Claim;

class ClaimStatus extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['claims_count'];


    public function getClaimsCountAttribute()
    {
        return Claim::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
}
