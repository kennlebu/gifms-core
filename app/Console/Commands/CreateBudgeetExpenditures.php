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
                        // $budget = $project->current_budget;
                        // if(!empty($budget)){
                        //     $tracker = ExpenditureTracker::where('budget_id', $budget->id)
                        //                                 ->first();
                            
                        //     if(!empty($tracker)){
                        //         $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                        //         $tracker->disableLogging();
                        //         $tracker->save();
                        //     }
                        //     else{
                        //         $tracker = new ExpenditureTracker();
                        //         $tracker->budget_id = $budget->id;
                        //         // $tracker->account_id = $allocation->account_id;
                        //         $tracker->expenditure = $allocation->converted_usd;
                        //         $tracker->disableLogging();
                        //         $tracker->save();
                        //     }
                        // }
                        // else {
                            // If the current budget is empty and allocation year is current year,
                            // assume it is for a new budget that is yet to be uploaded.
                            if($mobile_payment->management_approval_at)
                            {
                                $payment_date = date('Y-m-d', strtotime($mobile_payment->management_approval_at));
                                $budget = Budget::whereDate('start_date', '<=', $payment_date)->whereDate('end_date', '>=', $payment_date)->first();
                            }
                            else{
                                $budget = null;
                            }
                            

                            if(!empty($budget)){

                                $tracker = ExpenditureTracker::where('budget_id', $budget->id)
                                                            ->first();
                                
                                if(!empty($tracker)){
                                    $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                                else {
                                    $tracker = new ExpenditureTracker();
                                    // $tracker->account_id = $allocation->account_id;
                                    $tracker->expenditure = $allocation->converted_usd;
                                    $tracker->budget_id = $budget->id;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                            }
                            elseif($mobile_payment->management_approval_at && date('Y') == date('Y', strtotime($mobile_payment->management_approval_at))) {
                                $new_tracker = ExpenditureTracker::where('for_new_budget', 'true')
                                                            ->first();
                                
                                if(!empty($new_tracker)){
                                    $new_tracker->expenditure = $new_tracker->expenditure += $allocation->converted_usd;
                                    $new_tracker->disableLogging();
                                    $new_tracker->save();
                                }
                                else {
                                    $new_tracker = new ExpenditureTracker();
                                    // $new_tracker->account_id = $allocation->account_id;
                                    $new_tracker->expenditure = $allocation->converted_usd;
                                    $new_tracker->for_new_budget = 'true';
                                    $new_tracker->disableLogging();
                                    $new_tracker->save();
                                }
                            }
                            else {
                                // These are old allocations that had no budgets. Let them rot.
                            }
                        // }
                        $project = null;
                    }
                }
            }
        });


        $this->info('Running payments...');
        Payment::with('payment_batch')->chunk(100, function($payments) {
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
                        // $budget = $project->current_budget;
                        // if(!empty($budget)){
                        //     $tracker = ExpenditureTracker::where('budget_id', $budget->id)
                        //                                 ->first();
                            
                        //     if(!empty($tracker)){
                        //         $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                        //         $tracker->disableLogging();
                        //         $tracker->save();
                        //     }
                        //     else{
                        //         $tracker = new ExpenditureTracker();
                        //         $tracker->budget_id = $budget->id;
                        //         $tracker->expenditure = $allocation->converted_usd;
                        //         $tracker->disableLogging();
                        //         $tracker->save();
                        //     }
                        // }
                        // else {
                            // If the current budget is empty and allocation year is current year,
                            // assume it is for a new budget that is yet to be uploaded.
                            if($payment->payment_batch){
                                $batch_date = date('Y-m-d', strtotime($payment->payment_batch->created_at));
                                $budget = Budget::whereDate('start_date', '<=', $batch_date)->whereDate('end_date', '>=', $batch_date)->first();
                            }
                            else{
                                $budget = null;
                            }

                            if(!empty($budget)){
                                $tracker = ExpenditureTracker::where('budget_id', $budget->id)
                                                            ->first();
                                
                                if(!empty($tracker)){
                                    $tracker->expenditure = $tracker->expenditure += $allocation->converted_usd;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                                else {
                                    $tracker = new ExpenditureTracker();
                                    $tracker->expenditure = $allocation->converted_usd;
                                    $tracker->budget_id = $budget->id;
                                    $tracker->disableLogging();
                                    $tracker->save();
                                }
                            }
                            elseif($payment->payment_batch && date('Y') == date('Y', strtotime($payment->payment_batch->created_at))) {
                                $new_tracker = ExpenditureTracker::where('for_new_budget', 'true')
                                                            ->first();
                                
                                if(!empty($new_tracker)){
                                    $new_tracker->expenditure = $new_tracker->expenditure += $allocation->converted_usd;
                                    $new_tracker->disableLogging();
                                    $new_tracker->save();
                                }
                                else {
                                    $new_tracker = new ExpenditureTracker();
                                    $new_tracker->expenditure = $allocation->converted_usd;
                                    $new_tracker->for_new_budget = 'true';
                                    $new_tracker->disableLogging();
                                    $new_tracker->save();
                                }
                            }
                            else {
                                // These are old allocations that had no budgets. Let them rot.
                            }
                        // }
                    }
                    $project = null;
                }
            }
        });
        $this->info('Done');
    }
}
