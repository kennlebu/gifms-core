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




        // move projects from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

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


            echo "\n Projects---";
            echo $data[$key]['ProjectID'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('projects')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE projects p 
                                    LEFT JOIN employees pm 
                                    ON pm.migration_id = p.migration_project_manager_id

                                    SET     p.project_manager_id    =   pm.id 
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated Project  keys___________";
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

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectTeam')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_project_id']  				= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_employee_id']  				= $data[$key]['Employee'];
            $data_to_migrate[$key]['migration_id']							= $data[$key]['ID'];


            echo "\n Project Team ---";
            echo $data[$key]['Project'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('project_teams')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE project_teams pt 
                                    LEFT JOIN employees e 
                                    ON e.migration_id = pt.migration_employee_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pt.migration_project_id

                                    SET     pt.employee_id    	=   e.id ,
                                    		pt.project_id    	=   p.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated Project Team  keys___________";
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

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

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


            echo "\n ProjectBudgetAccounts ---";
            echo $data[$key]['BudgetAmount'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('project_budget_accounts')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE project_budget_accounts pba 
                                    LEFT JOIN accounts a 
                                    ON a.migration_id = pba.migration_account_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pba.migration_project_id

                                    SET     pba.account_id    	=   a.id ,
                                    		pba.project_id    	=   p.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated ProjectBudgetAccounts  keys___________";
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



        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectObjectives')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['objective_desc']                        = $data[$key]['Objective'];
            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n ProjectObjectives ---";
            echo $data[$key]['Activity'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('project_objectives')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE project_objectives po 
                                    LEFT JOIN projects p 
                                    ON p.migration_id = po.migration_project_id

                                    SET     po.project_id               =   p.id 
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated ProjectObjectives  keys___________";
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

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ProjectActivities')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_project_objective_id']        = $data[$key]['Objective'];
            $data_to_migrate[$key]['activity_desc']                         = $data[$key]['Activity'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n ProjectActivities ---";
            echo $data[$key]['Activity'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('project_activities')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE project_activities pt 
                                    LEFT JOIN project_objectives po 
                                    ON po.migration_id = pt.migration_project_objective_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pt.migration_project_id

                                    SET     pt.project_id               =   po.id ,
                                            pt.project_objective_id     =   p.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated ProjectActivities  keys___________";
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

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

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


            echo "\n ProjectCashNeeds ---";
            echo $data[$key]['Activity'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('project_cash_needs')->insert($data_to_migrate);




        $migrate_keys_sql = "
                                UPDATE project_cash_needs cn 
                                    LEFT JOIN employees emp 
                                    ON emp.migration_id = cn.migration_requested_by
                                    LEFT JOIN projects p 
                                    ON p.migration_id = cn.migration_project_id

                                    SET     cn.project_id               =   p.id ,
                                            cn.requested_by             =   emp.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated ProjectCashNeeds  keys___________";
        echo "\n-----------------------------------------------------------------------------------------------------\n";




    }
}
