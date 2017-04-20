<?php

use Illuminate\Database\Seeder;

class migrate_employees_keys extends Seeder
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
         *                  Employees
         * 
         * 
         * 
         * 
         * 
         */

        $migrate_keys_sql = "
                                UPDATE employees e 
                                    LEFT JOIN departments d 
                                    ON d.migration_id = e.migration_department_id
                                    LEFT JOIN banks b 
                                    ON b.migration_id = e.migration_bank_id
                                    LEFT JOIN bank_branches bbr 
                                    ON bbr.migration_id = e.migration_bank_branch_id

                                    SET     e.department_id       =   d.id ,
                                            e.bank_id             =   b.id ,
                                            e.bank_branch_id      =   bbr.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated supplier_rates Foreign keys ----------  departments, banks, bank_branches \n";
    }
}
