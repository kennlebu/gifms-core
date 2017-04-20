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

            $data_to_migrate[$key]['country_name']      = $data[$key]['Country'];
            $data_to_migrate[$key]['abbrv']             = $data[$key]['Abbrv'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Countries---";
            echo $data[$key]['Abbrv'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('countries')->insert($data_to_migrate);













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Counties
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Countys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['county_name']      = $data[$key]['County'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Counties---";
            echo $data[$key]['County'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('counties')->insert($data_to_migrate);














        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Cities
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Citys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['city_name']      = $data[$key]['City'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['CityIDD'];


            echo "\n Cities---";
            echo $data[$key]['City'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('cities')->insert($data_to_migrate);













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Regions
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Regions')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['region_name']      = $data[$key]['Region'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Regions---";
            echo $data[$key]['Region'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('regions')->insert($data_to_migrate);

















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  CashRequestPurposes
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('CashRequestPurposes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['cash_request_purpose']          = $data[$key]['CashRequestPurpose'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n CashRequestPurposes---";
            echo $data[$key]['CashRequestPurpose'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('cash_request_purposes')->insert($data_to_migrate);














        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  ExchangeRates
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ExchangeRates')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['exchange_rate']                 = $data[$key]['ExchangeRate'];
            $data_to_migrate[$key]['current_rate']                  = $data[$key]['CurrRate'];
            $data_to_migrate[$key]['active_date']                   = $data[$key]['ActiveDate'];
            $data_to_migrate[$key]['end_date']                      = $data[$key]['EndDate'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n ExchangeRates---";
            echo $data[$key]['ExchangeRate'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('exchange_rates')->insert($data_to_migrate);







        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Stations
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Stations')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['station_name']                  = $data[$key]['Station'];
            $data_to_migrate[$key]['logo']                          = $data[$key]['Logo'];
            $data_to_migrate[$key]['address']                       = $data[$key]['Address'];
            $data_to_migrate[$key]['location']                      = $data[$key]['StationAdd'];
            $data_to_migrate[$key]['country']                       = $data[$key]['Country'];
            $data_to_migrate[$key]['budget_default']                = $data[$key]['BudgetDefault'];
            $data_to_migrate[$key]['telephone']                     = $data[$key]['Telephone'];
            $data_to_migrate[$key]['mobile']                        = $data[$key]['Mobile'];
            $data_to_migrate[$key]['primary_contact']               = $data[$key]['PrimaryContact'];
            $data_to_migrate[$key]['email']                         = $data[$key]['EmailAddress'];
            $data_to_migrate[$key]['licence_level']                 = $data[$key]['licenselevel'];
            $data_to_migrate[$key]['town']                          = $data[$key]['Town'];
            $data_to_migrate[$key]['currency']                      = $data[$key]['currncy'];
            $data_to_migrate[$key]['expiry_date']                   = $data[$key]['ExpiryDate'];
            $data_to_migrate[$key]['licence_status']                = $data[$key]['LicenseStatus'];
            $data_to_migrate[$key]['accountant_email']              = $data[$key]['AccountantEmail'];
            $data_to_migrate[$key]['finance_email']                 = $data[$key]['FinanceEmail'];
            $data_to_migrate[$key]['manager_email']                 = $data[$key]['ManagerEmail'];
            $data_to_migrate[$key]['installation_path']             = $data[$key]['InstallationPath'];
            $data_to_migrate[$key]['alert_email_address']           = $data[$key]['AlertEmailAddress'];
            $data_to_migrate[$key]['alert_email_password']          = $data[$key]['AlertEmailPassword'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['STID'];


            echo "\n Stations---";
            echo $data[$key]['StationAdd'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

        DB::table('stations')->insert($data_to_migrate);



    }
}