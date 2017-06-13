<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\SuppliesModels\Supplier;
use App\Models\StaffModels\Staff;

class LpoQuotation extends BaseModel
{
    //
    use SoftDeletes;

    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');

    }
    public function uploaded_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','uploaded_by_id');

    }



}
