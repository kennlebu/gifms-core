<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Bank extends BaseModel
{
    //
    use SoftDeletes;
}
