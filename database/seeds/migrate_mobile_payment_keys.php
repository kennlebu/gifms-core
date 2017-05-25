<?php

use Illuminate\Database\Seeder;

class migrate_mobile_payment_keys extends Seeder
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
         *                  mobile_payments
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mobile_payments mp 
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = mp.migration_project_manager_id
                                    LEFT JOIN invoices inv 
                                    ON inv.migration_id = mp.migration_invoice_id
                                    LEFT JOIN accounts acc 
                                    ON acc.migration_id = mp.migration_account_id
                                    LEFT JOIN projects pr 
                                    ON pr.migration_id = mp.migration_project_id

                                    SET     mp.project_manager_id   =   pm.id ,
                                    		mp.invoice_id    		=   inv.id ,
                                    		mp.account_id    		=   acc.id ,
                                    		mp.project_id    		=   pr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mobile_payments Foreign keys ---------- project_manager_id, invoice_id, account_id,  project_id  \n";













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mobile_payment_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mobile_payment_approvals l 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = l.migration_approver_id

                                    SET     l.approver_id    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mobile_payment_approvals Foreign keys ---------- approver_id \n";











        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mobile_payees
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE mobile_payment_payees mp 
                                    LEFT JOIN mpesa_payments a 
                                    ON a.migration_id = mp.migration_mobile_payment_id

                                    SET     mp.mobile_payment_id    =   a.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mobile_payment_payees Foreign keys ---------- mobile_payment_id \n";









         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mobile_payment_statuses
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                 UPDATE mobile_payment_statuses mps
                                      LEFT JOIN security_levels sl
                                      ON mps.migration_status_security_level = sl.migration_id
                                      SET mps.status_security_level = sl.id
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mobile_payment_statuses Foreign keys ---------- security_level \n";













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
                                UPDATE mobile_payment_viewing_permissions mvp 
                                    LEFT JOIN security_levels sl 
                                    ON mvp.migration_security_level = sl.migration_id
                                    SET mvp.security_level = sl.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated mobile_payment_viewing_permissions Foreign keys ---------- security_level \n";











    }
}
