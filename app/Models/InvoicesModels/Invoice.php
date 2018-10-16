<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends BaseModel
{
    //
    use SoftDeletes;

    // use LogsActivity;

    protected $fillable = [
        'ref',
        'expense_desc',
        'expense_purpose',
        'external_ref',
        'invoice_date',
        'received_at',
        'received_by_id',
        'raised_at',
        'raised_by_id',
        'raise_action_by_id',
        'total',
        'invoice_document',
        'project_manager_id',
        'supplier_id',
        'status_id',
        'payment_mode_id',
        'currency_id',
        'lpo_id'
    ];



    protected static $logAttributes = [

        'ref',
        'expense_desc',
        'expense_purpose',
        'external_ref',
        'invoice_date',
        'received_at',
        'received_by_id',
        'raised_at',
        'raised_by_id',
        'raise_action_by_id',
        'total',
        'invoice_document',
        'project_manager_id',
        'supplier_id',
        'status_id',
        'payment_mode_id',
        'currency_id',
        'lpo_id'
    ];

    protected $appends = ['amount_allocated'];

    
    public function raised_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raised_by_id');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function raise_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raise_action_by_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\InvoicesModels\InvoiceStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier','supplier_id');
    }
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentMode','payment_mode_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
    }
    public function comments()
    {
        return $this->morphMany('App\Models\OtherModels\Comment', 'commentable')->orderBy('created_at','asc');
    }
    public function payments()
    {
        return $this->morphMany('App\Models\PaymentModels\Payment', 'payable')->orderBy('created_at','asc');
    }
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('created_at', 'NOT LIKE', '%2018-04-28 22%')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc')->orderBy('created_at','asc');
    }
    public function allocations()
    {
        return $this->morphMany('App\Models\AllocationModels\Allocation', 'allocatable')->orderBy('created_at','asc');
    }
    public function vouchers()
    {
        return $this->morphMany('App\Models\PaymentModels\PaymentVoucher', 'vouchable')->orderBy('created_at','asc');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
       
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','activity_id');
    }



    public function getAmountAllocatedAttribute(){

        $allocations  =   $this->allocations;
        $amount_allocated   =   0;

        foreach ($allocations as $key => $value) {
            $amount_allocated   +=   (float)   $value->amount_allocated;
        }

        return $amount_allocated;



    }
}
