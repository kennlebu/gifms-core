<?php

namespace App\Models\FinanceModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Budget extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['totals'];


    public function items()
    {
        return $this->hasMany('App\Models\FinanceModels\BudgetItem');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
    
    public function getTotalsAttribute(){

        $items     =   $this->items;
        $totals    =   0;

        foreach ($items as $key => $value) {
            $totals    +=  (float) $value->amount;
        }

        return $totals;

    }
}
