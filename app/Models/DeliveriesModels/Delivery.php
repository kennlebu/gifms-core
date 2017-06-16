<?php

namespace App\Models\DeliveriesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Delivery extends BaseModel
{
    //
    use SoftDeletes;
}
