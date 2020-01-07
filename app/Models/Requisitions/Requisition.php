<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Anchu\Ftp\Facades\Ftp;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo;
use App\Models\MobilePaymentModels\MobilePayment;

class Requisition extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['submitted_at'];
    protected $appends = ['documents','transactions','generated_ref'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $guarded = [];

    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }

    public function program_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','program_manager_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Requisitions\RequisitionStatus','status_id');
    }

    public function allocations()
    {
        return $this->hasMany('App\Models\Requisitions\RequisitionAllocation','requisition_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Requisitions\RequisitionItem','requisition_id');
    }
    
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    
    public function approvals()
    {
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable');
        // return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc');
    }

    public function lpos()
    {
        return $this->hasMany('App\Models\LPOModels\Lpo','requisition_id');
    }

    public function getDocumentsAttribute(){
        $sanitized_list = [];
        $directory = '/requisitions/'.$this->ref.'/';
        $listing = FTP::connection()->getDirListing($directory);
        foreach($listing as $item){
            $arr = explode('/',$item);
            $sanitized_list[] = explode('.',$arr[count($arr)-1])[0];
        }
        return $sanitized_list;
    }

    public function getTransactionsAttribute(){
        $list = [];
        $lpos = Lpo::with('supplier','currency','status')->where('requisition_id', $this->id)->get();
        $list['lpos'] = $lpos;

        $invoices = Invoice::with('supplier','currency','status')->where('requisition_id', $this->id)->get();
        $list['invoices'] = $invoices;

        $claims = Claim::with('status','requested_by','currency')->where('requisition_id', $this->id)->get();
        $list['claims'] = $claims;

        $mpesas = MobilePayment::with('status','currency')->where('requisition_id', $this->id)->get();
        $list['mobile_payments'] = $mpesas;

        return $list;
    }

    public function getGeneratedRefAttribute(){
        // Get the last requisition with a ref
        $last = Requisition::whereNotNull('ref')->orderBy('id', 'desc')->first();

        // Get current year and month
        $year = date('y', strtotime($this->created_at));
        $month = date('m', strtotime($this->created_at));
        $combination = $year.$month;

        // Get last year and month
        $last_year = date('y', strtotime($last->created_at));
        $last_month = date('m', strtotime($last->created_at));
        $last_combination = $last_year.$last_month;

        // Use current combination if it isn't the same as the previous
        $month_year = $last_combination;
        if($combination != $last_combination){
            $month_year = $combination;
        }

        // Get requisition number in month 
        $last_number = explode('-', $last->ref)[1] ?? 0;
        $new_number = $this->pad(3, ($last_number + 1));

        return 'KE'.$month_year.'-'.$new_number;
        // return 'DUMMY';
    }
}
