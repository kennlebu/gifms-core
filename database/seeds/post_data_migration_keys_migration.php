<?php

use Illuminate\Database\Seeder;

class post_data_migration_keys_migration extends Seeder
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
	     *					PROJECTS
	     * 
	     * 
	     * 
	     * 
	     * 
	     */

        $migrate_keys_sql = "
                                UPDATE projects p 
                                    LEFT JOIN employees pm 
                                    ON pm.migration_id = p.migration_project_manager_id

                                    SET     p.project_manager_id    =   pm.id 
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________POST-Migrated Project  keys___________";
        echo "\n-----------------------------------------------------------------------------------------------------\n";












        /**
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
	     */




        $migrate_keys_sql = "
                                UPDATE project_teams pt 
                                    LEFT JOIN employees e 
                                    ON e.migration_id = pt.migration_employee_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pt.migration_project_id

                                    SET     pt.employee_id      =   e.id ,
                                            pt.project_id       =   p.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________POST-Migrated Project Teams  keys___________";
        echo "\n-----------------------------------------------------------------------------------------------------\n";












        /**
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
	     */




        $migrate_keys_sql = "
                                UPDATE project_budget_accounts pba 
                                    LEFT JOIN accounts a 
                                    ON a.migration_id = pba.migration_account_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pba.migration_project_id

                                    SET     pba.account_id      =   a.id ,
                                            pba.project_id      =   p.id
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
         *                  ProjectObjectives
         * 
         * 
         * 
         * 
         */


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
	     *					ProjectActivities
	     * 
	     * 
	     * 
	     * 
	     */



        $migrate_keys_sql = "
                                UPDATE project_activities pt 
                                    LEFT JOIN project_objectives po 
                                    ON po.migration_id = pt.migration_project_objective_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pt.migration_project_id

                                    SET     pt.project_id    			=   po.id ,
                                    		pt.project_objective_id    	=   p.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated ProjectActivities  keys___________";
        echo "\n-----------------------------------------------------------------------------------------------------\n";











    }
}
