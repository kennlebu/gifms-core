<?php

use Illuminate\Database\Seeder;

class migrate_claims_keys extends Seeder
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
         *                  advances
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE advances c 
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

       echo "\n __________Migrated advances Foreign keys ---------- project_manager_id,  project_id  \n";









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  claim_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE claim_approvals c 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = c.migration_approver_id

                                    SET     c.approver_id    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated claim_approvals Foreign keys ---------- approver_id \n";








    }
}
