<?php

use Illuminate\Database\Seeder;

class migrate_grant_keys extends Seeder
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
         *                  grants
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE grants g 
                                    LEFT JOIN donors d 
                                    ON d.migration_id = g.migration_donor_id

                                    SET     g.donor_id   =   d.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated grants Foreign keys ---------- donors \n";
    }
}
