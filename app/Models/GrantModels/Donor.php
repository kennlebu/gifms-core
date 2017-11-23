<?php

namespace App\Models\GrantModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Donor extends BaseModel
{
    //
    use SoftDeletes;

    public function grants()
    {
        return $this->hasMany('App\Models\GrantModels\Grant');
    }
}
