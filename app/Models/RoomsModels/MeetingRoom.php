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
    use Notifiable;
      
    protected $hidden = ['bookings'];

    public function bookings()
    {
        return $this->hasMany('App\Models\RoomsModels\MeetingRoomBooking','room_id');
    }
}