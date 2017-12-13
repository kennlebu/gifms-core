<?php

namespace App\Models\AdvancesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\AdvancesModels\Advance;

class AdvanceStatus extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['advances_count'];


    public function getAdvancesCountAttribute()
    {
        return Advance::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\AdvancesModels\AdvanceStatus','next_status_id');
    }
    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
}
