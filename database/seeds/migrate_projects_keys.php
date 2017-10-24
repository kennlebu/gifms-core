<?php

use Illuminate\Database\Seeder;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use App\Models\ProjectsModels\Project;
use App\Models\ProgramModels\ProgramManager;

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














        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Project program
         * 
         * 
         * 
         * 
         * 
         */

        $judi       =   ProgramManager::where("program_id","1")
                                    ->where("default_approver","=",1)
                                    ->first();

        $davis      =   ProgramManager::where("program_id","1")
                                    ->where(function ($query)  {
                                            $query->whereNull('default_approver')
                                                  ->orWhere("default_approver",'<>','1');
                                        })
                                    ->first();

        $rosemary   =   ProgramManager::where("program_id","2")
                                    ->first();

        $patricia   =   ProgramManager::where("program_id","3")
                                    ->first();

        $ngatia1    =   ProgramManager::where("program_id","4")
                                    ->first();

        $ngatia2    =   ProgramManager::where("program_id","5")
                                    ->first();

        $jane       =   ProgramManager::where("program_id","6")
                                    ->first();


        //global pid 
        $global_projects   = Project::where("country_id","<>",1)->get();

        foreach ($global_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   7 
                            WHERE id = '".$project['id']."'
                     ";      
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids global PIDs\n";

        // print_r($davis->program_manager_id);die;

        //HIV
        $hiv_projects   = Project::where("country_id","=",1)

                                    ->where(function ($query) use ($judi,$davis) {
                                            $query->where("project_manager_id","=",$judi->program_manager_id)
                                                  ->orWhere("project_manager_id","=",$davis->program_manager_id);
                                        })
                                    
                                    ->get();

        foreach ($hiv_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   1 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids HIV\n";



        //Essential Medicines
        $em_projects   = Project::where("country_id","=",1)
                                    ->where("project_manager_id","=",$rosemary->program_manager_id)
                                    ->get();

        foreach ($em_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   2 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids Essential Medicines\n";

        //Malaria
        $malaria_projects   = Project::where("country_id","=",1)
                                    // ->where("project_manager_id","=",$patricia->program_manager_id)
                                    ->where(function ($query) use ($patricia) {
                                            $query->where("project_manager_id","=",$patricia->program_manager_id)
                                                  ->orWhere("project_name",'LIKE','%malaria%');
                                        })
                                    ->get();

        foreach ($malaria_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   3 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids Essential Medicines\n";

        //Family Planning
        $fp_projects   = Project::where("country_id","=",1)
                                    ->where("project_manager_id","=",$ngatia1->program_manager_id)
                                    ->get();

        foreach ($fp_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   4 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids Family Planning\n";

        //Vaccines
        $vacc_projects   = Project::where("country_id","=",1)
                                    // ->where("project_manager_id","=",$ngatia2->program_manager_id)
                                    ->where(function ($query) use ($ngatia2) {
                                            $query->where("project_manager_id","=",$ngatia2->program_manager_id)
                                                  ->orWhere("project_name",'LIKE','%vaccine%');
                                        })
                                    ->get();

        foreach ($vacc_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   5 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids Vaccines\n";
        //Office Operational Costs & Overheads
        $office_projects   = Project::where("country_id","=",1)
                                    ->where("project_manager_id","=",$jane->program_manager_id)
                                    ->get();

        foreach ($office_projects as $key => $project) {

            $sql = "
                        UPDATE projects p 
                            SET     p.program_id         =   6 
                            WHERE id = '".$project['id']."'
                     ";
            DB::statement($sql);
        }
        echo "\n __________Migrated program_ids Office Operational Costs & Overheads\n";









    }
}
