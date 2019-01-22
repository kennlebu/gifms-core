<?php

namespace App\Models\FundsRequestModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\FundsRequestModels\FundsRequestItem;

class ConsolidatedFunds extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['funds_requests'];

    public function getFundsRequestsAttribute()
    {
        return FundsRequest::where('consolidated_funds_id', $this->attributes['id'])->get();
    }
}
