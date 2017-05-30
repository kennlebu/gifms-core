<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\LPOModels\Lpo;

class LpoStatus extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['lpo_count'];


    public function getLpoCountAttribute()
    {
        return Lpo::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
}
