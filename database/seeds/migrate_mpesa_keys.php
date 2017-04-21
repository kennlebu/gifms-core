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

    }
}
