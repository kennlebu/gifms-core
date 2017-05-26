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
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;

class MobilePayment extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = [
                            'requested_by',
                            'requested_action_by',
                            'project',
                            'account',
                            'mobile_payment_type',
                            'invoice',
                            'status',
                            'project_manager',
                            'region',
                            'county'
                        ];



    public function getRequestedByAttribute()
    {
    	

	        return Staff::find($this->attributes['requested_by_id']);


    }
    public function getRequestedActionByAttribute()
    {
       

	        return Staff::find($this->attributes['requested_action_by_id']);
	    

    }
    public function getProjectAttribute()
    {
        

	        return Project::find($this->attributes['project_id']);

	    

    }
    public function getAccountAttribute()
    {
        

	        return Account::find($this->attributes['account_id']);
	    

    }
    public function getMobilePaymentTypeAttribute()
    {
        

	        return MobilePaymentType::find($this->attributes['mobile_payment_type_id']);
	    

    }
    public function getInvoiceAttribute()
    {
        

	        return Invoice::find($this->attributes['invoice_id']);
	    

    }
    public function getStatusAttribute()
    {
        

	        return MobilePaymentStatus::find($this->attributes['status_id']);
	    

    }
    public function getProjectManagerAttribute()
    {
        

	        return Staff::find($this->attributes['project_manager_id']);
	    

    }
    public function getRegionAttribute()
    {
        

	        return Region::find($this->attributes['region_id']);
	    

    }
    public function getCountyAttribute()
    {
        

	        return County::find($this->attributes['county_id']);
	    

    }
}
