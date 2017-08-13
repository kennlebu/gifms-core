<?php

use Illuminate\Database\Seeder;

class post_migration_keys_drop extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //LPOS
    	$sql = "
    			ALTER TABLE `gifms`.`lpos` 
					DROP COLUMN `migration_project_manager_id`,
					DROP COLUMN `migration_supplier_id`,
					DROP COLUMN `migration_received_by_id`,
					DROP COLUMN `migration_project_id`,
					DROP COLUMN `migration_requested_by_id`,
					DROP COLUMN `migration_account_id`,
					DROP COLUMN `migration_preffered_quotation_id`,
					DROP COLUMN `attention`,
					DROP COLUMN `preffered_supplier`,
					DROP COLUMN `supply_category`,
                    DROP COLUMN `addressee`,
                    DROP COLUMN `lpo_email`,
					DROP COLUMN `lpo_date`
    			";


        DB::statement($sql);

    	echo "\n lpos date data updated ---";
    }
}
