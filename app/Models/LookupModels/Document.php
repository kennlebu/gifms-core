<?php

namespace App\Models\LookupModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    public function entity()
    {
        return $this->morphTo();
    }
}
