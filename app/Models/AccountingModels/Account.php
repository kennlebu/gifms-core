<?php

namespace App\Models\AccountingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Account extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at','brevity','client','migration_id','office_cost'];

    public function account_type()
    {
        return $this->belongsTo('App\Models\AccountingModels\AccountType','account_type_id');
    }
}
