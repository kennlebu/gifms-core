<?php

use Illuminate\Database\Seeder;

class migrate_accounting_data extends Seeder
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
         *                  Accounts
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Accounts')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['account_code']                 	= $data[$key]['AccountCode'];
            $data_to_migrate[$key]['account_name']                 	= $data[$key]['Account'];
            $data_to_migrate[$key]['account_desc']                 	= $data[$key]['AccountDescription'];
            $data_to_migrate[$key]['account_type']                 	= $data[$key]['AccountType'];
            $data_to_migrate[$key]['client']                 		= $data[$key]['Client'];
            $data_to_migrate[$key]['brevity']                 		= $data[$key]['Berevity'];
            $data_to_migrate[$key]['office_cost']                 	= $data[$key]['OfficeCost'];
            $data_to_migrate[$key]['account_format']                = 2013;
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Accounts---";
            echo $data[$key]['Account'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('accounts')->insert($data_to_migrate);









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Accounts2016
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Accounts2016')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['account_code']                 	= $data[$key]['AccountCode'];
            $data_to_migrate[$key]['account_name']                 	= $data[$key]['Account'];
            $data_to_migrate[$key]['account_desc']                 	= $data[$key]['AccountDescription'];
            $data_to_migrate[$key]['account_type']                 	= $data[$key]['AccountType'];
            $data_to_migrate[$key]['client']                 		= $data[$key]['Client'];
            $data_to_migrate[$key]['brevity']                 		= $data[$key]['Berevity'];
            $data_to_migrate[$key]['office_cost']                 	= $data[$key]['OfficeCost'];
            $data_to_migrate[$key]['account_format']                = 2016;
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Accounts2016---";
            echo $data[$key]['Account'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('accounts')->insert($data_to_migrate);






        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  AccountClassifications
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AccountClassifications')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['account_classification_name']  	= $data[$key]['AccountClassification'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n AccountClassifications---";
            echo $data[$key]['AccountClassification'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('account_classifications')->insert($data_to_migrate);







        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  QBAccounts
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('QBAccounts')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['account_name']		= $data[$key]['AccountName'];
            $data_to_migrate[$key]['account_number']  	= $data[$key]['AccountNumber'];
            $data_to_migrate[$key]['account_type']  	= $data[$key]['AccountType'];
            $data_to_migrate[$key]['account_desc']  	= $data[$key]['AccountDescription'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n QBAccounts---";
            echo $data[$key]['AccountName'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('qb_accounts')->insert($data_to_migrate);











        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  AccountTypes
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AccountTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['account_type_name']             = $data[$key]['AccountType'];
            $data_to_migrate[$key]['account_classification_id']  	= $data[$key]['AccountClassification'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n AccountTypes---";
            echo $data[$key]['AccountType'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('account_types')->insert($data_to_migrate);










    }
}
