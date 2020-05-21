<?php

namespace App\Console\Commands;

use App\Models\FinanceModels\BudgetObjective;
use App\Models\FinanceModels\Budget;
use App\Models\FinanceModels\BudgetAccount;
use App\Models\ProjectsModels\Project;
use Illuminate\Console\Command;

class RefractorBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refractor:budgets';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Move PIDs to budgets';
    protected $description = 'Create budget objectives and accounts';

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
        $budgets = Budget::all();
        foreach($budgets as $budget){
            $budget_objective = new BudgetObjective();
            $budget_objective->budget_id = $budget->id;
            $budget_objective->objective_id = -1;
            $budget_objective->objective_name = (!empty($budget->project) ? $budget->project->project_code ?? '' : '').' '. (!empty($budget->project) ? $budget->project->project_name ?? '' : ''). ' Objective 1';
            $budget_objective->disableLogging();
            $budget_objective->save();

            if(!empty($budget->items)){
                foreach($budget->items as $item){
                    $budget_account = new BudgetAccount();
                    $budget_account->budget_objective_id = $budget_objective->id;
                    $budget_account->account_id = $item->account_id;
                    $budget_account->amount = $item->amount;
                    $budget_account->created_by_id = $budget->created_by_id;
                    $budget_account->disableLogging();
                    $budget_account->save();
                }
            }
        }

        $this->info("Done");
    }
}
