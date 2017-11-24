<?php

use Illuminate\Database\Seeder;

class migrate_grant_data extends Seeder
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
         *                  Donors
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Donors')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['donor_name']                 	= $data[$key]['Donor'];
            $data_to_migrate[$key]['donor_code']                 	= $data[$key]['DonorCode'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Donors-$key---";
            echo $data[$key]['Donor'];
        }
        
        DB::table('donors')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------Donors \n";















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Grants
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Grants')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['grant_name']                 	= $data[$key]['Grant'];
            $data_to_migrate[$key]['grant_code']                 	= $data[$key]['GrantCode'];
            $data_to_migrate[$key]['grant_amount']                 	= $data[$key]['GrantAmount'];
            $data_to_migrate[$key]['status_id']                 	= $data[$key]['GrantStatus'];
            $data_to_migrate[$key]['migration_donor_id']            = $data[$key]['Donor'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Grants-$key---";
            echo $data[$key]['Grant'];
        }
        
        DB::table('grants')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------Grants \n";
    }













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  GrantStatuses
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('GrantStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['grant_status']                 	= $data[$key]['GrantStatus'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n GrantStatuses-$key---";
            echo $data[$key]['Grant'];
        }
        
        DB::table('grant_statuses')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------GrantStatuses \n";
    }
}
