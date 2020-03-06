<?php

namespace App\Models\FinanceModels;

use App\Models\BaseModels\BaseModel;

class BudgetObjective extends BaseModel
{
    protected $appends = ['objective_total'];

    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective', 'objective_id');
    }
    public function accounts()
    {
        return $this->hasMany('App\Models\FinanceModels\BudgetAccount');
    }

    public function getObjectiveTotalAttribute(){

        $accounts = $this->accounts;
        $total = 0;

        foreach ($accounts as $account) {
            $total += (float) $account->amount;
        }

        return $total;
    }
}
