<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\MobilePaymentModels\MobilePaymentType;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePaymentStatus;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;
use App\Models\MobilePaymentModels\MobilePaymentPayee;
use App\Models\MobilePaymentModels\MobilePaymentApproval;

class MobilePayment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = [];

 

    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    public function requested_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_action_by_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }
    public function mobile_payment_type()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentType');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoicesModels\Invoice');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\MobilePaymentModels\MobilePaymentStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function region()
    {
        return $this->belongsTo('App\Models\LookupModels\Region');
    }
    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function payees()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePaymentPayee');
    }
    public function mobile_payment_approvals()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePaymentApproval');
    }









}
