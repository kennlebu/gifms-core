<?php

namespace App\Models\FundsRequestModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\FundsRequestModels\FundsRequest;

class FundsRequestStatus extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['count'];
    protected $table = 'funds_requests_statuses';


    public function getCountAttribute()
    {
        return FundsRequest::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\FundsRequestModels\FundsRequestStatus','next_status_id');
    }
}
