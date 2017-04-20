<?php

use Illuminate\Database\Seeder;

class migrate_invoice_keys extends Seeder
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
         *                  Invoices
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE invoices i 
                                    LEFT JOIN staff ma 
                                    ON ma.migration_id = i.migration_management_approval
                                    LEFT JOIN staff ub 
                                    ON ub.migration_id = i.migration_uploaded_by
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = i.migration_approver_id
                                    LEFT JOIN claims c 
                                    ON c.migration_id = i.migration_claim_id
                                    LEFT JOIN lpos l 
                                    ON l.migration_id = i.migration_lpo_id
                                    LEFT JOIN staff_advances sa 
                                    ON sa.migration_id = i.migration_advance_id
                                    LEFT JOIN staff mp 
                                    ON mp.migration_id = i.migration_mpesa_id

                                    SET     i.management_approval   =   ma.id, 
                                            i.uploaded_by           =   ub.id, 
                                            i.approver_id           =   ap.id, 
                                            i.claim_id              =   c.id, 
                                            i.lpo_id                =   l.id, 
                                            i.advance_id            =   sa.id, 
                                            i.mpesa_id              =   mp.id, 
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated invoices Foreign keys ---------- banks,bank_branches \n";

      /**
         * 
         * 
         * 
         * 
         * 
         *                  invoice_project_account_allocations
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE invoice_project_account_allocations ipaa 
                                    LEFT JOIN staff ab 
                                    ON ab.migration_id = ipaa.migration_allocated_by
                                    LEFT JOIN invoices i 
                                    ON i.migration_id = ipaa.migration_invoice_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = ipaa.migration_project_id
                                    LEFT JOIN accounts a 
                                    ON a.account_code = ipaa.migration_project_account
                                    LEFT JOIN accounts a16 
                                    ON a16.account_code = ipaa.migration_project_account_2016

                                    SET     ipaa.allocated_by              =   ab.id, 
                                            ipaa.invoice_id                =   i.id, 
                                            ipaa.project_id                =   p.id, 
                                            ipaa.project_account           =   a.id, 
                                            ipaa.project_account_2016      =   a16.id, 
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated invoice_project_account_allocations Foreign keys ----------  allocated_by, invoice_id, project_id, project_account, project_account_2016   \n";

     



      /**
         * 
         * 
         * 
         * 
         * 
         *                  invoice_statuses
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE invoice_statuses is 
                                    LEFT JOIN security_levels sl 
                                    ON sl.migration_id = is.migration_status_security_level

                                    SET     is.status_security_level              =   sl.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated invoice_statuses Foreign keys ----------  status_security_level   \n";




    }
}
