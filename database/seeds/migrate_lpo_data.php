<?php

use Illuminate\Database\Seeder;

class migrate_lpo_data extends Seeder
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
         *                  LPO
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPO')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {



            $data_to_migrate[$key]['chai_ref']                      = $data[$key]['OurRef'];
            $data_to_migrate[$key]['lpo_date']                      = $data[$key]['LPODate'];
            $data_to_migrate[$key]['addressee']                     = $data[$key]['Addressee'];
            $data_to_migrate[$key]['expense_desc']                  = $data[$key]['Title'];
            $data_to_migrate[$key]['expense_purpose']               = $data[$key]['Purpose'];
            $data_to_migrate[$key]['request_date']                  = $data[$key]['RequestDate'];
            $data_to_migrate[$key]['status_id']                     = $data[$key]['Status'];
            $data_to_migrate[$key]['currency_id']                   = $data[$key]['LPOCurrency'];
            $data_to_migrate[$key]['quotation']                     = $data[$key]['Quotation'];
            $data_to_migrate[$key]['supply_category']               = $data[$key]['SupplyCategory'];
            $data_to_migrate[$key]['delivery_document']             = $data[$key]['DeliveryDocument'];
            $data_to_migrate[$key]['date_delivered']                = $data[$key]['DateDelivered'];
            $data_to_migrate[$key]['meeting']                       = $data[$key]['Meeting'];
            $data_to_migrate[$key]['comments']                      = $data[$key]['Comments'];
            $data_to_migrate[$key]['preffered_supplier']            = $data[$key]['PreferredSupplier'];
            $data_to_migrate[$key]['attention']                     = $data[$key]['Attention'];
            $data_to_migrate[$key]['lpo_email']                     = $data[$key]['LPOEmail'];
            $data_to_migrate[$key]['reject_reason']                 = $data[$key]['RejectReason'];
            $data_to_migrate[$key]['quote_exempt']                  = $data[$key]['QuoteExempt'];
            $data_to_migrate[$key]['quote_exempt_explanation']      = $data[$key]['QuotesExemptExplaination'];            
            $data_to_migrate[$key]['migration_account_id']          = $data[$key]['Account'];
            $data_to_migrate[$key]['migration_project_manager_id']  = $data[$key]['ProjectManager'];
            $data_to_migrate[$key]['migration_received_by_id']      = $data[$key]['RecievedBy'];
            $data_to_migrate[$key]['migration_project_id']          = $data[$key]['Project'];
            $data_to_migrate[$key]['migration_requested_by_id']     = $data[$key]['RequestedBy'];
            $data_to_migrate[$key]['migration_supplier_id']         = $data[$key]['Supplier'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];



            $data_to_migrate_fin[$key]['id']                                = $key+1;
            $data_to_migrate_fin[$key]['finance_approval']                  = $data[$key]['FinanceApproval'];
            $data_to_migrate_fin[$key]['finance_approval_date']             = $data[$key]['FinanceApprovalDate'];

            $data_to_migrate_man[$key]['id']                                = $key+1;
            $data_to_migrate_man[$key]['management_approval']               = $data[$key]['ManagementApproval'];
            $data_to_migrate_man[$key]['management_approval_date']          = $data[$key]['ManagementApprovalDate'];

            $data_to_migrate_pm[$key]['id']                                 = $key+1;
            $data_to_migrate_pm[$key]['pm_approval']                        = $data[$key]['PMApproval'];
            $data_to_migrate_pm[$key]['pm_approval_date']                   = $data[$key]['PMApprovalDate'];

        	echo "\nLPO -$key---";
        	echo $data[$key]['Title'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpos')->insert($batch);
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
         *                  lpo_approvals
         * 
         * 
         * 
         * 
         * 
         */



        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'lpo_id' => $value['id'],'migration_approver_id' => $value['pm_approval']]);
            }

            echo "\nLPO Approval-$key---";
            echo $data_to_migrate_pm[$key]['pm_approval_date'];

        }


        $insertBatchs = array_chunk($pm_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $man_approval=array();

        foreach ($data_to_migrate_man as $key => $value) {

            if($value){
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'lpo_id' => $value['id'],'migration_approver_id' => $value['management_approval']]);
            }

            echo "\nLPO Approval-$key---";
            echo $data_to_migrate_man[$key]['management_approval_date'];

        }


        $insertBatchs = array_chunk($man_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $fin_approval=array();

        foreach ($data_to_migrate_fin as $key => $value) {

            if($value){
                array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'lpo_id' => $value['id'],'migration_approver_id' => $value['finance_approval']]);
            }

            echo "\nLPO Approval-$key---";
            echo $data_to_migrate_fin[$key]['finance_approval_date'];

        }


        $insertBatchs = array_chunk($fin_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_approvals')->insert($batch);
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
         *                  LpoItems
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LpoItems')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['item_description']      = $data[$key]['ItemDescription'];
            $data_to_migrate[$key]['unit_price']            = $data[$key]['UnitPrice'];
            $data_to_migrate[$key]['vat_inclusive']         = $data[$key]['VATInclusive'];
            $data_to_migrate[$key]['qty']                   = $data[$key]['Qty'];
            $data_to_migrate[$key]['qty_description']       = $data[$key]['QuantityDescription'];
            $data_to_migrate[$key]['quotation']             = $data[$key]['Quotation'];
            $data_to_migrate[$key]['item']                  = $data[$key]['Item'];
            $data_to_migrate[$key]['vat_charge']            = $data[$key]['VATCharge'];
            $data_to_migrate[$key]['lpo_migration_id']      = $data[$key]['LPO'];
            $data_to_migrate[$key]['migration_id']          = $data[$key]['ID'];


            echo "\n Lpo Items-$key---";
            echo $data[$key]['ItemDescription'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_items')->insert($batch);
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
         *                  LPOQuotations
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPOQuotations')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['quotation_doc']         = $data[$key]['Quotation'];
            $data_to_migrate[$key]['Supplier']              = $data[$key]['Supplier'];
            $data_to_migrate[$key]['amount']                = $data[$key]['Amount'];
            $data_to_migrate[$key]['quote_description']     = $data[$key]['QuoteDescription'];
            $data_to_migrate[$key]['quote_date']            = $data[$key]['QuoteDate'];
            $data_to_migrate[$key]['Uploaded_by']           = $data[$key]['UploadedBy'];
            $data_to_migrate[$key]['quote_option']          = $data[$key]['QuoteOption'];
            $data_to_migrate[$key]['lpo_migration_id']      = $data[$key]['LPO'];
            $data_to_migrate[$key]['migration_id']          = $data[$key]['ID'];


            echo "\n Lpo Quotations-$key---";
            echo $data[$key]['Quotation'];
        }


        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_quotations')->insert($batch);
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
         *                  LPOStatuses
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPOStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['lpo_status']                            = $data[$key]['LPOStatus'];
            $data_to_migrate[$key]['next_status']                           = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['migration_status_security_level']       = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']                          = $data[$key]['ID'];


            echo "\n Lpo Statuses-$key---";
            echo $data[$key]['LPOStatus'];
        }
        
        DB::table('lpo_statuses')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";









        
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  LPOTerms
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPOTerms')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['terms']                 = $data[$key]['Terms'];
            $data_to_migrate[$key]['lpo_migration_id']      = $data[$key]['LPO'];
            $data_to_migrate[$key]['migration_id']          = $data[$key]['ID'];


            echo "\n Lpo Terms-$key---";
            echo $data[$key]['Terms'];
        }

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('lpo_terms')->insert($batch);
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
         *                  lpo_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPOStatusView')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['lpo_status']                        = $data[$key]['LPOStatus'];
            $data_to_migrate[$key]['migration_security_level_id']       = $data[$key]['SecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n LPO  Status View -$key---";
            echo $data[$key]['LPOStatus'];
        }
        
        DB::table('lpo_viewing_permissions')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";





      
    }
}
