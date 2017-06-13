<?php

namespace App\Models\FinanceModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Budget extends BaseModel
{
    //
    use SoftDeletes;
}
