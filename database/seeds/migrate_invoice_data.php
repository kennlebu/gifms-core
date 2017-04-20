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
            $data_to_migrate[$key]['employee_advance']  			= $data[$key]['EmployeeAdvance'];
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





    }
}
