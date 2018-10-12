<?php

namespace App\Models\ActivityModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Activity extends BaseModel
{
    
    use SoftDeletes;
    
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ActivityModels\ActivityStatus', 'status_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    
}
