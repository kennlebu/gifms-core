<?php

namespace App\Models\Meetings;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends BaseModel
{
    use SoftDeletes;

    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County','county_id');
    }
    public function created_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','created_by_id');
    }
}
