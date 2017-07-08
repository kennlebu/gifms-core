<?php

use Illuminate\Database\Seeder;

class migrate_banking_keys extends Seeder
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
         *                  bank_branches
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE bank_branches bb 
                                    LEFT JOIN banks b 
                                    ON b.migration_id = bb.migration_bank_id

                                    SET     bb.bank_id             =   b.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated bank_branches Foreign keys ---------- bank_id  \n";
    }
}
