<?php

use Illuminate\Database\Seeder;

class migrate_advances_keys extends Seeder
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
         *                  claims
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE claims c 
                                    LEFT JOIN staff rb 
                                    ON rb.migration_id = c.migration_requested_by_id
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = c.migration_project_manager_id
                                    LEFT JOIN projects pr 
                                    ON pr.migration_id = c.migration_project_id

                                    SET     c.requested_by_id             =   rb.id ,
                                            c.request_action_by_id        =   rb.id ,
                                            c.project_manager_id          =   pm.id ,
                                    		c.project_id    		      =   pr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated claims Foreign keys ---------- project_manager_id,  project_id  \n";










        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  advance_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE advance_approvals aa 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = aa.migration_approver_id

                                    SET     aa.approver_id    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated advance_approvals Foreign keys ---------- approver_id \n";









    }
}
