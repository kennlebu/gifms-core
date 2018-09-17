<?php

namespace App\Models\RoomsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class MeetingRoom extends BaseModel
{
    
    use SoftDeletes;

    public function room()
    {
        return $this->belongsTo('App\Models\RoomsModels\MeetingRoom','room_id');
    }

    public function booked_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','booked_by_id');
    }
}