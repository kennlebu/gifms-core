<?php

namespace App\Models\AccountingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Account extends BaseModel
{
    //
    use SoftDeletes;

    public function account_type()
    {
        return $this->belongsTo('App\Models\AccountingModels\AccountType','account_type_id');
    }
}
