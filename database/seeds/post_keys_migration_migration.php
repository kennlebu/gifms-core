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

        echo "\n lpo_statuses add ---";


        //currencies color

        DB::statement("
            UPDATE `gifms`.`currencies` SET `display_color`='#18468A' WHERE `id`='1'
            ");

        DB::statement("
            UPDATE `gifms`.`currencies` SET `display_color`='#B01500' ,`currency_sign`='$' WHERE `id`='2'
            ");

        echo "\n currencies display_color ---";


        //payments payable migration

        DB::statement('
            UPDATE `gifms`.`payments` SET `payable_id`=`invoice_id`, `payable_type` = "invoices" WHERE `invoice_id`<>""
            ');
        DB::statement('
            UPDATE `gifms`.`payments` SET `payable_id`=`advance_id`, `payable_type` = "advances" WHERE `advance_id`<>""
            ');
        
        echo "\n payments payable_id, payable_type---";





















        //Invoices
        $sql = "
                UPDATE `invoices` 
                    SET `created_at`  = `date_raised`,
                        `updated_at`  = `date_raised`,
                        `ref`         = CONCAT('CHAI/INV/#',`id`,'/',DATE_FORMAT(`date_raised`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n invoices date data updated ---";































        //mobile_payments
        $sql = "
                UPDATE `mobile_payments` 
                    SET `created_at`  = `requested_date`,
                        `updated_at`  = `requested_date`,
                        `ref`         = CONCAT('CHAI/MPYMT/#',`id`,'/',DATE_FORMAT(`requested_date`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n mobile_payments date data updated ---";































        //claims
        $sql = "
                UPDATE `claims` 
                    SET `created_at`  = `requested_date`,
                        `updated_at`  = `requested_date`,
                        `ref`         = CONCAT('CHAI/CLM/#',`id`,'/',DATE_FORMAT(`requested_date`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n claims date data updated ---";































        //advances
        $sql = "
                UPDATE `advances` 
                    SET `created_at`  = `requested_date`,
                        `updated_at`  = `requested_date`,
                        `ref`         = CONCAT('CHAI/ADV/#',`id`,'/',DATE_FORMAT(`requested_date`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n advances date data updated ---";































        //payments
        $sql = "
                UPDATE `payments` `p`
                LEFT JOIN `payment_batches` `b`
                ON `b`.`id` = `p`.`payment_batch_id`
                    SET `p`.`created_at`  = `b`.`payment_date`,
                        `p`.`updated_at`  = `b`.`payment_date`,
                        `p`.`ref`         = CONCAT('CHAI/PYMT/#',`p`.`id`,'/',DATE_FORMAT(`b`.`payment_date`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n payments date data updated ---";



        //payments
        $sql = "
                UPDATE `payments` `p`
                RIGHT JOIN `invoices` `i`
                ON `i`.`id` = `p`.`invoice_id`
                    LEFT JOIN `suppliers` `s`
                    ON `s`.`id`=`i`.`supplier_id`
                        LEFT JOIN `banks` `b`
                        ON `b`.`id` =`s`.`bank_id`
                        LEFT JOIN `bank_branches` `br`
                        ON `br`.`id` =`s`.`bank_branch_id`
                    SET `p`.`currency_id`  = `i`.`currency_id`,
                        `p`.`payment_desc`  = `i`.`expense_desc`,
                        `p`.`paid_to_bank_account_no`  = `s`.`bank_account`,
                        `p`.`paid_to_bank_account_no`  = CASE WHEN `i`.`currency_id` = 2 THEN `s`.`usd_account` ELSE `s`.`bank_account` END,
                        `p`.`paid_to_name`  = `s`.`supplier_name`,
                        `p`.`paid_to_bank_id`  = `b`.`id`,
                        `p`.`paid_to_bank_branch_id`  = `br`.`id`,
                        `p`.`debit_bank_account_id`  = `i`.`currency_id`

                ";


        DB::statement($sql);

        echo "\n payments date data updated ---";































        //payment_batches
        $sql = "
                UPDATE `payment_batches` 
                    SET `created_at`  = `payment_date`,
                        `updated_at`  = `payment_date`,
                        `ref`         = CONCAT('CHAI/PYTBT/#',`id`,'/',DATE_FORMAT(`payment_date`,'%Y/%m/%d'))

                ";


        DB::statement($sql);

        echo "\n payment_batches date data updated ---";

























        //invoice_statuses
        DB::table('invoice_statuses')->insert([
                [
                    'invoice_status' =>'Received pending upload',
                    'next_status_id' =>'10'
                ],
                [
                    'invoice_status' =>'Allocated Pending Accountant Approval',
                    'next_status_id' =>'1'

                ]
            ]);

        echo "\n invoice_statuses  updated ---";








        //claim_statuses
        DB::table('claim_statuses')->insert([
                [
                    'claim_status' =>'Pending Accountant Approval',
                    'next_status_id' => '2'
                ]
            ]);

        echo "\n claim_statuses  updated ---";











        //advance_statuses
        DB::table('advance_statuses')->insert([
                [
                    'advance_status' =>'Pending Accountant Approval',
                    'next_status_id' => '2'
                ]
            ]);

        echo "\n advance_statuses  updated ---";



        
        $sql = "
                UPDATE `advance_statuses`           SET `next_status_id`  = '4'     WHERE `id`='3'

                ";


        DB::statement($sql);

        $sql = "
                UPDATE `advance_statuses`           SET `next_status_id`  = '5'     WHERE `id`='4'

                ";


        DB::statement($sql);


        $sql = "
                UPDATE `advance_statuses`           SET `next_status_id`  = '6'     WHERE `id`='5'

                ";


        DB::statement($sql);












        //default statuses
        
        $sql = "
                UPDATE `advance_statuses`           SET `default_status`  = '1', `next_status_id`  = '13'     WHERE `id`='1'

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `claim_statuses`             SET `default_status`  = '1', `next_status_id`  = '10'     WHERE `id`='1'

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `invoice_statuses`           SET `default_status`  = '1', `next_status_id`  = '10'     WHERE `id`='11'

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `lpo_statuses`               SET `default_status`  = '1'     WHERE `id`='2'

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `mobile_payment_statuses`    SET `default_status`  = '1'     WHERE `id`='1'

                ";

        DB::statement($sql);

        echo "\n default statuses updated ---";












        //invoice submittable
        $sql = "
                UPDATE `invoice_statuses`           SET `default_status`  = null, `next_status_id`  = '12'     WHERE `id`='10'

                ";


        DB::statement($sql);












        //approvable statuses
        
        $sql = "
                UPDATE `advance_statuses`           SET `approvable`  = '1'     WHERE `id` IN (2,3,4,8,9,12,13)

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `claim_statuses`             SET `approvable`  = '1'     WHERE `id` IN (2,3,4,10)

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `invoice_statuses`           SET `approvable`  = '1'     WHERE `id` IN (1,2,3,12)

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `lpo_statuses`               SET `approvable`  = '1'     WHERE `id` IN (3,4,5,13)

                ";


        DB::statement($sql);
        $sql = "
                UPDATE `mobile_payment_statuses`    SET `approvable`  = '1'     WHERE `id` IN (2,3,8,12)

                ";

        DB::statement($sql);

        echo "\n approvable statuses updated ---";


    }
}
