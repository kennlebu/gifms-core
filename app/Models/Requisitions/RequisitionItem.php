<?php

namespace App\Models\Requisitions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends BaseModel
{
    use SoftDeletes;
}
