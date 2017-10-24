<?php

use Illuminate\Database\Seeder;

class post_migration_statuses_migration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='1' WHERE `id`='11'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='2' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='3' WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='4' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='5' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='6' WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `next_status_id`='5', `order_priority`='7' WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='9', `deleted_at`='2017-10-24 12:48:44' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `next_status_id`='7', `order_priority`='8' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='10' WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='11', `display_color`='#4fb360' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='12', `display_color`='#e57777' WHERE `id`='9'"; 
        DB::statement($sql);

    }
}
