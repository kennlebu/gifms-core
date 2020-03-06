<?php

namespace App\Models\FinanceModels;

use App\Models\BaseModels\BaseModel;

class BudgetAccount extends BaseModel
{
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account', 'account_id');
    }
}
