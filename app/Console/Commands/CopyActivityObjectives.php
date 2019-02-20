<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use App\Models\ActivityModels\Activity;
use App\Models\ActivityModels\ActivityObjective;

class CopyActivityObjectives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:objectives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy activity objectives from old format';

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

        $activities = Activity::whereNotNull('objective_id')->get();
        foreach($activities as $activity){
            echo "\nCopying ".$activity->title>"..........";
            $activity_objective = new ActivityObjective;
            $activity_objective->activity_id = $activity->id;
            $activity_objective->objective_id = $activity->objective_id;
            $activity_objective->disableLogging();
            $activity_objective->save();
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";

        $this->info("Done!");
    }

}
