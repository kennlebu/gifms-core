<?php

namespace App\Models\FundsRequestModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\FundsRequestModels\FundsRequestItem;

class FundsRequest extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['total'];


    public function getTotalAttribute()
    {
        $items = FundsRequestItem::where('funds_requests_id', $this->attributes['id'])->get();
        $total = 0;
        foreach($items as $item){
            $total += $item->amount;
        }
        return $total;

    }
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\FundsRequestModels\FundsRequestStatus','status_id');
    }
}
