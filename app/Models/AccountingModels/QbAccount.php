<?php

namespace App\Models\AccountingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class QbAccount extends BaseModel
{
    //
    use SoftDeletes;
}
