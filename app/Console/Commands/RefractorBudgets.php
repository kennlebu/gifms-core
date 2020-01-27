<?php

namespace App\Console\Commands;

use App\Models\FinanceModels\Budget;
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
    protected $description = 'Move PIDs to budgets';

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
        $projects = Project::all();
        foreach($projects as $project){
            if(!empty($project->budget_id)){
                $budget = Budget::find($project->budget_id);
                if($budget) {
                    $budget->project_id = $project->id;
                    $budget->disableLogging();
                    $budget->save();
                }
            }
        }

        $this->info("Done");
    }
}
