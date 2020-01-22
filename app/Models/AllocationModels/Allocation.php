<?php

namespace App\Models\AllocationModels;

use App\Models\AdvancesModels\Advance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\ClaimsModels\Claim;
use App\Models\FinanceModels\ExchangeRate;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;

class Allocation extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['account_2013_id','account_2016_id','allocation_month','allocation_year','deleted_at','migration_account_2013_code',
                        'migration_account_2016_code','migration_allocatable_id','migration_allocated_by_id','migration_id','migration_project_id',
                        ];
    // protected $appends = ['converted_usd'];

    public function allocatable()
    {
        return $this->morphTo();
    }
    public function allocated_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','allocated_by_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\AccountingModels\Account');
    }   
    public function program_activity()
    {
        return $this->belongsTo('App\Models\ActivityModels\Activity','activity_id');
    } 
    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective','objective_id');
    }

    public function getConvertedUsdAttribute(){
        $allocatable = null;
        if($this->allocatable_type=='invoices' && !empty($this->allocatable_id)){
            $allocatable = Invoice::find($this->allocatable_id);
        }else if($this->allocatable_type=='mobile_payments' && !empty($this->allocatable_id)){
            $allocatable = MobilePayment::find($this->allocatable_id);
        }else if($this->allocatable_type=='claims' && !empty($this->allocatable_id)){
            $allocatable = Claim::find($this->allocatable_id);
        }else if($this->allocatable_type=='advances' && !empty($this->allocatable_id)){
            $allocatable = Advance::find($this->allocatable_id);
        }

        $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
        if(!empty($rate)) $rate = $rate->exchange_rate;
        else $rate = 101.72;
        if($allocatable && $allocatable->currency_id == 1){
            return (float) $this->amount_allocated/$rate;
        }
        else return $this->amount_allocated;
    }

    public function getBudgetSpentAttribute(){
        $allocatable = null;
        if($this->allocatable_type=='invoices' && !empty($this->allocatable_id)){
            $allocatable = Invoice::has('payments')->find($this->allocatable_id);
        }else if($this->allocatable_type=='mobile_payments' && !empty($this->allocatable_id)){
            $allocatable = MobilePayment::whereIn('status_id',[4,5,6,11,12,13,17])->where('id',$this->allocatable_id)->first();
        }else if($this->allocatable_type=='claims' && !empty($this->allocatable_id)){
            $allocatable = Claim::has('payments')->find($this->allocatable_id);
        }else if($this->allocatable_type=='advances' && !empty($this->allocatable_id)){
            $allocatable = Advance::has('payments')->find($this->allocatable_id);
        }

        $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
        if(!empty($rate)) $rate = $rate->exchange_rate;
        else $rate = 101.72;
        if($allocatable && $allocatable->currency_id == 1){
            return (float) $this->amount_allocated/$rate;
        }
        else return $this->amount_allocated;
    }
}
