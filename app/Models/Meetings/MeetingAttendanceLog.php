<?php

namespace App\Models\Meetings;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingAttendanceLog extends BaseModel
{
    use SoftDeletes;

    public function meeting()
    {
        return $this->belongsTo('App\Models\Meetings\Meeting','meeting_id');
    }
    public function attendee()
    {
        return $this->belongsTo('App\Models\Meetings\MeetingAttendee','attendee_id');
    }
}
