<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\LPOModels\LpoApproval;
use App\Models\LPOModels\LpoComment;
use App\Models\LPOModels\LpoDefaultTerm;
use App\Models\LPOModels\LpoItem;
use App\Models\LPOModels\LpoQuotation;
use App\Models\LPOModels\LpoStatus;
use App\Models\LPOModels\LpoTerm;
use App\Models\InvoicesModels\Invoice;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;
use App\Models\LookupModels\Currency;
use App\Models\SuppliesModels\Supplier;

class Lpo extends BaseModel
{
    //

    use SoftDeletes;

    protected $dates = ['deleted_at'];



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
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoicesModels\Invoice');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\LPOModels\LpoStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier');
    }
    public function received_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','received_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function quotations()
    {
        return $this->hasMany('App\Models\LPOModels\LpoQuotation');
    }
    public function items()
    {
        return $this->hasMany('App\Models\LPOModels\LpoItem');
    }
    public function terms()
    {
        return $this->hasMany('App\Models\LPOModels\LpoTerm');
    }
    public function lpo_approvals()
    {
        return $this->hasMany('App\Models\LPOModels\LpoApproval');
    }





}
