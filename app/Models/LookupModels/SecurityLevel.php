<?php

namespace App\Models\LookupModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class SecurityLevel extends BaseModel
{
    //
    use SoftDeletes;
}
