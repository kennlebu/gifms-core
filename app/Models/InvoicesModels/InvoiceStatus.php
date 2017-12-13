<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
// use App\Models\InvoicesModels\Invoice;

class InvoiceStatus extends BaseModel
{
    //
    use SoftDeletes;



    protected $appends = ['invoices_count'];


    public function getInvoicesCountAttribute()
    {
        return Invoice::where("deleted_at",null)
		        ->where('status_id', $this->attributes['id'])
		        ->count();

    }
    public function next_status()
    {
        return $this->belongsTo('App\Models\InvoicesModels\InvoiceStatus','next_status_id');
    }
    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
}
