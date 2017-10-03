<?php

namespace App\Models\BankingModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BankBranch extends BaseModel
{
    //
    use SoftDeletes;


    public function bank()
    {
        return $this->belongsTo('App\Models\BankingModels\Bank');
    }
}
