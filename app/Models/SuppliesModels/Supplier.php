<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Supplier extends Model
{
    //
    use SoftDeletes;
}
