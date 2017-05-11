<?php

use Illuminate\Database\Seeder;

class migrate_suppliers_data extends Seeder
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
         *                  Suppliers
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Suppliers')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['supplier_name']                 = $data[$key]['Supplier'];
            $data_to_migrate[$key]['contact_name']                  = $data[$key]['Contact'];
            $data_to_migrate[$key]['address']                       = $data[$key]['Addresss'];
            $data_to_migrate[$key]['telephone']                     = $data[$key]['Telephone'];
            $data_to_migrate[$key]['email']                         = $data[$key]['Email'];
            $data_to_migrate[$key]['website']                       = $data[$key]['Website'];
            $data_to_migrate[$key]['bank_account']                  = $data[$key]['BankAccount'];
            $data_to_migrate[$key]['mobile_payment_number']         = $data[$key]['MobilePaymentNumber'];
            $data_to_migrate[$key]['chaque_address']                = $data[$key]['ChequeAddressee'];
            $data_to_migrate[$key]['payment_mode']                  = $data[$key]['PaymentMode'];
            $data_to_migrate[$key]['bank_code']                     = $data[$key]['BankCode'];
            $data_to_migrate[$key]['swift_code']                    = $data[$key]['SWIFTCode'];
            $data_to_migrate[$key]['usd_account']                   = $data[$key]['USDAccount'];
            $data_to_migrate[$key]['alternative_email']             = $data[$key]['AlternativeEmail'];
            $data_to_migrate[$key]['currency']                      = $data[$key]['Currency'];
            $data_to_migrate[$key]['mobile_payment_name']           = $data[$key]['MobilePaymentName'];
            $data_to_migrate[$key]['city_id']                       = $data[$key]['City'];
            $data_to_migrate[$key]['qb']                            = $data[$key]['QB'];
            $data_to_migrate[$key]['status']                        = $data[$key]['Status'];
            $data_to_migrate[$key]['staff']                         = $data[$key]['Staff'];
            $data_to_migrate[$key]['password']                      = $data[$key]['Passwd'];
            $data_to_migrate[$key]['quick_books']                   = $data[$key]['Quickbooks'];
            $data_to_migrate[$key]['tax_pin']                       = $data[$key]['TaxPIN'];
            $data_to_migrate[$key]['migration_bank_id']             = (int)$data[$key]['Bank'];
            $data_to_migrate[$key]['migration_bank_branch_id']      = (int)$data[$key]['BankBranch'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Suppliers-$key---";
            echo $data[$key]['Supplier'];
        }


        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('suppliers')->insert($batch);
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
         *                  SupplyCategories
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('SupplyCategories')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['supply_category_name']              = $data[$key]['SupplyCategory'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n SupplyCategories-$key---";
            echo $data[$key]['SupplyCategory'];
        }

        DB::table('supply_categories')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";



















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  SupplyCategoryItems
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('SupplyCategoryItems')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['category_item_name']                = $data[$key]['CategoryItem'];
            $data_to_migrate[$key]['migration_category_id']             = $data[$key]['Category'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n SupplyCategoryItems-$key---";
            echo $data[$key]['CategoryItem'];
        }
        DB::table('supply_category_items')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";
















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  SupplierRates
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('SupplierRates')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['rate']                          = $data[$key]['Rate'];
            $data_to_migrate[$key]['year']                          = $data[$key]['Year'];
            $data_to_migrate[$key]['migration_category_item_id']    = $data[$key]['CategoryItem'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n SupplierRates-$key---";
            echo $data[$key]['CategoryItem'];
        }
        
        DB::table('supplier_rates')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";
























        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  category_suppliers
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('CategorySuppliers')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_category_id']         = $data[$key]['Category'];
            $data_to_migrate[$key]['migration_supplier_id']         = $data[$key]['Supplier'];
            $data_to_migrate[$key]['migration_id']                  = $key;


            echo "\n SupplyCategories -$key---";
            echo $data[$key]['Category'];
        }
        
        DB::table('category_suppliers')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";







    }


}
