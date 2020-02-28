<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BankProjectBalances extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['balance_date_month'];

    public function getBalanceDateMonthAttribute(){
        return date('M Y', strtotime($this->balance_date));
    }
}
