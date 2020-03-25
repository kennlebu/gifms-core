<?php

namespace App\Models\FinanceModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class ExchangeRate extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['month'];

    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','added_by_id');
    }
    public function getmonthAttribute(){
        return date('M Y',  strtotime($this->active_date));
    }
    public function exchange_rate(){
        if(empty($this->exchange_rate))
            return 101.70;
    }
}