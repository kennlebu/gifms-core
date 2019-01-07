<?php

namespace App\Models\FundsRequestModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class FundsRequestItem extends BaseModel
{
    //
    use SoftDeletes;
    protected $table = 'funds_requests_items';

    public function funds_request()
    {
        return $this->belongsTo('App\Models\FundsRequestModels\FundsRequest','funds_request_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project','project_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','program_activity_id');
    }
}
