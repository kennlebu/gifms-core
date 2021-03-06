<?php

namespace App\Models\Meetings;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['full_invite_url', 'full_invite_url_banking', 'expired'];

    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County','county_id');
    }
    public function created_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','created_by_id');
    }
    public function attendees()
    {
        // $logs = MeetingAttendanceLog::where('meeting_id', $this->id)->pluck('attendee_id')->toArray();
        $logs = MeetingAttendanceRegister::where('meeting_id', $this->id)->pluck('attendee_id')->toArray();
        $attendees = MeetingAttendee::whereIn('id', $logs)->get();
        return $attendees;
    }

    public function getFullInviteUrlAttribute(){
        return config('app.url').'/event/register/'.$this->invite_url;
    }

    public function getFullInviteUrlBankingAttribute(){
        return config('app.url').'/event/register/'.$this->invite_url.'/b';
    }

    public function getExpiredAttribute() {
        $expired = false;
        if(date('Y-m-d') > date('Y-m-d', strtotime($this->ends_on))) {
            $expired = true;
        }
        return $expired;
    }
}
