<?php

namespace App\Models\Requisitions;

use Illuminate\Database\Eloquent\Model;

class RequisitionDocument extends Model
{
    public $timestamps = false;

    public function uploaded_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','uploaded_by_id');
    }
}
