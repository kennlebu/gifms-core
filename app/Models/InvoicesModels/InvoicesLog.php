<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class InvoicesLog extends BaseModel
{
    //
    use SoftDeletes;
}
