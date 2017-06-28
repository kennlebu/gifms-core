<?php

namespace App\Models\OtherModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Comment extends BaseModel
{
    //
    use SoftDeletes;
    
    public function commentable()
    {
        return $this->morphTo();
    }
}
