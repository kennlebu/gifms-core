<?php

namespace App\Console\Commands;

use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\FinanceModels\Budget;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\PaymentModels\Payment;
use App\Models\ProjectsModels\ExpenditureTracker;
use App\Models\ProjectsModels\Project;
use Illuminate\Console\Command;

class CreateBudgeetExpenditures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:expenditures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates expenditures for the different PIDs according to their budgets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Running mobile payments...');
        MobilePayment::whereNotNull('management_approval_at')->chunk(100, function($mobile_payments) {
            foreach($mobile_payments as $mobile_payment){
                foreach($mobile_payment->allocations as $allocation){
                    $project = Project::find($allocation->project_id);
                    if(!empty($project)){
                        $budget = $project->current_budget;
                        if(!empty($budget)){
                            $tracker = ExpenditureTracker::where('account_id', $allocation->account_id)
                                                        ->where('budget_id', $budget->id)
                                                        ->first();
                            
                            if(!empty($tracker)){
                                $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                            }
                            else{
                                $tracker = new ExpenditureTracker();
                                $tracker->budget_id = $budget->id;
                                $tracker->account_id = $allocation->account_id;
                                $tracker->expenditure = $allocation->converted_usd;
                                $tracker->disableLogging();
                                $tracker->save();
                            }
                        }
                        else {
                            // If the current budget is empty and allocation year is current year,
                            // assume it is for a new budget that is yet to be uploaded.
                            $allocation_date = date('Y-m-d', strtotime($allocation->created_at));
                            $budget = Budget::whereDate('start_date', '<=', $allocation_date)->whereDate('end_date', '>=', $allocation_date)->first();

                            if(!empty($budget)){
                                $tracker = ExpenditureTracker::where('account_id', $allocation->account_id)
                                                            ->where('budget_id', $budget->id)
                                                            ->first();
                                
                                if(!empty($tracker)){
                                    $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                                else {
                                    $tracker = new ExpenditureTracker();
                                    $tracker->account_id = $allocation->account_id;
                                    $tracker->expenditure = $allocation->converted_usd;
                                    $tracker->budget_id = $budget->id;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                            }
                            elseif(date('Y') == date('Y', strtotime($allocation->created_at))) {
                                $tracker = new ExpenditureTracker();
                                $tracker->account_id = $allocation->account_id;
                                $tracker->expenditure = $allocation->converted_usd;
                                $tracker->for_new_budget = 'true';
                                $tracker->disableLogging();
                                $tracker->save();
                            }
                            else {
                                // These are old allocations that had no budgets. Let them rot.
                            }
                        }
                        $project = null;
                    }
                }
            }
        });


        $this->info('Running payments...');
        Payment::chunk(100, function($payments) {
            foreach($payments as $payment){
                $allocations = [];
                if($payment->payable_id && $payment->payable_type == 'invoices'){
                    $invoice = Invoice::find($payment->payable_id);
                    if(!empty($invoice)){
                        $allocations = $invoice->allocations;
                    }
                }
                elseif($payment->payable_id && $payment->payable_type == 'claims'){
                    $claim = Claim::find($payment->payable_id);
                    if(!empty($claim)){
                        $allocations = $claim->allocations;
                    }
                }
                elseif($payment->payable_id && $payment->payable_type == 'advances'){
                    $advance = Advance::find($payment->payable_id);
                    if(!empty($advance)){
                        $allocations = $advance->allocations;
                    }
                }

                foreach($allocations as $allocation){
                    $project = Project::find($allocation->project_id);
                    if(!empty($project)){
                        $budget = $project->current_budget;
                        if(!empty($budget)){
                            $tracker = ExpenditureTracker::where('account_id', $allocation->account_id)
                                                        ->where('budget_id', $budget->id)
                                                        ->first();
                            
                            if(!empty($tracker)){
                                $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                                $tracker->disableLogging();
                                $tracker->save();
                            }
                            else{
                                $tracker = new ExpenditureTracker();
                                $tracker->budget_id = $budget->id;
                                $tracker->account_id = $allocation->account_id;
                                $tracker->expenditure = $allocation->converted_usd;
                                $tracker->disableLogging();
                                $tracker->save();
                            }
                        }
                        else {
                            // If the current budget is empty and allocation year is current year,
                            // assume it is for a new budget that is yet to be uploaded.
                            $allocation_date = date('Y-m-d', strtotime($allocation->created_at));
                            $budget = Budget::whereDate('start_date', '<=', $allocation_date)->whereDate('end_date', '>=', $allocation_date)->first();

                            if(!empty($budget)){
                                $tracker = ExpenditureTracker::where('account_id', $allocation->account_id)
                                                            ->where('budget_id', $budget->id)
                                                            ->first();
                                
                                if(!empty($tracker)){
                                    $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                                else {
                                    $tracker = new ExpenditureTracker();
                                    $tracker->account_id = $allocation->account_id;
                                    $tracker->expenditure = $allocation->converted_usd;
                                    $tracker->budget_id = $budget->id;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                            }
                            elseif(date('Y') == date('Y', strtotime($allocation->created_at))) {
                                $tracker = new ExpenditureTracker();
                                $tracker->account_id = $allocation->account_id;
                                $tracker->expenditure = $allocation->converted_usd;
                                $tracker->for_new_budget = 'true';
                                $tracker->disableLogging();
                                $tracker->save();
                            }
                            else {
                                // These are old allocations that had no budgets. Let them rot.
                            }
                        }
                    }
                    $project = null;
                }
            }
        });
        $this->info('Done');
    }
}
