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
	     *					PaymentModes
	     * 
	     * 
	     * 
	     * 
	     * 
	     */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('PaymentModes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['payment_mode_description'] 			= $data[$key]['PaymentMode'];
        	$data_to_migrate[$key]['abrv'] 								= $data[$key]['Abbrv'];
        	$data_to_migrate[$key]['migration_id'] 						= $data[$key]['ID'];


        	echo "\n payment mode ---";
        	echo $data[$key]['PaymentMode'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('payment_modes')->insert($data_to_migrate);

        


    }
}
