<?php

namespace App\Models\OtherModels;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    
    public function commentable()
    {
        return $this->morphTo();
    }
}
