<?php

namespace App\Models\Meetings;

use App\Models\BaseModels\BaseModel;

class MeetingAttendanceRegister extends BaseModel
{
    public function attendee()
    {
        return $this->belongsTo('App\Models\Meetings\MeetingAttendee','attendee_id');
    }
    public function meeting()
    {
        return $this->belongsTo('App\Models\Meetings\Meeting','meeting_id');
    }
}
