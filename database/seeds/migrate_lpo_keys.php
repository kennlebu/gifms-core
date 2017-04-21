<?php

use Illuminate\Database\Seeder;

class migrate_lpo_keys extends Seeder
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
         *                  lpos
         * 
         * 
         * 
         * 
         * 
         */
        $migrate_keys_sql = "
                                UPDATE lpos l 
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = l.migration_project_manager_id
                                    LEFT JOIN staff rcb 
                                    ON rcb.migration_id = l.migration_received_by
                                    LEFT JOIN staff rq 
                                    ON rq.migration_id = l.migration_requested_by
                                    LEFT JOIN projects p 
                                    ON p.migration_id = l.migration_project_id
                                    LEFT JOIN suppliers s 
                                    ON s.migration_id = l.migration_supplier_id
                                    LEFT JOIN accounts a 
                                    ON a.migration_id = l.migration_account_id

                                    SET     l.project_manager_id    =   pm.id ,
                                            l.received_by           =   rcb.id,
                                            l.requested_by          =   rq.id,
                                            l.project_id            =   p.id,
                                            l.supplier_id           =   s.id,
                                            l.account_id            =   a.id
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpos Foreign keys ----------project_manager_id, received_by, requested_by, project_id, supplier_id, account_id    \n";



 		/**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE lpo_approvals l 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = l.migration_approver

                                    SET     l.approver    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_approvals Foreign keys ---------- approver \n";

   


 		/**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_items
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE lpo_items i 
                                    JOIN lpos l 
                                    ON i.lpo_migration_id = l.migration_id
                                    SET i.lpo_id = l.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_items Foreign keys ---------- lpos \n";

    




 		/**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_quotations
         * 
         * 
         * 
         * 
         * 
         */



        $migrate_keys_sql = "
                                UPDATE lpo_quotations i 
                                    JOIN lpos l 
                                    ON i.lpo_migration_id = l.migration_id
                                    SET i.lpo_id = l.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_quotations Foreign keys ---------- lpo_id \n";


         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_terms
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE lpo_terms t 
                                    JOIN lpos l 
                                    ON t.lpo_migration_id = l.migration_id
                                    SET t.lpo_id = l.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_terms Foreign keys ---------- lpo_id \n";






         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_statuses
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE lpo_statuses s 
                                    LEFT JOIN security_levels sl 
                                    ON s.migration_status_security_level = sl.migration_id
                                    SET s.status_security_level = sl.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_statuses Foreign keys ---------- status_security_level \n";







         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  lpo_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE lpo_viewing_permissions lvp 
                                    LEFT JOIN security_levels sl 
                                    ON lvp.migration_security_level = sl.migration_id
                                    SET lvp.security_level = sl.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated lpo_viewing_permissions Foreign keys ---------- security_level \n";











    }
}
