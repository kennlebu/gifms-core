<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class CashReceived extends BaseModel
{
    use SoftDeletes;
    protected $table = 'cash_received';

    public function bank_balance()
    {
        return $this->belongsTo('App\Models\BankingModels\bankProjectBalances','bank_balance_id');
    }
}
