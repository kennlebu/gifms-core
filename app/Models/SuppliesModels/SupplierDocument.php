<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierDocument extends Model
{
    use SoftDeletes;

    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','added_by_id');
    }
}
