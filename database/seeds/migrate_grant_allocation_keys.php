<?php

use Illuminate\Database\Seeder;

class migrate_grant_allocation_keys extends Seeder
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
         *                  grant_allocations
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE grant_allocations ga 
                                    LEFT JOIN grants g 
                                    ON g.migration_id = ga.migration_grant_id
                                    LEFT JOIN projects pr 
                                    ON pr.migration_id = ga.migration_project_id

                                    SET     ga.allocated_by_id             =   pr.project_manager_id ,
                                            ga.allocate_action_by_id       =   pr.project_manager_id ,
                                            ga.grant_id                    =   g.id ,
                                            ga.project_id                  =   pr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated grant_allocations Foreign keys ---------- grant_id,  project_id, allocated_by_id, allocate_action_by_id  \n";










        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  grant_allocations
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE grant_allocations ga 
                                    LEFT JOIN grants g
                                    ON g.id = ga.grant_id
                                    SET     ga.percentage_allocated = ((ga.amount_allocated/g.grant_amount)* 100)
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated grant_allocations Foreign keys ---------- percentage_allocated  \n";







        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  projects
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                            UPDATE projects p
                                LEFT JOIN grant_allocations ga
                                ON ga.project_id = p.id                                
                                SET p.grant_id = ga.grant_id
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated projects Foreign keys ---------- grant_id  \n";




    }
}
