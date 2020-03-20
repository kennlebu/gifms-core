<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BankProjectBalances extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['balance_date_month', 'total_balance', 'total_cash_received'];

    public function getBalanceDateMonthAttribute(){
        return date('M Y', strtotime($this->balance_date));
    }

    public function getTotalBalanceAttribute(){
        return ($this->balance ?? 0) + ($this->accruals ?? 0) + ($this->total_cash_received);
    }

    public function getTotalCashReceivedAttribute(){
        $total = CashReceived::where('bank_balance_id', $this->id)->sum('amount');
        return $total ?? 0;
    }
}
