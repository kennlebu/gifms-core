<?php

use Illuminate\Database\Seeder;

class migrate_approvals_keys extends Seeder
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
         *                  advance_approvals
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE approvals a 
                                    LEFT JOIN staff ap 
                                    ON ap.migration_id = a.migration_approver_id

                                    SET     a.approver_id    =   ap.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated approvals Foreign keys ---------- approver_id \n";







    }
}
