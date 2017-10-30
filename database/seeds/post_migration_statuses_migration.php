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



        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='11'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='2', `display_color`='#64547a6e', `invoice_status`='Received Pending Submission'  WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='3', `invoice_status`='Pending Accountant Review'  WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='4', `invoice_status`='Pending PM Approval' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='5', `invoice_status`='Pending Finance Approval' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='6', `invoice_status`='Pending Management Approval'  WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `next_status_id`='5', `order_priority`='7', `display_color`='##075b23a1', `invoice_status`='Pending Finance CSV Creation (Batching)'  WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='9', `deleted_at`='2017-10-24 12:48:44' , `display_color`='##075b23a1' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `next_status_id`='7', `order_priority`='8' , `display_color`='##075b23a1' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='10', `display_color`='##075b23a1' WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='11', `display_color`='#55ff55c2' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE `gifms1`.`invoice_statuses` SET `order_priority`='12', `display_color`='#ff5555c2' WHERE `id`='9'"; 
        DB::statement($sql);





















        $sql = "UPDATE `lpo_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `lpo_status`='Pending Accountant Review', `order_priority`='2' WHERE `id`='13'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `lpo_status`='Pending PM Approval ', `order_priority`='3' WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='4' WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='5' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='14'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='6', `display_color`='#075b23a1' WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='7', `display_color`='#075b23a1' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='8', `display_color`='#075b23a1' WHERE `id`='9'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `display_color`='#ff5555c2',`order_priority`='10' WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `display_color`='#ff5555c2',`order_priority`='9' WHERE `id`='11'"; 
        DB::statement($sql);

    }
}

