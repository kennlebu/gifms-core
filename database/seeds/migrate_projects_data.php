<?php

use Illuminate\Database\Seeder;

class migrate_projects_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



    	/**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					PROJECTS
	     * 
	     * 
	     * 
	     * 
	     * 
	     */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Projects')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['project_code']  				= $data[$key]['ProjectID'];
            $data_to_migrate[$key]['project_name']  				= $data[$key]['ProjectName'];
            $data_to_migrate[$key]['project_desc']  				= $data[$key]['ProjectDescription'];
            $data_to_migrate[$key]['start_date']  					= $data[$key]['StartDate'];
            $data_to_migrate[$key]['end_date']  					= $data[$key]['EndDate'];
            $data_to_migrate[$key]['status']  						= $data[$key]['Status'];
            $data_to_migrate[$key]['cluster']  						= $data[$key]['Cluster'];
            $data_to_migrate[$key]['client']  						= $data[$key]['Client'];
            $data_to_migrate[$key]['country_id']  				    = $data[$key]['ProjectCountry'];
            $data_to_migrate[$key]['qb']  							= $data[$key]['QB'];
            $data_to_migrate[$key]['migration_project_manager_id']  = $data[$key]['ProjectManager'];
            $data_to_migrate[$key]['migration_id']					= $data[$key]['ID'];


            echo "\n Projects-$key---";
            echo $data[$key]['ProjectID'];
        }
        
        DB::table('projects')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";













        /**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					ProjectTeam
	     * 
	     * 
	     * 
	     * 
	     * 
	     */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectTeam')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_project_id']  				= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_staff_id']  				    = $data[$key]['Employee'];
            $data_to_migrate[$key]['migration_id']							= $data[$key]['ID'];


            echo "\n Project Team -$key---";
            echo $data[$key]['Project'];
        }
        
        DB::table('project_teams')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";















        /**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					ProjectBudgetAccounts
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectBudgetAccounts')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['budget_amount']  				= $data[$key]['BudgetAmount'];
            $data_to_migrate[$key]['budget_month']  				= $data[$key]['BudgetMonth'];
            $data_to_migrate[$key]['budget_year']  					= $data[$key]['BudgetYear'];
            $data_to_migrate[$key]['funding_status']  				= $data[$key]['FundingStatus'];
            $data_to_migrate[$key]['current_forecast']  			= $data[$key]['CurrentForecast'];
            $data_to_migrate[$key]['reforcast_month']  				= $data[$key]['ReforecastMonth'];
            $data_to_migrate[$key]['migration_project_id']  		= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_account_id']  		= $data[$key]['Account'];
            $data_to_migrate[$key]['migration_id']					= $data[$key]['ID'];


            echo "\n ProjectBudgetAccounts -$key---";
            echo $data[$key]['BudgetAmount'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('project_budget_accounts')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }


        echo "\n-----------------------------------------------------------------------------------------------------\n";











        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  ProjectObjectives
         * 
         * 
         * 
         * 
         * 
         */




        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectObjectives')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['objective_desc']                        = $data[$key]['Objective'];
            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n ProjectObjectives -$key---";
            echo $data[$key]['Activity'];
        }
        
        DB::table('project_objectives')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  ProjectActivities
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectActivities')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_project_objective_id']        = $data[$key]['Objective'];
            $data_to_migrate[$key]['activity_desc']                         = $data[$key]['Activity'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n ProjectActivities -$key---";
            echo $data[$key]['Activity'];
        }
        
        DB::table('project_activities')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Project Cash Needs
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectCashNeeds')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['activity']                              = $data[$key]['Activity'];
            $data_to_migrate[$key]['amount']                                = $data[$key]['Amount'];
            $data_to_migrate[$key]['month']                                 = $data[$key]['RequestMonth'];
            $data_to_migrate[$key]['year']                                  = $data[$key]['RequestYear'];
            $data_to_migrate[$key]['cash_request_purpose']                  = $data[$key]['RequestCategory'];
            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_requested_by']                = $data[$key]['RequestedBy'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n ProjectCashNeeds -$key---";
            echo $data[$key]['Activity'];
        }
        
        DB::table('project_cash_needs')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  ProjectMasterList
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectMasterList')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['Description']                = $data[$key]['ProjectDescription'];
            $data_to_migrate[$key]['program']                    = $data[$key]['Program'];
            $data_to_migrate[$key]['strategic_group']            = $data[$key]['StrategicGroup'];
            $data_to_migrate[$key]['focus_area']                 = $data[$key]['FocusArea'];
            $data_to_migrate[$key]['region']                     = $data[$key]['Region'];
            $data_to_migrate[$key]['global_association']         = $data[$key]['GlobalAssociation'];
            $data_to_migrate[$key]['status']                     = $data[$key]['Status'];
            $data_to_migrate[$key]['start_date']                 = $data[$key]['StartDate'];
            $data_to_migrate[$key]['migration_id']               = $data[$key]['ProjectID'];


            echo "\n ProjectMasterList -$key---";
            echo $data[$key]['ProjectDescription'];
        }
        
        DB::table('project_master_lists')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";




    }
}
