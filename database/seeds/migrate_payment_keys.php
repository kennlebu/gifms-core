<?php

use Illuminate\Database\Seeder;

class migrate_payment_keys extends Seeder
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
         *                  payments
         * 
         * 
         * 
         * 
         * 
         */
        $migrate_keys_sql = "
                                UPDATE payments p 
                                    LEFT JOIN payment_batches pb 
                                    ON pb.migration_id = p.migration_payment_batch_id
                                    LEFT JOIN invoices i 
                                    ON i.migration_id = p.migration_invoice_id
                                    LEFT JOIN advances adv 
                                    ON adv.migration_id = p.migration_advance_id

                                    SET     p.payment_batch_id              =   pb.id ,
                                            p.invoice_id                    =   i.id ,
                                            p.advance_id                    =   adv.id
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated payments Foreign keys ---------- payment_batch_id, invoice_id, advance_id,\n";
















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  payment_batches
         * 
         * 
         * 
         * 
         * 
         */
        $migrate_keys_sql = "
                                UPDATE payment_batches p 
                                    LEFT JOIN staff pb 
                                    ON pb.migration_id = p.migration_processed_by
                                    LEFT JOIN staff ub 
                                    ON ub.migration_id = p.migration_uploaded_by

                                    SET     p.processed_by              =   pb.id ,
                                            p.uploaded_by               =   ub.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated payment_batches Foreign keys ---------- processed_by, uploaded_by,\n";



    }
}
