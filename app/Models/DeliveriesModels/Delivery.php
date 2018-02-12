<?php

namespace App\Models\DeliveriesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\SuppliesModels\Supplier;

class Delivery extends BaseModel
{
    //
    use SoftDeletes;
    
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
}
