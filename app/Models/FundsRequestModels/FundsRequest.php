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

    protected $appends = ['total_kes', 'total_usd', 'ref'];


    public function getTotalKesAttribute()
    {
        $items = FundsRequestItem::where('funds_request_id', $this->attributes['id'])->get();
        $total = 0;
        foreach($items as $item){
            if($item->currency_id == 1){
                $total += $item->amount;
            }
        }
        return $total;
    }
    public function getTotalUsdAttribute()
    {
        $items = FundsRequestItem::where('funds_request_id', $this->attributes['id'])->get();
        $total = 0;
        foreach($items as $item){
            if($item->currency_id == 2){
                $total += $item->amount;
            }
        }
        return $total;
    }
    public function getRefAttribute(){
        return $this->pad(5, $this->attributes['id']);
    }
    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\FundsRequestModels\FundsRequestStatus','status_id');
    }
    public function funds_request_items()
    {
        return $this->hasMany('App\Models\FundsRequestModels\FundsRequestItem');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    } 
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
}
