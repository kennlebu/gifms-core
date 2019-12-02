<?php

namespace App\Models\Requisitions;

use App\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Anchu\Ftp\Facades\Ftp;

class Requisition extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['submitted_at'];
    protected $appends = ['documents'];
    protected $hidden = ['updated_at', 'deleted_at'];

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
        return $this->morphMany('App\Models\ApprovalsModels\Approval', 'approvable')->where('approver_id', '!=', 0)->orderBy('approval_level_id', 'asc');
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
}
