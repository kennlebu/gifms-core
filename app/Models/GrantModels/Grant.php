<?php

namespace App\Models\GrantModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Grant extends BaseModel
{
    //
    use SoftDeletes;

    public function status()
    {
        return $this->belongsTo('App\Models\GrantModels\GrantStatus','status_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
    public function donor()
    {
        return $this->belongsTo('App\Models\GrantModels\Donor','donor_id');
    }
}
