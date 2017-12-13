<?php

use Illuminate\Database\Seeder;

class migrate_budgeting_keys extends Seeder
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
         *                  projects
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE projects bb 
                                    SET     bb.budget_id             =   bb.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated projects Foreign keys ---------- budget_id  \n";











        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  budget_items
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE budget_items bi 
                                    LEFT JOIN budgets b 
                                    ON b.id = bi.budget_id

                                    SET     bi.created_by_id             	=   b.created_by_id ,
                                            bi.create_action_by_id        	=   b.create_action_by_id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated budget_items Foreign keys ---------- created_by_id,  create_action_by_id  \n";









    }
}
