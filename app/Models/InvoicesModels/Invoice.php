<?php

namespace App\Models\InvoicesModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Invoice extends BaseModel
{
    //
    use SoftDeletes;

    
    public function raised_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raised_by_id');
    }
    public function raise_action_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','raise_action_by_id');
    }
    // public function project()
    // {
    //     return $this->belongsTo('App\Models\ProjectsModels\Project');
    // }
    public function status()
    {
        return $this->belongsTo('App\Models\InvoicesModels\InvoiceStatus');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','rejected_by_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency');
    }
    public function invoice_approvals()
    {
        return $this->hasMany('App\Models\InvoicesModels\InvoiceApproval');
    }
    public function allocations()
    {
        return $this->hasMany('App\Models\InvoicesModels\InvoiceProjectAccountAllocation');
    }
    public function comments()
    {
        return $this->morphMany('App\OtherModels\Comment', 'commentable');
    }
}
