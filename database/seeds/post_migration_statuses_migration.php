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



        $sql = "UPDATE  `invoice_statuses` SET `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='11'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='2', `display_color`='#64547a6e', `invoice_status`='Received Pending Submission'  WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='3', `invoice_status`='Pending Accountant Review'  WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='4', `invoice_status`='Pending PM Approval' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='5', `invoice_status`='Pending Finance Approval' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='6', `invoice_status`='Pending Management Approval'  WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `next_status_id`='5', `order_priority`='7', `display_color`='#075b23a1', `invoice_status`='Pending Finance CSV Creation (Batching)'  WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='9', `deleted_at`='2017-10-24 12:48:44' , `display_color`='#075b23a1' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `next_status_id`='7', `order_priority`='8' , `display_color`='#075b23a1' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='10', `display_color`='#075b23a1',`invoice_status`='CSV Uploaded to Bank Awaiting Reconciliation'  WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='11', `display_color`='#55ff55c2' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE `invoice_statuses` SET `order_priority`='12', `display_color`='#ff5555c2' WHERE `id`='9'"; 
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
        $sql = "UPDATE `lpo_statuses` SET `order_priority`='8', `display_color`='#55ff55c2' WHERE `id`='9'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `display_color`='#ff5555c2',`order_priority`='10' WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE `lpo_statuses` SET `display_color`='#ff5555c2',`order_priority`='9' WHERE `id`='11'"; 
        DB::statement($sql);















        $sql = "UPDATE `mobile_payment_statuses` SET `mobile_payment_status`='Request Uploaded Pending Submission    ', `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE `mobile_payment_statuses` SET `mobile_payment_status`='Pending PM Approval', `order_priority`='3' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `mobile_payment_status`='Pending Finance Approval', `order_priority`='4' WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `mobile_payment_status`='Pending Management Approval', `order_priority`='5' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `mobile_payment_status`='Pending Accountant Review', `order_priority`='2', `approvable`='1'  WHERE `id`='9'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='6', `display_color`='#075b23a1', `mobile_payment_status`='Sent To Bank Awaiting Reconciliation'  WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='7', `display_color`='#5b1307a1' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='8', `display_color`='#5b1307a1' WHERE `id`='11'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='9', `display_color`='#07575ba1' WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='10', `display_color`='#07575ba1', `mobile_payment_status`='Corrected And Resent to Bank Awaiting Reconciliation' WHERE `id`='13'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='11', `display_color`='#55ff55c2',`mobile_payment_status`='Paid' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='12', `display_color`='#075b23a1', `deleted_at`='2017-10-24 12:48:44' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE  `mobile_payment_statuses` SET `order_priority`='13', `display_color`='#ff5555c2' WHERE `id`='7'"; 
        DB::statement($sql);










        $sql = "UPDATE  `claim_statuses` SET `claim_status`='CSV Uploaded to Bank Awaiting Reconciliation', `order_priority`='8', `display_color`='#075b23a1' WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Pending Finance Approval', `order_priority`='4' WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Pending Directors Approval', `order_priority`='5' WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Pending Finance CSV Creation (Batching)    ', `order_priority`='6', `display_color`='#075b23a1' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Pending Accountant Review', `order_priority`='2' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Pending PM Approval', `order_priority`='3' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `claim_status`='Paid', `order_priority`='9', `display_color`='#55ff55c2' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `order_priority`='7', `display_color`='#075b23a1' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE  `claim_statuses` SET `order_priority`='10', `display_color`='#ff5555c2' WHERE `id`='9'"; 
        DB::statement($sql);







        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Compiled Pending Submission', `order_priority`='1', `display_color`='#64547a6e' WHERE `id`='1'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Pending PM Approval', `next_status_id`='3', `order_priority`='3' WHERE `id`='2'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Allocated Pending Accountant Review', `order_priority`='10', `display_color`='#075b23a1' WHERE `id`='9'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Pending Management Approval ', `next_status_id`='8', `order_priority`='5' WHERE `id`='4'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Pending Accountant Review', `order_priority`='2' WHERE `id`='13'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `order_priority`='4' WHERE `id`='3'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Pending Finance CSV Creation (Batching)', `next_status_id`='5', `order_priority`='6', `display_color`='#075b23a1' WHERE `id`='8'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `next_status_id`='7', `order_priority`='7', `display_color`='#075b23a1' WHERE `id`='5'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='CSV Uploaded to Bank Awaiting Reconciliation', `next_status_id`='6', `order_priority`='8', `display_color`='#075b23a1' WHERE `id`='7'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Issued & Paid Awaiting Uploader Allocations', `next_status_id`='9', `order_priority`='9', `display_color`='#075b23a1' WHERE `id`='6'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `advance_status`='Paid & Completed', `order_priority`='11', `display_color`='#55ff55c2' WHERE `id`='10'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `deleted_at`='2017-10-24 12:48:44' WHERE `id`='12'"; 
        DB::statement($sql);
        $sql = "UPDATE  `advance_statuses` SET `order_priority`='12', `display_color`='#ff5555c2' WHERE `id`='11'"; 
        DB::statement($sql);
    }
}

