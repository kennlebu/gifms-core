<?php

namespace App\Models\Meetings;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['full_invite_url'];

    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County','county_id');
    }
    public function created_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','created_by_id');
    }

    public function getFullInviteUrlAttribute(){
        return config('app.url').'/event/register/'.$this->invite_url;
    }
}
