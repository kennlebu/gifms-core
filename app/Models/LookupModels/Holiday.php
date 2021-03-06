<?php

namespace App\Models\LookupModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Holiday extends BaseModel
{
    //
    use SoftDeletes;
    protected $appends = ['full_date'];

    public function getFullDateAttribute()
    {
        return date('Y').'-'.$this->date;

    }
}
