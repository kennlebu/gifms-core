<?php

use Illuminate\Database\Seeder;

class migrate_mpesa_keys extends Seeder
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
         *                  mpesa_payments
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mpesa_payments mp 
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = mp.migration_project_manager
                                    LEFT JOIN invoices inv 
                                    ON inv.migration_id = mp.migration_invoice_id
                                    LEFT JOIN accounts acc 
                                    ON acc.migration_id = mp.migration_account_id
                                    LEFT JOIN projects pr 
                                    ON pr.migration_id = mp.migration_project_id

                                    SET     mp.project_manager    	=   pm.id ,
                                    		mp.invoice_id    		=   inv.id ,
                                    		mp.account_id    		=   acc.id ,
                                    		mp.project_id    		=   pr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mpesa_payments Foreign keys ---------- project_manager, invoice_id, account_id,  project_id  \n";













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_payment_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mpesa_payment_approvals l 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = l.migration_approver

                                    SET     l.approver    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mpesa_payment_approvals Foreign keys ---------- approver \n";











        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_payees
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mpesa_payees mp 
                                    LEFT JOIN mpesa_payments a 
                                    ON a.migration_id = mp.migration_mpesa_payment_id

                                    SET     mp.mpesa_payment_id    =   a.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mpesa_payees Foreign keys ---------- mpesa_payment_id \n";









         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_payment_statuses
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                 UPDATE mpesa_payment_statuses mps
                                      LEFT JOIN security_levels sl
                                      ON mps.migration_status_security_level = sl.migration_id
                                      SET mps.status_security_level = sl.id
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mpesa_payment_statuses Foreign keys ---------- security_level \n";













         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mpesa_viewing_permissions mvp 
                                    LEFT JOIN security_levels sl 
                                    ON mvp.migration_security_level = sl.migration_id
                                    SET mvp.security_level = sl.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mpesa_viewing_permissions Foreign keys ---------- security_level \n";











    }
}
