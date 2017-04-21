<?php

use Illuminate\Database\Seeder;

class migrate_staff_keys extends Seeder
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
         *                  Staff
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE staff s 
                                    LEFT JOIN departments d 
                                    ON d.migration_id = s.migration_department_id
                                    LEFT JOIN banks b 
                                    ON b.migration_id = s.migration_bank_id
                                    LEFT JOIN bank_branches bbr 
                                    ON bbr.migration_id = s.migration_bank_branch_id

                                    SET     s.department_id       =   d.id ,
                                            s.bank_id             =   b.id ,
                                            s.bank_branch_id      =   bbr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated staff Foreign keys ----------  departments, banks, bank_branches \n";
    }
}
