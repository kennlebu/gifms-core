<?php

namespace App\Models\Requisitions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use SoftDeletes;

    protected $dates = ['submitted_at'];
}
