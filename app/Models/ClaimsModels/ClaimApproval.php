<?php

namespace App\Models\ClaimsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ClaimApproval extends BaseModel
{
    //
    use SoftDeletes;
}
