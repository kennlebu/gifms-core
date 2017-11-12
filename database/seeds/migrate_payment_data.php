<?php

use Illuminate\Database\Seeder;

class migrate_payment_data extends Seeder
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
         *                  PaymentModes
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('PaymentModes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['payment_mode_description']          = $data[$key]['PaymentMode'];
            $data_to_migrate[$key]['abrv']                              = $data[$key]['Abbrv'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n payment mode ---";
            echo $data[$key]['PaymentMode'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('payment_modes')->insert($data_to_migrate);







    	/**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					Payments
	     * 
	     * 
	     * 
	     * 
	     * 
	     */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Payments')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['payment_mode_id']               =   $data[$key]['PaymentMode'];
            $data_to_migrate[$key]['amount']                        =   $data[$key]['Amount'];
            $data_to_migrate[$key]['bank_charges']                  =   $data[$key]['BankCharges'];
            $data_to_migrate[$key]['status_id']                     =   4;
            $data_to_migrate[$key]['migration_payment_batch_id']    =   $data[$key]['Batch'];
            $data_to_migrate[$key]['migration_invoice_id']          =   $data[$key]['Invoice'];
            $data_to_migrate[$key]['migration_advance_id']          =   $data[$key]['Advance'];
        	$data_to_migrate[$key]['migration_id'] 				    =   $data[$key]['ID'];


        	echo "\n payments---";
        	echo $data[$key]['Amount'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('payments')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  PaymentBatches
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('PaymentBatches')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['payment_date']                          =   $data[$key]['PaymentDate'];
            $data_to_migrate[$key]['upload_date']                           =   $data[$key]['UploadDate'];
            $data_to_migrate[$key]['status_id']                             =   2;
            $data_to_migrate[$key]['migration_processed_by']                =   $data[$key]['ProcessedBy'];
            $data_to_migrate[$key]['migration_uploaded_by']                 =   $data[$key]['UploadedBy'];
            $data_to_migrate[$key]['migration_id']                          =   $data[$key]['ID'];


            echo "\n payment batches---";
            echo $data[$key]['PaymentDate'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('payment_batches')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  PaymentTypes
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('PaymentTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['payment_type_desc']                             =   $data[$key]['PaymentType'];
            $data_to_migrate[$key]['migration_id']                                  =   $data[$key]['ID'];


            echo "\n payment types---";
            echo $data[$key]['PaymentType'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('payment_types')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";


        










        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Payment Statuses
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPayment Statuses -[ALL]---\n";

        DB::table('payment_statuses')->insert([
            [
                'payment_status' => 'Generated Awaiting Batching',
                'next_status_id' => '2',
                'order_priority' => '1',
                'default_status' => '1'

            ],
            [
                'payment_status' => 'Batched',
                'next_status_id' => '3',
                'order_priority' => '2',
                'default_status' => NULL

            ],
            [
                'payment_status' => 'Sent To Bank',
                'next_status_id' => '4',
                'order_priority' => '3',
                'default_status' => NULL

            ],
            [
                'payment_status' => 'Reconciled',
                'next_status_id' => '5',
                'order_priority' => '4',
                'default_status' => NULL

            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";
        






















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Payment Batch Statuses
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPayment Batch Statuses -[ALL]---\n";

        DB::table('payment_batch_statuses')->insert([
            [
                'payment_status' => 'Created Awaiting Upload To Bank',
                'next_status_id' => '2',
                'order_priority' => '1',
                'default_status' => '1'

            ],
            [
                'payment_status' => 'Uploaded To Bank',
                'next_status_id' => '3',
                'order_priority' => '2',
                'default_status' => NULL

            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";












    }
}
