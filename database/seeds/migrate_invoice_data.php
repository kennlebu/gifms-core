<?php

use Illuminate\Database\Seeder;

class migrate_invoice_data extends Seeder
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
	     *					Invoices
	     * 
	     * 
	     * 
	     * 
	     * 
	     */




        // move projects from previous db table

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Invoices')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['expense_desc']  				= $data[$key]['InvoiceTitle'];
            $data_to_migrate[$key]['expense_purpose']  				= $data[$key]['InvoicePurpose'];
            $data_to_migrate[$key]['external_ref']                  = $data[$key]['InvoiceNumber'];
            $data_to_migrate[$key]['invoice_date']                  = $data[$key]['InvoiceDate'];
            $data_to_migrate[$key]['date_raised']                   = $data[$key]['DateReceived'];
            // $data_to_migrate[$key]['raised_by_id']                  = $data[$key]['ReceivedBy'];
            // $data_to_migrate[$key]['raise_action_by_id']            = $data[$key]['ReceivedBy'];
            $data_to_migrate[$key]['total']                         = $data[$key]['InvoiceAmount'];
            $data_to_migrate[$key]['invoice_document']              = $data[$key]['InvoiceDocument'];
            $data_to_migrate[$key]['supplier_id']                   = $data[$key]['Supplier'];
            $data_to_migrate[$key]['status_id']                     = $data[$key]['InvoiceStatus'];
            $data_to_migrate[$key]['accountant_approval_date']      = $data[$key]['AccountsApprovalDate'];
            $data_to_migrate[$key]['management_approval_date']      = $data[$key]['ManagementApprovalDate'];
            $data_to_migrate[$key]['pm_approval_date']              = $data[$key]['PMApprovalDate'];
            $data_to_migrate[$key]['allocated']                     = $data[$key]['Allocated'];
            $data_to_migrate[$key]['payment_date']                  = $data[$key]['PaymentDate'];
            $data_to_migrate[$key]['reconcilliation_date']          = $data[$key]['ReconciliationDate'];
            $data_to_migrate[$key]['staff_advance']                 = $data[$key]['EmployeeAdvance'];
            $data_to_migrate[$key]['comments']                      = $data[$key]['InvoiceComments'];
            $data_to_migrate[$key]['invoice_type_id']                  = $data[$key]['InvoiceType'];
            $data_to_migrate[$key]['currency_id']                      = $data[$key]['InvoiceCurrency'];
            $data_to_migrate[$key]['reject_reason']                 = $data[$key]['RejectReason'];
            $data_to_migrate[$key]['withholding_tax']               = $data[$key]['WithHoldingTax'];
            $data_to_migrate[$key]['payment_mode_id']               = $data[$key]['InvoicePaymentMode'];
            $data_to_migrate[$key]['country_id']                    = $data[$key]['InvoiceCountry'];
            $data_to_migrate[$key]['voucher_no']                    = $data[$key]['VoucherNumber'];
            $data_to_migrate[$key]['migration_project_manager_id']  = $data[$key]['ProjectManager'];
            $data_to_migrate[$key]['migration_management_approval_id']	= $data[$key]['ManagementApproval'];
            $data_to_migrate[$key]['migration_raised_by_id']		= $data[$key]['ReceivedBy'];
            $data_to_migrate[$key]['migration_approver_id']  		= $data[$key]['Approver'];
            $data_to_migrate[$key]['migration_claim_id']			= $data[$key]['Claim'];
            $data_to_migrate[$key]['migration_lpo_id'] 				= $data[$key]['LPO'];
            $data_to_migrate[$key]['migration_advance_id']			= $data[$key]['Advance'];
            $data_to_migrate[$key]['migration_mpesa_id']  			= $data[$key]['Allowance'];
            $data_to_migrate[$key]['bank_ref_no']  					= $data[$key]['BankRefNumber'];
            $data_to_migrate[$key]['shared_cost']  					= $data[$key]['SharedCost'];
            $data_to_migrate[$key]['csv_generated']  				= $data[$key]['CSVGenerated'];
            $data_to_migrate[$key]['recurring_period']  			= $data[$key]['RecurringPeriod'];
            $data_to_migrate[$key]['recurr_end_date']  				= $data[$key]['RecurrEndDate'];
            $data_to_migrate[$key]['vat']  							= $data[$key]['vat'];
            $data_to_migrate[$key]['excise_duty']  					= $data[$key]['exciseduty'];
            $data_to_migrate[$key]['catering_levy']  				= $data[$key]['cateringlevy'];
            $data_to_migrate[$key]['zero_rated']  					= $data[$key]['zerorated'];
            $data_to_migrate[$key]['exempt_supplies']  				= $data[$key]['exemptsupplies'];
            $data_to_migrate[$key]['other_levies']  				= $data[$key]['otherlevies'];
            $data_to_migrate[$key]['other_amounts']  				= $data[$key]['otheramounts'];
            $data_to_migrate[$key]['migration_id']					= $data[$key]['ID'];




            // $data_to_migrate_fin[$key]['id']                                = $key+1;
            // $data_to_migrate_fin[$key]['finance_approval']                  = $data[$key]['FinanceApproval'];
            // $data_to_migrate_fin[$key]['finance_approval_date']             = $data[$key]['FinanceApprovalDate'];


            $data_to_migrate_acc[$key]['id']                                = $key+1;
            $data_to_migrate_acc[$key]['accounts_approval']                  = 84;
            $data_to_migrate_acc[$key]['accounts_approval_date']             = ($data[$key]['AccountsApprovalDate']=='1900-01-01 00:00:00.000')? null:$data[$key]['AccountsApprovalDate'];

            $data_to_migrate_man[$key]['id']                                = $key+1;
            $data_to_migrate_man[$key]['management_approval']               = $data[$key]['ManagementApproval'];
            $data_to_migrate_man[$key]['management_approval_date']          = ($data[$key]['ManagementApprovalDate']=='1900-01-01 00:00:00.000')? null:$data[$key]['ManagementApprovalDate'];

            $data_to_migrate_pm[$key]['id']                                 = $key+1;
            $data_to_migrate_pm[$key]['pm_approval']                        = $data[$key]['Approver'];
            $data_to_migrate_pm[$key]['pm_approval_date']                   = ($data[$key]['PMApprovalDate']=='1900-01-01 00:00:00.000')? null:$data[$key]['PMApprovalDate'];


            echo "\n Invoices-$key--";
            echo $data[$key]['InvoiceNumber'];
        }
        
        $insertBatchs = array_chunk($data_to_migrate, 500);
		foreach ($insertBatchs as $batch) {
		    DB::table('invoices')->insert($batch);
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
         *                  invoice_approvals
         * 
         * 
         * 
         * 
         * 
         */



        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['pm_approval'],'approvable_type'=>"invoices"]);
            }

            echo "\n Claim Approval-$key---";
            echo $data_to_migrate_pm[$key]['pm_approval_date'];

        }


        $insertBatchs = array_chunk($pm_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $man_approval=array();

        foreach ($data_to_migrate_man as $key => $value) {

            if($value){
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['management_approval'],'approvable_type'=>"invoices"]);
            }

            echo "\n Claim Approval-$key---";
            echo $data_to_migrate_man[$key]['management_approval_date'];

        }


        $insertBatchs = array_chunk($man_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        // $fin_approval=array();

        // foreach ($data_to_migrate_fin as $key => $value) {

        //     if($value){
        //         array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['finance_approval'],'approvable_type'=>"invoices"]);
        //     }

        //     echo "\n Claim Approval-$key---";
        //     echo $data_to_migrate_fin[$key]['finance_approval_date'];

        // }


        // $insertBatchs = array_chunk($fin_approval, 500);
        // foreach ($insertBatchs as $batch) {
        //     DB::table('approvals')->insert($batch);
        //      echo "\n-------------------------------------------------------Batch inserted\n";
        // }

        // echo "\n-----------------------------------------------------------------------------------------------------\n";









        $acc_approval=array();

        foreach ($data_to_migrate_acc as $key => $value) {

            if($value){
                array_push($acc_approval,['approval_level_id' => 1,'created_at' => $value['accounts_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['accounts_approval'],'approvable_type'=>"invoices"]);
            }

            echo "\n Claim Approval-$key---";
            echo $data_to_migrate_acc[$key]['accounts_approval_date'];

        }


        $insertBatchs = array_chunk($acc_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('approvals')->insert($batch);
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
         *                  InvoiceAllocationType
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceAllocationType')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['allocation_type_desc']        = $data[$key]['AllocationType'];
            $data_to_migrate[$key]['migration_id']                = $data[$key]['ID'];


            echo "\n InvoiceAllocationType-$key---";
            echo $data[$key]['AllocationType'];
        }
        
        DB::table('invoice_allocation_types')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";












        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  InvoiceStatuses
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['invoice_status']                        = $data[$key]['InvoiceStatus'];
            $data_to_migrate[$key]['next_status']                           = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['migration_status_security_level']       = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n InvoiceStatuses-$key---";
            echo $data[$key]['InvoiceStatus'];
        }
        
        DB::table('invoice_statuses')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";












        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  InvoiceProjectAccountAllocation
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceProjectAccountAllocation')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['amount_allocated']                  =        $data[$key]['AmountAllocated'];
            $data_to_migrate[$key]['allocation_month']                  =        $data[$key]['InvoiceAllocationMonth'];
            $data_to_migrate[$key]['allocation_year']                   =        $data[$key]['InvoiceAllocationYear'];
            $data_to_migrate[$key]['allocation_purpose']                =        $data[$key]['AllocationPurpose'];
            $data_to_migrate[$key]['percentage_allocated']              =        $data[$key]['PercentageAllocated'];
            $data_to_migrate[$key]['migration_allocated_by_id']         =        $data[$key]['AllocatedBy'];
            $data_to_migrate[$key]['migration_allocatable_id']          =        $data[$key]['Invoice'];
            $data_to_migrate[$key]['migration_project_id']              =        $data[$key]['Project'];
            $data_to_migrate[$key]['migration_account_2013_code']       =        $data[$key]['ProjectAccount'];
            $data_to_migrate[$key]['migration_account_2016_code']       =        $data[$key]['ProjectAccount2016'];
            $data_to_migrate[$key]['migration_id']                      =        $data[$key]['ID'];
            $data_to_migrate[$key]['allocatable_type']                  =        "invoices";


            echo "\n Allocations-$key---";
            echo $data[$key]['Invoice'];
        }



        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('allocations')->insert($batch);
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
         *                  InvoiceApportionmentRates
         * 
         * 
         * 
         * 
         * 
         */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceApportionmentRates')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['invoice_split']                         = $data[$key]['InvoiceSplit'];
            $data_to_migrate[$key]['migration_account_id']                  = $data[$key]['Account'];
            $data_to_migrate[$key]['migration_project_id']                  = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n InvoiceApportionmentRates-$key---";
            echo $data[$key]['InvoiceSplit'];
        }
        
        DB::table('invoice_apportionment_rates')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";






        
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  invoice_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceStatusView')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['invoice_status']                    = $data[$key]['InvoiceStatus'];
            $data_to_migrate[$key]['migration_security_level']          = $data[$key]['SecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n Invoice  Status View -$key---";
            echo $data[$key]['InvoiceStatus'];
        }
        
        DB::table('invoice_viewing_permissions')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";













        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  InvoiceTypes
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'));

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['invoice_type']      = $data[$key]['InvoiceTypes'];
            $data_to_migrate[$key]['migration_id']      = $data[$key]['ID'];


            echo "\n InvoiceTypes-$key---";
            echo $data[$key]['InvoiceTypes'];
        }
        
        DB::table('invoice_types')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";










        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  InvoiceLog
         * 
         * 
         * 
         * 
         * 
         */

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'));

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('InvoiceLog')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['invoice_date']                  = $data[$key]['InvoiceDate'];
            $data_to_migrate[$key]['invoice_amount']                = (double)$data[$key]['InvoiceAmount'];
            $data_to_migrate[$key]['log_status']                    = $data[$key]['LogStatus'];
            $data_to_migrate[$key]['invoice_no']                    = $data[$key]['InvoiceNumber'];
            $data_to_migrate[$key]['migration_supplier_id']         = $data[$key]['Supplier'];
            $data_to_migrate[$key]['migration_logged_by']           = $data[$key]['LoggedBy'];
            $data_to_migrate[$key]['migration_staff_id']            = $data[$key]['Staff'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];
            $data_to_migrate[$key]['created_at']                    = $data[$key]['LogDate'];


            echo "\n InvoiceLog-$key---";
            echo $data[$key]['InvoiceDate'];
        }
        
        DB::table('invoices_logs')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";
















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  RecurringInvoices
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('RecurringInvoices')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['recurrence_date']           = $data[$key]['RecurrenceDate'];
            $data_to_migrate[$key]['recurrence_count']          = $data[$key]['RecurrenceCount'];
            $data_to_migrate[$key]['posted']                    = $data[$key]['Posted'];
            $data_to_migrate[$key]['migration_invoice_id']      = $data[$key]['Invoice'];
            $data_to_migrate[$key]['migration_id']              = $data[$key]['ID'];


            echo "\n Recurring Invoices-$key---";
            echo $data[$key]['RecurrenceDate'];
        }
        
        DB::table('recurring_invoices')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";






    }
}
