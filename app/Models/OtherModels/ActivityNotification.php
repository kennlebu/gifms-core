<?php

namespace App\Models\OtherModels;

use App\Models\BaseModels\BaseModel;

class ActivityNotification extends BaseModel
{
    protected $appends = ['time_ago'];

    public function getTimeAgoAttribute(){
        return $this->calculate_time_span($this->created_at);
    }

    public function action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','action_by_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','user_id');
    }
}
