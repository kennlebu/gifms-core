<?php

use Illuminate\Database\Seeder;

class migrate_referrence_tables extends Seeder
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
         *                  Approval Levels
         * 
         * 
         * 
         * 
         * 
         */
        DB::table('approval_levels')->insert([
		    ['approval_level' => 'Accountant Approval'],
		    ['approval_level' => 'PM Approval'],
		    ['approval_level' => 'Finance Approval'],
		    ['approval_level' => 'Management Approval']
		]);






        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Countries
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Countrys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['country_name']          = $data[$key]['Country'];
            $data_to_migrate[$key]['abbrv']          = $data[$key]['Abbrv'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Countries---";
            echo $data[$key]['Abbrv'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('countries')->insert($data_to_migrate);



    }
}
