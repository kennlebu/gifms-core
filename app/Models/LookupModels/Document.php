<?php

namespace App\Models\LookupModels;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function entity()
    {
        return $this->morphTo();
    }
}
