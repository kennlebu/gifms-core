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

    // protected $appends = ['supplier','uploaded_by'];


    // public function getSupplierAttribute()
    // {
    //     return Supplier::find($this->attributes['supplier_id']);

    // }
    // public function getUploadedByAttribute()
    // {
    //     return Staff::find($this->attributes['uploaded_by_id']);

    // }

    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');

    }
    public function uploaded_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','uploaded_by_id');

    }



}
