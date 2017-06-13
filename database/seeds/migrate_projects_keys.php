<?php

use Illuminate\Database\Seeder;

class migrate_projects_keys extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $migrate_keys_sql = "
                                UPDATE projects p 
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = p.migration_project_manager_id

                                    SET     p.project_manager_id    =   pm.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated projects Foreign keys ---------- project_manager_id \n";









        $migrate_keys_sql = "
                                UPDATE project_teams pt 
                                    LEFT JOIN staff e 
                                    ON e.migration_id = pt.migration_staff_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = pt.migration_project_id

                                    SET     pt.staff_id    	=   e.id ,
                                    		pt.project_id    	=   p.id
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated project_teams Foreign keys ---------- staff_id, project_id,\n";








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

       echo "\n __________Migrated project_budget_accounts Foreign keys ---------- account_id,project_id \n";









        $migrate_keys_sql = "
                                UPDATE project_objectives po 
                                    LEFT JOIN projects p 
                                    ON p.migration_id = po.migration_project_id

                                    SET     po.project_id               =   p.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated project_objectives Foreign keys ---------- project_id \n";







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

       echo "\n __________Migrated project_activities Foreign keys ---------- project_objective_id, project_id\n";






        $migrate_keys_sql = "
                                UPDATE project_cash_needs cn 
                                    LEFT JOIN staff emp 
                                    ON emp.migration_id = cn.migration_requested_by
                                    LEFT JOIN projects p 
                                    ON p.migration_id = cn.migration_project_id

                                    SET     cn.project_id               =   p.id ,
                                            cn.requested_by             =   emp.id
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated project_cash_needs Foreign keys ---------- project_id, requested_by\n";











    }
}
