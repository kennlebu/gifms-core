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
                                    ON ma.migration_id = i.migration_management_approval_id
                                    LEFT JOIN staff rb 
                                    ON rb.migration_id = i.migration_raised_by_id
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = i.migration_approver_id
                                    LEFT JOIN staff pm 
                                    ON pm.migration_id = i.migration_project_manager_id
                                    LEFT JOIN lpos l 
                                    ON l.migration_id = i.migration_lpo_id

                                    SET     i.management_approval_id        =   ma.id, 
                                            i.raised_by_id                  =   rb.id, 
                                            i.received_by_id                =   rb.id, 
                                            i.raise_action_by_id            =   rb.id, 
                                            i.approver_id                   =   ap.id, 
                                            i.project_manager_id            =   pm.id, 
                                            i.lpo_id                        =   l.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated invoices Foreign keys ---------- pm,lpos \n";

      /**
         * 
         * 
         * 
         * 
         * 
         *                  allocations
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE allocations al 
                                    LEFT JOIN staff ab 
                                    ON ab.migration_id = al.migration_allocated_by_id
                                    LEFT JOIN invoices i 
                                    ON i.migration_id = al.migration_allocatable_id
                                    LEFT JOIN projects p 
                                    ON p.migration_id = al.migration_project_id
                                    LEFT JOIN accounts a13 
                                    ON a13.account_code = al.migration_account_2013_code
                                    LEFT JOIN accounts a16 
                                    ON a16.account_code = al.migration_account_2016_code

                                    SET     al.allocated_by_id           =   ab.id, 
                                            al.allocatable_id            =   i.id, 
                                            al.project_id                =   p.id, 
                                            al.account_2013_id           =   a13.id, 
                                            al.account_2016_id           =   a16.id,
                                            al.account_id                =   CASE WHEN migration_account_2016_code <> '' THEN a16.id
                                                                                  WHEN migration_account_2013_code <> '' THEN a13.id
                                                                                  ELSE NULL
                                                                             END

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
                                UPDATE invoice_statuses iss 
                                    LEFT JOIN security_levels sl 
                                    ON sl.migration_id = iss.migration_status_security_level

                                    SET     iss.status_security_level              =   sl.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated invoice_statuses Foreign keys ----------  status_security_level   \n";







         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  invoice_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE invoice_viewing_permissions ivp 
                                    LEFT JOIN security_levels sl 
                                    ON ivp.migration_security_level = sl.migration_id
                                    SET ivp.security_level = sl.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated invoice_viewing_permissions Foreign keys ---------- security_level \n";










         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  invoices_logs
         * 
         * 
         * 
         * 
         * 
         */


        $migrate_keys_sql = "
                                UPDATE invoices_logs il 
                                    LEFT JOIN staff lb 
                                    ON il.migration_logged_by = lb.migration_id
                                    LEFT JOIN staff s 
                                    ON il.migration_staff_id = s.migration_id
                                    LEFT JOIN suppliers su 
                                    ON il.migration_supplier_id = su.migration_id
                                    SET il.logged_by = lb.id ,
                                        il.staff_id = s.id ,
                                        il.supplier_id = su.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated invoices_logs Foreign keys ---------- logged_by,staff_id,supplier_id \n";










         /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  recurring_invoices
         * 
         * 
         * 
         * 
         * 
         */


        $migrate_keys_sql = "
                                UPDATE recurring_invoices rp 
                                    LEFT JOIN invoices inv 
                                    ON rp.migration_invoice_id = inv.migration_id
                                    SET rp.invoice_id = inv.id 
                            ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated recurring_invoices Foreign keys ---------- invoice_id \n";









    }
}
