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

            $data_to_migrate[$key]['invoice_title']  				= $data[$key]['InvoiceTitle'];
            $data_to_migrate[$key]['invoice_number']  				= $data[$key]['InvoiceNumber'];
            $data_to_migrate[$key]['invoice_date']  				= $data[$key]['InvoiceDate'];
            $data_to_migrate[$key]['date_received']  				= $data[$key]['DateReceived'];
            $data_to_migrate[$key]['received_by']  					= $data[$key]['ReceivedBy'];
            $data_to_migrate[$key]['invoice_amount']  				= $data[$key]['InvoiceAmount'];
            $data_to_migrate[$key]['invoice_document']  			= $data[$key]['InvoiceDocument'];
            $data_to_migrate[$key]['project_manager']  				= $data[$key]['ProjectManager'];
            $data_to_migrate[$key]['supplier']  					= $data[$key]['Supplier'];
            $data_to_migrate[$key]['invoice_status']  				= $data[$key]['InvoiceStatus'];
            $data_to_migrate[$key]['accountant_approval_date']  	= $data[$key]['AccountsApprovalDate'];
            $data_to_migrate[$key]['management_approval_date']		= $data[$key]['ManagementApprovalDate'];
            $data_to_migrate[$key]['allocated']  					= $data[$key]['Allocated'];
            $data_to_migrate[$key]['payment_date']  				= $data[$key]['PaymentDate'];
            $data_to_migrate[$key]['staff_advance']  			    = $data[$key]['EmployeeAdvance'];
            $data_to_migrate[$key]['reconcilliation_date']  		= $data[$key]['ReconciliationDate'];
            $data_to_migrate[$key]['invoice_comments']  			= $data[$key]['InvoiceComments'];
            $data_to_migrate[$key]['pm_approval_date']  			= $data[$key]['PMApprovalDate'];
            $data_to_migrate[$key]['invoice_type']  				= $data[$key]['InvoiceType'];
            $data_to_migrate[$key]['invoice_currency']  			= $data[$key]['InvoiceCurrency'];
            $data_to_migrate[$key]['reject_reason']  				= $data[$key]['RejectReason'];
            $data_to_migrate[$key]['withholding_tax']  				= $data[$key]['WithHoldingTax'];
            $data_to_migrate[$key]['invoice_payment_mode']			= $data[$key]['InvoicePaymentMode'];
            $data_to_migrate[$key]['invoice_country']  				= $data[$key]['InvoiceCountry'];
            $data_to_migrate[$key]['voucher_no']	  				= $data[$key]['VoucherNumber'];
            $data_to_migrate[$key]['invoice_purpose']  				= $data[$key]['InvoicePurpose'];
            $data_to_migrate[$key]['migration_management_approval']	= $data[$key]['ManagementApproval'];
            $data_to_migrate[$key]['migration_uploaded_by']			= $data[$key]['UploadedBy'];
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
            $data_to_migrate[$key]['invoice_allocation_month']          =        $data[$key]['InvoiceAllocationMonth'];
            $data_to_migrate[$key]['invoice_allocation_year']           =        $data[$key]['InvoiceAllocationYear'];
            $data_to_migrate[$key]['allocation_purpose']                =        $data[$key]['AllocationPurpose'];
            $data_to_migrate[$key]['percentage_allocated']              =        $data[$key]['PercentageAllocated'];
            $data_to_migrate[$key]['brevity']                           =        $data[$key]['Berevity'];
            $data_to_migrate[$key]['migration_allocated_by']            =        $data[$key]['AllocatedBy'];
            $data_to_migrate[$key]['migration_invoice_id']              =        $data[$key]['Invoice'];
            $data_to_migrate[$key]['migration_project_id']              =        $data[$key]['Project'];
            $data_to_migrate[$key]['migration_project_account']         =        $data[$key]['ProjectAccount'];
            $data_to_migrate[$key]['migration_project_account_2016']    =        $data[$key]['ProjectAccount2016'];
            $data_to_migrate[$key]['migration_id']                      =        $data[$key]['ID'];


            echo "\n InvoiceProjectAccountAllocation-$key---";
            echo $data[$key]['Invoice'];
        }



        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('invoice_project_account_allocations')->insert($batch);
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





    }
}
