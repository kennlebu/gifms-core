<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BankAccount extends BaseModel
{
    //
    use SoftDeletes;

    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
}
