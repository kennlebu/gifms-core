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
                                    LEFT JOIN employees ma 
                                    ON ma.migration_id = i.migration_management_approval
                                    LEFT JOIN employees ub 
                                    ON ub.migration_id = i.migration_uploaded_by
                                    LEFT JOIN employees ap 
                                    ON ap.migration_id = i.migration_approver_id
                                    LEFT JOIN claims c 
                                    ON c.migration_id = i.migration_claim_id
                                    LEFT JOIN lpos l 
                                    ON l.migration_id = i.migration_lpo_id
                                    LEFT JOIN staff_advances sa 
                                    ON sa.migration_id = i.migration_advance_id
                                    LEFT JOIN employees mp 
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




    }
}
