<?php
namespace App\Models\DeliveriesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\DeliveriesModels\Delivery;

class DeliveryStatus extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['deliveries_count'];


    public function getDeliveriesCountAttribute()
    {
        return Delivery::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\DeliveriesModels\DeliveryStatus','next_status_id');
    }
}