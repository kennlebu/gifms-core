<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class InvoiceType extends BaseModel
{
    //
    use SoftDeletes;
}
