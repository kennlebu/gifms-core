<?php

namespace App\Models\AccountingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Account2016 extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
