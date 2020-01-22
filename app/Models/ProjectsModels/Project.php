<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Illuminate\Support\Facades\DB;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\AdvancesModels\Advance;
use App\Models\AccountingModels\Account;
use App\Models\ActivityModels\ActivityObjective;
use App\Models\AllocationModels\Allocation;
use App\Models\FinanceModels\Budget;
use App\Models\FinanceModels\BudgetItem;
use App\Models\FinanceModels\ExchangeRate;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\ProgramModels\ProgramManager;
use App\Models\ReportModels\ReportingObjective;

class Project extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['program_manager'];
    protected $hidden = ['client','closed_on','cluster','deleted_at','migration_id','migration_project_manager_id','qb','start_date'];
    protected $allocatables = 0;
    protected $mobile_payments = 0;

    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ProjectsModels\ProjectStatus','status_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\LookupModels\Country','country_id');
    }
    public function staffs()
    {
        return $this->belongsToMany('App\Models\StaffModels\Staff', 'project_teams', 'project_id', 'staff_id');
    }
    public function budget()
    {
        return $this->belongsTo('App\Models\FinanceModels\Budget', 'budget_id')->whereYear('end_date', date('Y'));
    }
    public function current_budget()
    {
        return $this->belongsTo('App\Models\FinanceModels\Budget', 'budget_id')->whereYear('end_date', date('Y'));
    }
    // public function grant_allocations()
    // {
    //     return $this->hasMany('App\Models\GrantModels\GrantAllocation','project_id');
    // }
    public function allocations()
    {
        return $this->hasMany('App\Models\AllocationModels\Allocation','project_id');
    }
    public function year_allocations()
    {
        return $this->hasMany('App\Models\AllocationModels\Allocation','project_id')->whereYear('created_at', '2019');
    }
    public function grant()
    {
        return $this->belongsTo('App\Models\GrantModels\Grant', 'grant_id');
    }
    public function getProgramManagerAttribute()
    {
        $pm = ProgramManager::where('program_id', $this->program_id)->first();
        return $pm->program_manager;
    }
    // public function getAmountAllocatedAttribute(){

    //     $grant_allocations     =   $this->grant_allocations;
    //     $totals    =   0;

    //     foreach ($grant_allocations as $key => $value) {
    //         $totals    +=  (float) $value->amount_allocated;
    //     }

    //     return $totals;

    // }
    // public function getGrantAmountAllocatedAttribute(){

    //     $grant_allocations     =   $this->grant_allocations;
    //     $totals    =   0;

    //     foreach ($grant_allocations as $key => $value) {
    //         $totals    +=  (float) $value->amount_allocated;
    //     }

    //     return $totals;

    // }
    public function getTotalExpenditureAttribute(){
        // $allocations     =   $this->year_allocations;
        if($this->allocatables < 1){
            $batches = PaymentBatch::whereYear('created_at', date('Y'))->pluck('id')->toArray();
            $this->allocatables = Payment::whereIn('payment_batch_id', $batches)->pluck('payable_id')->toArray();
            $allocations = Allocation::where('project_id', $this->id)->whereIn('allocatable_id', $this->allocatables)->get();
        }
        if($this->mobile_payments < 1){
            $this->mobile_payments = MobilePayment::whereYear('management_approval_at', date('Y'))->pluck('id')->toArray();
            $mp_allocations = Allocation::where('project_id', $this->id)->where('allocatable_type', 'mobile_payments')->whereIn('allocatable_id', $this->mobile_payments)->get();
        }
        $totals = 0;

        foreach ($allocations as $key => $value) {
            $allocatable = null;
            if($value->allocatable_type=='invoices' && !empty($value->allocatable_id)){
                $allocatable = Invoice::find($value->allocatable_id);
            }else if($value->allocatable_type=='claims' && !empty($value->allocatable_id)){
                $allocatable = Claim::find($value->allocatable_id);
            }else if($value->allocatable_type=='advances' && !empty($value->allocatable_id)){
                $allocatable = Advance::find($value->allocatable_id);
            }

            if($allocatable){
                $totals += (float) $value->converted_usd;
            }
        }

        foreach ($mp_allocations as $key => $value) {
            $allocatable = null;
            if(!empty($value->allocatable_id)){
                $allocatable = MobilePayment::find($value->allocatable_id);
            }

            if(!empty($allocatable)){
                $totals += (float) $value->converted_usd;
            }
        }

        return $totals;

    }

    public function getTotalExpenditurePercAttribute(){
        // if(!empty($this->budget)) $budget_amount = (int) $this->budget->totals;
        // else $budget_amount = 0;
        // $total_expenditure          = (int)  $this->getTotalExpenditureAttribute();

        // if($budget_amount!=0){
        //     return ($total_expenditure/$budget_amount)*100;
        // }else{
        //     return 0;
        // }
        return 0;

    }

    















    public function getExpenditureByAccountsDataAttribute(){

        $project_id = $this->id;
        // $qb = Allocation::when('allocatable_type', 'mobile_payments', function ($q) {
        //                     $q->whereHas('allocatable', function($query) {
        //                         $query->whereNotIn('status_id', [1,7,14,15,16]);
        //                     });
        //                 })
        //                 ->when('allocatable_type', 'invoices', function ($q) {
        //                     $q->whereHas('allocatable', function($query) {
        //                         $query->whereNotIn('status_id', [1,9,10,11,13]);
        //                     });
        //                 })
        //                 ->when('allocatable_type', 'claims', function ($q) {
        //                     $q->whereHas('allocatable', function($query) {
        //                         $query->whereNotIn('status_id', [1,9,11]);
        //                     });
        //                 })
        //                 ->when('allocatable_type', 'advances', function ($q) {
        //                     $q->whereHas('allocatable', function($query) {
        //                         $query->whereNotIn('status_id', [1,11]);
        //                     });
        //                 })
        //                 ->whereYear('created_at', date('Y'));

        // return Allocation::with('project','account')->whereYear('created_at', date('Y'))
        //                 ->where('project_id', $this->id)
        //                 ->groupBy('account_id')
        //                 ->get();

        $qb = DB::table('allocations');
        $qb->whereYear('created_at', date('Y'));
        $qb->select(DB::raw('accounts.account_name AS name, SUM(allocations.amount_allocated) AS `value`'))
                 ->leftJoin('projects', 'projects.id', '=', 'allocations.project_id')
                 ->leftJoin('accounts', 'accounts.id', '=', 'allocations.account_id')
                 ->where('projects.id', '=', $project_id)
                 ->whereNotNull('accounts.account_name')
                 ->groupBy('accounts.id')
                 ->orderBy('accounts.account_name', 'asc');



        $sql = $this->bind_presql($qb->toSql(),$qb->getBindings());
        $response = DB::select($sql);

        return $response;

    }
    















    public function getBudgetExpenditureByAccountsDataAttribute(){
        $uniqueAcc_ids = $this->uniqueArrayValues($this->year_allocations, 'account_id');
        
        // The same thing in longhand:
        // $acc_ids = array();
        // foreach ($this->allocations as $alloc) {
        //     $acc_ids[] = $alloc['account_id'];
        // }
        // $uniqueAcc_ids = array_unique($acc_ids);

        $project_id = $this->id;
        // $result     = array();
        $response   = array();

        foreach($uniqueAcc_ids as $aid){
            $result = [];
            $account = Account::find($aid);
            $result['account_code'] = empty($account->code) ? '' : $account->account_code;
            $result['account_id'] = empty($account->id) ? '' : $account->id;
            $result['account_name'] = empty($account->id) ? '' : $account->account_name;
            $result['account_code'] = empty($account->id) ? '' : $account->account_code;

            // $budget_items = empty($this->budget->items) ? [] : $this->pluck($this->budget->items, 'account_id', $aid);
            $acc_expenditures = $this->pluck($this->allocations, 'account_id', $aid);
            // $expenditures = $this->pluck($acc_expenditures, 'project_id', $project_id);
            // $result['total_expenditure'] = $this->sumArrayColumn($expenditures, 'account_id');

            // $allocations = $this->allocations;
            $allocation_total = 0;

            foreach ($acc_expenditures as $ex) {
                $allocatable = null;
                if($ex->allocatable_type=='invoices' && !empty($ex->allocatable_id)){
                    $allocatable = Invoice::has('payments')->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='mobile_payments' && !empty($ex->allocatable_id)){
                    $allocatable = MobilePayment::whereIn('status_id',[4,5,6,11,12,13,17])->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='claims' && !empty($ex->allocatable_id)){
                    $allocatable = Claim::has('payments')->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='advances' && !empty($ex->allocatable_id)){
                    $allocatable = Advance::has('payments')->where('id', $ex->allocatable_id)->first();
                }

                if($allocatable && $allocatable->currency_id){
                    // if($allocatable->currency_id == 2){  
                    //     $allocation_total += (float) $ex->converted_usd;
                    // }
                    // else if($ex->allocatable->currency_id == 1){
                    //     // $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
                    //     // // $rate = DB::select("select * from exchange_rates
                    //     // //     where ((DATE_ADD('".date('Y-m-d')."', INTERVAL 1 DAY) between 
                    //     // //     cast(active_date as date) and cast(end_date as date)
                    //     // //     or DATE_SUB('".date('Y-m-d')."', INTERVAL 1 DAY) between
                    //     // //     cast(active_date as date) and cast(end_date as date))");
                    //     // if(!empty($rate)) $rate = $rate->exchange_rate;
                    //     // else $rate = 101.72;

                    //     // $totals += (float) ($value->amount_allocated/$rate);
                    // }
                    $allocation_total += (float) $ex->converted_usd;
                }
            }
            $result['total_expenditure'] = $allocation_total;
            
            // $result['total_budget'] = $this->sumArrayColumn($budget_items, 'amount');
            $result['total_budget'] = $this->budget->items->where('account_id', $aid)->sum('amount');

            $response[] = $result;
        }



        // $qb_expenditure = DB::table('accounts');

        // $qb_expenditure->select(DB::raw('accounts.id AS account_id, accounts.account_name, accounts.account_code, allocations.id AS allocation_id, SUM(allocations.amount_allocated) AS `total_expenditure`, null AS budget_item_id,null AS `total_budget`'))
        //          ->leftJoin('allocations', 'allocations.account_id', '=', 'accounts.id')
        //          ->rightJoin('projects', function($q) use ($project_id)
        //             {
        //                 $q->on('projects.id', '=', 'allocations.project_id')
        //                  ->where('projects.id', '=', $project_id);
        //             })
        //          ->whereNotNull('accounts.account_name')
        //          ->groupBy('accounts.id')
        //          ->whereNull('accounts.deleted_at')
        //          ->orderBy('accounts.account_name', 'asc');



        // $sql_expenditure = $this->bind_presql($qb_expenditure->toSql(),$qb_expenditure->getBindings());
        // $sql_expenditure = "select accounts.id as account_id ,accounts.account_name, accounts.account_code, f.allocation_id, f.total_expenditure, f.budget_item_id, f.total_budget FROM accounts Left join ($sql_expenditure) as f on f.account_id = accounts.id where `accounts`.`account_name` is not null and `accounts`.`deleted_at` is null group by `accounts`.`id` order by `accounts`.`account_name` asc";
        // $result_expenditure = DB::select($sql_expenditure);










        // $qb_budget = DB::table('accounts');

        // $qb_budget->select(DB::raw('accounts.id AS account_id, accounts.account_name,  accounts.account_code, null AS allocation_id, null AS `total_expenditure`,  budget_items.id AS budget_item_id,SUM(budget_items.amount) AS `total_budget`'))
        //          ->leftJoin('budget_items', function($q) 
        //             {
        //                 $q->on('budget_items.account_id', '=', 'accounts.id');
        //             })
        //          ->leftJoin('budgets', function($q) 
        //             {
        //                 $q->on('budgets.id', '=', 'budget_items.budget_id');
        //             })
        //          ->rightJoin('projects', function($q)  use ($project_id)
        //             {
        //                 $q->on('projects.budget_id', '=', 'budgets.id')
        //                  ->where('projects.id', '=', $project_id);
        //             })
        //          ->whereNotNull('accounts.account_name')
        //          ->groupBy('accounts.id')
        //          ->whereNull('accounts.deleted_at')
        //          ->orderBy('accounts.account_name', 'asc');



        // $sql_budget = $this->bind_presql($qb_budget->toSql(),$qb_budget->getBindings());
        // $result_budget = DB::select($sql_budget);




        // foreach ($result_expenditure as $key => $value) {

        //     $result[$key]["account_id"]         =    $value["account_id"];
        //     $result[$key]["account_code"]       =    $value["account_code"];
        //     $result[$key]["account_name"]       =    $value["account_name"];
        //     $result[$key]["allocation_id"]      =    $value["allocation_id"];
        //     $result[$key]["total_expenditure"]  =    $value["total_expenditure"]; 
            
        //     $result[$key]["budget_item_id"]     =    0;
        //     $result[$key]["total_budget"]       =    0; 

        //     foreach ($result_budget as $key1 => $value1) {
        //         if ($value["account_id"]==$value1["account_id"]) {
        //             $result[$key]["budget_item_id"]     =    $result_budget[$key1]["budget_item_id"];
        //             $result[$key]["total_budget"]       =    $result_budget[$key1]["total_budget"];                    
        //         }else{

        //         }
        //     }
        // }

        // foreach ($result as $key => $value) {
        //     if(!empty($value["total_expenditure"])||!empty($value["total_budget"])){
        //         $response[] = $value;
        //     }
        // }

        // foreach ($response as $key => $value) {
        //     if(is_null($value['total_expenditure'])){
        //         $response[$key]['total_expenditure'] = 0;
        //     }
        //     if(is_null($value['total_budget'])){
        //         $response[$key]['total_budget'] = 0;
        //     }
        // }


        return $response;

    }

    public function getBudgetExpenditureByObjectivesDataAttribute(){
        $uniqueOids = $this->uniqueArrayValues($this->year_allocations, 'objective_id');
        $response = [];
        foreach($uniqueOids as $oid){
            $result = [];
            $objective = ReportingObjective::find($oid);
            $result['objective'] = $objective->objective ?? '';

            $obj_expenditures = $this->pluck($this->year_allocations, 'objective_id', $oid);

            $allocation_total = 0;
            foreach ($obj_expenditures as $ex) {
                if($ex->allocatable && $ex->allocatable->currency_id){
                    if($ex->allocatable->currency_id == 2){  
                        $allocation_total += (float) $ex->amount_allocated;
                    }
                    else if($ex->allocatable->currency_id == 1){
                        $allocation_total += (float) ($ex->amount_allocated/100);
                    }
                }$allocatable = null;
                if($ex->allocatable_type=='invoices' && !empty($ex->allocatable_id)){
                    $allocatable = Invoice::has('payments')->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='mobile_payments' && !empty($ex->allocatable_id)){
                    $allocatable = MobilePayment::whereIn('status_id',[4,5,6,11,12,13,17])->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='claims' && !empty($ex->allocatable_id)){
                    $allocatable = Claim::has('payments')->where('id', $ex->allocatable_id)->first();
                }else if($ex->allocatable_type=='advances' && !empty($ex->allocatable_id)){
                    $allocatable = Advance::has('payments')->where('id', $ex->allocatable_id)->first();
                }

                if($allocatable && $allocatable->currency_id){
                //     if($allocatable->currency_id == 2){  
                //         $allocation_total += (float) $ex->converted_usd;
                //     }
                //     else if($ex->allocatable->currency_id == 1){
                //         // $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
                //         // // $rate = DB::select("select * from exchange_rates
                //         // //     where ((DATE_ADD('".date('Y-m-d')."', INTERVAL 1 DAY) between 
                //         // //     cast(active_date as date) and cast(end_date as date)
                //         // //     or DATE_SUB('".date('Y-m-d')."', INTERVAL 1 DAY) between
                //         // //     cast(active_date as date) and cast(end_date as date))");
                //         // if(!empty($rate)) $rate = $rate->exchange_rate;
                //         // else $rate = 101.72;

                //         // $totals += (float) ($value->amount_allocated/$rate);
                //         $allocation_total += (float) $ex->converted_usd;
                //     }
                $allocation_total += (float) $ex->converted_usd;
                }
            }
            $result['total_expenditure'] = $allocation_total;
            $result['total_budget'] = BudgetItem::where('objective_id', $oid)->sum('amount');

            if(!empty($result['objective']))
                $response[] = $result;
        }
        return $response;        
    }
}
