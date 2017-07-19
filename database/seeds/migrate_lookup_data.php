<?php

use Illuminate\Database\Seeder;

class migrate_lookup_data extends Seeder
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
        echo "\nApproval Levels -[ALL]---\n";

        DB::table('approval_levels')->insert([
		    ['approval_level' => 'Accountant Approval'],
		    ['approval_level' => 'PM Approval'],
		    ['approval_level' => 'Finance Approval'],
		    ['approval_level' => 'Management Approval']
		]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";





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


        $data = (array) DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Countrys')->get()->toArray();

        // print_r($data);

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['country_name']      = $data[$key]['Country'];
            $data_to_migrate[$key]['abbrv']             = $data[$key]['Abbrv'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Countries-$key---";
            echo $data[$key]['Abbrv'];
        }
        
        DB::table('countries')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";














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


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Countys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['county_name']      = $data[$key]['County'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Counties-$key---";
            echo $data[$key]['County'];
        }
        
        DB::table('counties')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";















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


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Citys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['city_name']      = $data[$key]['City'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['CityIDD'];


            echo "\n Cities-$key---";
            echo $data[$key]['City'];
        }
        
        DB::table('cities')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";














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


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Regions')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['region_name']      = $data[$key]['Region'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n Regions-$key---";
            echo $data[$key]['Region'];
        }
        
        DB::table('regions')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";


















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


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('CashRequestPurposes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['cash_request_purpose']          = $data[$key]['CashRequestPurpose'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n CashRequestPurposes-$key---";
            echo $data[$key]['CashRequestPurpose'];
        }
        
        DB::table('cash_request_purposes')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";















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

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ExchangeRates')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['exchange_rate']                 = $data[$key]['ExchangeRate'];
            $data_to_migrate[$key]['current_rate']                  = $data[$key]['CurrRate'];
            $data_to_migrate[$key]['active_date']                   = $data[$key]['ActiveDate'];
            $data_to_migrate[$key]['end_date']                      = $data[$key]['EndDate'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n ExchangeRates-$key---";
            echo $data[$key]['ExchangeRate'];
        }
        
        DB::table('exchange_rates')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";








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


            echo "\n Stations-$key---";
            echo $data[$key]['StationAdd'];
        }
        
        DB::table('stations')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";








        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  SecurityLevel
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('SecurityLevel')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['security_level_desc']       = $data[$key]['Description'];
            $data_to_migrate[$key]['home_page']                 = $data[$key]['HomePage'];
            $data_to_migrate[$key]['migration_id']              = $data[$key]['ID'];


            echo "\n SecurityLevel-$key---";
            echo $data[$key]['Description'];
        }
        
        DB::table('security_levels')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";












        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  recurring_periods
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('RecurringPeriods')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['recurr_period']             = $data[$key]['RecurrPeriod'];
            $data_to_migrate[$key]['days']                      = $data[$key]['RecurrDays'];
            $data_to_migrate[$key]['migration_id']              = $data[$key]['ID'];


            echo "\n recurring periods-$key---";
            echo $data[$key]['RecurrPeriod'];
        }
        
        DB::table('recurring_periods')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";














        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  activation_types
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ActivationTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['activation_type']             = $data[$key]['ActivationType'];
            $data_to_migrate[$key]['migration_id']              = $data[$key]['ID'];


            echo "\n Activation Type-$key---";
            echo $data[$key]['ActivationType'];
        }
        
        DB::table('activation_types')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  clusters
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Clusters')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['cluster_desc']                  = $data[$key]['Cluster'];
            $data_to_migrate[$key]['strategic_group_id']            = $data[$key]['StrategicGroup'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Clusters -$key---";
            echo $data[$key]['Cluster'];
        }
        
        DB::table('clusters')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  currencies
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Currencys')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['currency_name']                 = $data[$key]['Currency'];
            $data_to_migrate[$key]['default_currency']              = $data[$key]['DefaultCurrency'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Currencies -$key---";
            echo $data[$key]['Currency'];
        }
        
        DB::table('currencies')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";


















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  import_classes
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ImportClasses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['date_time']                     = $data[$key]['Date/Time'];
            $data_to_migrate[$key]['status']                        = $data[$key]['Status'];
            $data_to_migrate[$key]['class_name']                    = $data[$key]['Class Name'];
            $data_to_migrate[$key]['migration_id']                  = $key;


            echo "\n Import Classes -$key---";
            echo $data[$key]['Class Name'];
        }
        
        DB::table('import_classes')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";






















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  import_vendors
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ImportedVendors')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['date_time']                 = $data[$key]['Date/Time'];
            $data_to_migrate[$key]['status']                 = $data[$key]['Status'];
            $data_to_migrate[$key]['vendor_name']                 = $data[$key]['Vendor Name'];
            $data_to_migrate[$key]['is_active']                 = $data[$key]['Is Active'];
            $data_to_migrate[$key]['company_name']                 = $data[$key]['Company Name'];
            $data_to_migrate[$key]['salutation']                 = $data[$key]['Salutation'];
            $data_to_migrate[$key]['first_name']                 = $data[$key]['First Name'];
            $data_to_migrate[$key]['m_i']                 = $data[$key]['M#I#'];
            $data_to_migrate[$key]['last_name']                 = $data[$key]['Last Name'];
            $data_to_migrate[$key]['contact']                 = $data[$key]['Contact'];
            $data_to_migrate[$key]['phone']                 = $data[$key]['Phone'];
            $data_to_migrate[$key]['fax']                 = $data[$key]['Fax'];
            $data_to_migrate[$key]['alt_phone']                 = $data[$key]['Alt# Phone'];
            $data_to_migrate[$key]['alt_contact']                 = $data[$key]['Alt# Contact'];
            $data_to_migrate[$key]['email']                 = $data[$key]['Email'];
            $data_to_migrate[$key]['print_on_check']                 = $data[$key]['Print on Check'];
            $data_to_migrate[$key]['address_line_1']                 = $data[$key]['Address Line1'];
            $data_to_migrate[$key]['address_line_2']                 = $data[$key]['Address Line2'];
            $data_to_migrate[$key]['address_line_3']                 = $data[$key]['Address Line3'];
            $data_to_migrate[$key]['address_line_4']                 = $data[$key]['Address Line4'];
            $data_to_migrate[$key]['address_city']                 = $data[$key]['Address City'];
            $data_to_migrate[$key]['address_state']                 = $data[$key]['Address State'];
            $data_to_migrate[$key]['address_postal_code']             = $data[$key]['Address PostalCode'];
            $data_to_migrate[$key]['address_country']                 = $data[$key]['Address Country'];
            $data_to_migrate[$key]['vendor_type']                 = $data[$key]['Vendor Type'];
            $data_to_migrate[$key]['terms']                 = $data[$key]['Terms'];
            $data_to_migrate[$key]['tax_id']                 = $data[$key]['Tax ID'];
            $data_to_migrate[$key]['eligible_for_1099']                 = $data[$key]['Eligible for 1099'];
            $data_to_migrate[$key]['account_number']                 = $data[$key]['Account Number'];
            $data_to_migrate[$key]['credit_limit']                 = $data[$key]['Credit Limit'];
            $data_to_migrate[$key]['notes']                 = $data[$key]['Notes'];
            $data_to_migrate[$key]['prefill_account']                 = $data[$key]['Prefill Account'];
            $data_to_migrate[$key]['billing_rate']                 = $data[$key]['Billing Rate'];
            $data_to_migrate[$key]['migration_id']                  = $key;


            echo "\n Imported Vendors -$key---";
            echo $data[$key]['Vendor Name'];
        }
        
        DB::table('import_vendors')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";












    }
}
