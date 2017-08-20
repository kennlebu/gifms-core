<?php

use Illuminate\Database\Seeder;

class migrate_banking_data extends Seeder
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
         *                  Banks
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Banks')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['bank_name'] 		= $data[$key]['BankName'];
        	$data_to_migrate[$key]['bank_code'] 		= $data[$key]['BankCode'];
        	$data_to_migrate[$key]['swift_code'] 		= $data[$key]['SWIFTCode'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\n Bank-$key---";
        	echo $data[$key]['BankName'];
        }
        
        DB::table('banks')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";










         

        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  BankBranches
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankBranches')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['branch_name']       = $data[$key]['BankBranch'];
            $data_to_migrate[$key]['branch_code']       = $data[$key]['BranchCode'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];
        	$data_to_migrate[$key]['migration_bank_id'] = $data[$key]['Bank'];


        	echo "\n Branch -$key---";
        	echo $data[$key]['BankBranch'];
        }
        
        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('bank_branches')->insert($batch);
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
         *                  BankAccounts
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankAccounts')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['title'] 			= $data[$key]['Title'];
        	$data_to_migrate[$key]['bank_name'] 		= $data[$key]['BankName'];
        	$data_to_migrate[$key]['branch_name'] 		= $data[$key]['BankBranch'];
        	$data_to_migrate[$key]['account_number'] 	= $data[$key]['AccountNumber'];
        	$data_to_migrate[$key]['currency'] 			= $data[$key]['Currency'];
        	$data_to_migrate[$key]['balance_locked'] 	= $data[$key]['BalanceLocked'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\nbank account-$key---";
        	echo $data[$key]['Title'];
        }
        
        DB::table('bank_accounts')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";



        







        

        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  BankCSV
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankCSV')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['csv_document'] 		= $data[$key]['CSVDocument'];
        	$data_to_migrate[$key]['date'] 				= $data[$key]['CSVDate'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\n bank csv-$key---";
        	echo $data[$key]['CSVDocument'];
        }

        DB::table('bank_csvs')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";



        







        

        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  BankProjectBalances
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankProjectBalances')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['bank_account_id'] 		= $data[$key]['BankAccount'];
        	$data_to_migrate[$key]['project_id'] 			= $data[$key]['Project'];
        	$data_to_migrate[$key]['balance'] 				= $data[$key]['Balance'];
        	$data_to_migrate[$key]['balance_date'] 			= $data[$key]['BalanceDate'];
        	$data_to_migrate[$key]['balance_status'] 		= $data[$key]['BalanceStatus'];
        	$data_to_migrate[$key]['balance_end_date'] 		= $data[$key]['BalanceEndDate'];
        	$data_to_migrate[$key]['balance_usd'] 			= $data[$key]['BalanceUSD'];
        	$data_to_migrate[$key]['invoice'] 				= $data[$key]['Invoice'];
        	$data_to_migrate[$key]['migration_id'] 			= $data[$key]['ID'];


        	echo "\n bank project balances -$key---";
        	echo $data[$key]['BalanceDate'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('bank_project_balances')->insert($batch);
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
         *                  BankStatement
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankStatement')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['post_date'] 		= $data[$key]['PostDate'];
        	$data_to_migrate[$key]['reference'] 		= $data[$key]['Reference'];
        	$data_to_migrate[$key]['narrative'] 		= $data[$key]['Narrative'];
        	$data_to_migrate[$key]['value_date'] 		= $data[$key]['ValueDate'];
        	$data_to_migrate[$key]['debit'] 			= $data[$key]['Debit'];
        	$data_to_migrate[$key]['credit'] 			= $data[$key]['Credit'];
        	$data_to_migrate[$key]['closingBalance'] 	= $data[$key]['ClosingBalance'];
        	$data_to_migrate[$key]['statement_month'] 	= $data[$key]['StatementMonth'];
        	$data_to_migrate[$key]['statement_year'] 	= $data[$key]['StatementYear'];
        	$data_to_migrate[$key]['bank_account'] 		= $data[$key]['BankAccount'];
        	$data_to_migrate[$key]['posted'] 			= $data[$key]['Posted'];
        	$data_to_migrate[$key]['payment'] 			= $data[$key]['Payment'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\nBank Statement-$key---";
        	echo $data[$key]['Reference'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('bank_statements')->insert($batch);
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
         *                  BankTransactions
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankTransactions')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['bank_ref'] 			= $data[$key]['BankRef'];
        	$data_to_migrate[$key]['chai_ref'] 			= $data[$key]['ClinHealthRef'];
        	$data_to_migrate[$key]['inputter'] 			= $data[$key]['Inputter'];
        	$data_to_migrate[$key]['approver'] 			= $data[$key]['Approver'];
        	$data_to_migrate[$key]['file_type'] 		= $data[$key]['FileType'];
        	$data_to_migrate[$key]['amount'] 			= $data[$key]['Amount'];
        	$data_to_migrate[$key]['file_name'] 		= $data[$key]['FileName'];
        	$data_to_migrate[$key]['txn_date'] 			= $data[$key]['TxnDate'];
        	$data_to_migrate[$key]['txn_time'] 			= $data[$key]['TxnTime'];
        	$data_to_migrate[$key]['processing_date'] 	= $data[$key]['ProcessDate'];
        	$data_to_migrate[$key]['narrative'] 		= $data[$key]['Narrative'];
        	$data_to_migrate[$key]['bank_csv'] 			= $data[$key]['BankCSV'];
        	$data_to_migrate[$key]['allocated'] 		= $data[$key]['Allocated'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\nBank Transactions-$key---";
        	echo $data[$key]['ClinHealthRef'];
        }
        
        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('bank_transactions')->insert($batch);
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
         *                  BankTransferTypes
         * 
         * 
         * 
         * 
         * 
         */



        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankTransferTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['transfer_type'] 			= $data[$key]['TransferType'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\nBank Transfer Types-$key---";
        	echo $data[$key]['TransferType'];
        }
        
        DB::table('bank_transfer_types')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";




    }
}
