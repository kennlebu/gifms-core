<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BankProjectBalances extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['balance_date_month', 'total_balance'];

    public function getBalanceDateMonthAttribute(){
        return date('M Y', strtotime($this->balance_date));
    }

    public function getTotalBalanceAttribute(){
        return ($this->balance ?? 0) + ($this->accruals ?? 0) + ($this->cash_received ?? 0);
    }
}
