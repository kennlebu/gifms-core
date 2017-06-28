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
 						`updated_at`  = `request_date`,
                        `ref`         = CONCAT('CHAI/LPO/#',`id`,'/',DATE_FORMAT(`request_date`,'%Y/%m/%d'))

    			";


        DB::statement($sql);

    	echo "\n lpos date data updated ---";




        //lpo_statuses
        DB::table('lpo_statuses')->insert([
                [
                    'lpo_status' =>'Paid and completed'
                ]
            ]);

        DB::statement("UPDATE `lpo_statuses` SET `next_status_id` = '14' WHERE `id` = '10' ");


        //currencies color

        DB::statement("
            UPDATE `gifms`.`currencies` SET `display_color`='#B01500' WHERE `id`='2';
            UPDATE `gifms`.`currencies` SET `display_color`='#18468A' WHERE `id`='1';
            ");


        //payments payable migration

        DB::statement("
            UPDATE `gifms`.`payments` SET `payable_id`=`invoice_id`, `payable_type` = 'APP\\Models\\InvoiceModels\\Invoice' WHERE `invoice_id`<>'';
            ");


    }
}
