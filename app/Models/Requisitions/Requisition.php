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
    protected $appends = ['transactions'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $guarded = [];

    public function requested_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','requested_by_id');
    }
    
    public function returned_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','returned_by_id');
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
        return $this->hasMany('App\Models\ApprovalsModels\Approval', 'approvable_id')->with('approval_level')->where('approvable_type', 'requisitions');
    }

    public function lpos()
    {
        return $this->hasMany('App\Models\LPOModels\Lpo','requisition_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\InvoicesModels\Invoice','requisition_id');
    }

    public function mobile_payments()
    {
        return $this->hasMany('App\Models\MobilePaymentModels\MobilePayment','requisition_id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\DeliveriesModels\Delivery','requisition_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Requisitions\RequisitionDocument','requisition_id');
    }

    // public function getDocumentsAttribute(){
    //     $sanitized_list = [];
    //     $directory = '/requisitions/'.$this->ref.'/';
    //     $listing = FTP::connection()->getDirListing($directory);
    //     foreach($listing as $item){
    //         $arr = explode('/',$item);
    //         $sanitized_list[] = explode('.',$arr[count($arr)-1])[0];
    //     }
    //     return $sanitized_list;
    // }

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
        $last = Requisition::whereNotNull('ref')->orderBy('id', 'desc')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->first();

        // Get current year and month
        $combination = date('ym', strtotime($this->created_at));

        // Get last year and month
        if(!empty($last)){
            $last_combination = date('ym', strtotime($last->created_at));
        }
        else {
            $last_combination = date('ym');
        }

        // Use current combination if it isn't the same as the previous
        $month_year = $last_combination;
        $new_number = '001';
        if($combination != $last_combination){
            $month_year = $combination;
            
        }
        else {
            // Get requisition number in month 
            if(!empty($last)){
                $last_number = explode('-', $last->ref)[1] ?? 0;
            }
            else {
                $last_number = 0;
            }
            $new_number = $this->pad(3, ($last_number + 1));
        }        

        return 'KE'.$month_year.'-'.$new_number;
    }
}
