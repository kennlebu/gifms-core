<?php

use Illuminate\Database\Seeder;

class post_keys_migration_migration extends Seeder
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
    			UPDATE `lpos` 
	    			SET `created_at`  = `request_date`,
 						`updated_at`  = `request_date`
                        `ref`         = CONCAT('CHAI/LPO/#',`id`,'/',DATE_FORMAT(`created_at`,'%Y/%m/%d'))

    			";


        DB::statement($sql);

    	echo "\n lpos date data updated ---";
    }
}
