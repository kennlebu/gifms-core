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
        
        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPO')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate_pm[$key]['id']                             = $key+1;
            $data_to_migrate_man[$key]['id']                            = $key+1;
            $data_to_migrate_fin[$key]['id']                            = $key+1;


            $data_to_migrate[$key]['chai_ref']                      = $data[$key]['OurRef'];
        	$data_to_migrate[$key]['lpo_date'] 						= $data[$key]['LPODate'];
        	$data_to_migrate[$key]['suppllier_id'] 					= $data[$key]['Supplier'];
        	$data_to_migrate[$key]['addressee'] 					= $data[$key]['Addressee'];
        	$data_to_migrate[$key]['title'] 						= $data[$key]['Title'];
        	$data_to_migrate[$key]['purpose'] 						= $data[$key]['Purpose'];
            $data_to_migrate[$key]['requested_by']                  = $data[$key]['RequestedBy'];
        	$data_to_migrate[$key]['request_date'] 					= $data[$key]['RequestDate'];

        	$data_to_migrate_pm[$key]['pm_approval'] 					 = $data[$key]['PMApproval'];
        	$data_to_migrate_pm[$key]['pm_approval_date'] 				 = $data[$key]['PMApprovalDate'];
        	$data_to_migrate_man[$key]['management_approval'] 			 = $data[$key]['ManagementApproval'];
            $data_to_migrate_man[$key]['management_approval_date']       = $data[$key]['ManagementApprovalDate'];

        	$data_to_migrate[$key]['status'] 						= $data[$key]['Status'];
        	$data_to_migrate[$key]['currency'] 						= $data[$key]['LPOCurrency'];
        	$data_to_migrate[$key]['quotation'] 					= $data[$key]['Quotation'];
        	$data_to_migrate[$key]['supply_category'] 		        = $data[$key]['SupplyCategory'];
        	$data_to_migrate[$key]['delivery_document'] 			= $data[$key]['DeliveryDocument'];
        	$data_to_migrate[$key]['date_delivered'] 				= $data[$key]['DateDelivered'];
        	$data_to_migrate[$key]['received_by'] 					= $data[$key]['RecievedBy'];
        	$data_to_migrate[$key]['meeting'] 						= $data[$key]['Meeting'];
        	$data_to_migrate[$key]['comments'] 						= $data[$key]['Comments'];
        	$data_to_migrate[$key]['preffered_supplier'] 			= $data[$key]['PreferredSupplier'];
        	$data_to_migrate[$key]['project'] 						= $data[$key]['Project'];
        	$data_to_migrate[$key]['account'] 						= $data[$key]['Account'];
        	$data_to_migrate[$key]['attention'] 					= $data[$key]['Attention'];
        	$data_to_migrate[$key]['lpo_email'] 					= $data[$key]['LPOEmail'];
        	$data_to_migrate[$key]['project_manager'] 				= $data[$key]['ProjectManager'];
        	$data_to_migrate[$key]['reject_reason'] 				= $data[$key]['RejectReason'];

        	$data_to_migrate_fin[$key]['finance_approval']                 = $data[$key]['FinanceApproval'];
        	$data_to_migrate_fin[$key]['finance_approval_date'] 		   = $data[$key]['FinanceApprovalDate'];

        	$data_to_migrate[$key]['quote_exempt'] 					= $data[$key]['QuoteExempt'];
        	$data_to_migrate[$key]['quote_exempt_explanation'] 		= $data[$key]['QuotesExemptExplaination'];
        	$data_to_migrate[$key]['migration_id'] 					= $data[$key]['ID'];


        	echo "\nLPO date :::::";
        	echo $data[$key]['Title'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpos')->insert($data_to_migrate);




        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'lpo_id' => $value['id']]);
            }

            echo "\n---LPO----pm appr date::::::::";
            echo $data_to_migrate_pm[$key]['pm_approval_date'];

        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_approvals')->insert($pm_approval);



        $man_approval=array();

        foreach ($data_to_migrate_man as $key => $value) {

            if($value){
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'lpo_id' => $value['id']]);
            }

            echo "\n---LPO---man appr date :::::: ";
            echo $data_to_migrate_man[$key]['management_approval_date'];

        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_approvals')->insert($man_approval);



        $fin_approval=array();

        foreach ($data_to_migrate_fin as $key => $value) {

            if($value){
                array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'lpo_id' => $value['id']]);
            }

            echo "\n---LPO---fin appr date:::::";
            echo $data_to_migrate_fin[$key]['finance_approval_date'];

        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_approvals')->insert($fin_approval);





         // move lpo_items from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

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


            echo "\n Lpo Items---";
            echo $data[$key]['ItemDescription'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_items')->insert($data_to_migrate);

        $migrate_keys_sql = "
                                UPDATE lpo_items i 
                                    JOIN lpos l 
                                    ON i.lpo_migration_id = l.migration_id
                                    SET i.lpo_id = l.id 
                            ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated keys___________";




         // move lpo_quotations from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

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


            echo "\n Lpo Quotations---";
            echo $data[$key]['Quotation'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_quotations')->insert($data_to_migrate);

        $migrate_keys_sql = "
                                UPDATE lpo_quotations i 
                                    JOIN lpos l 
                                    ON i.lpo_migration_id = l.migration_id
                                    SET i.lpo_id = l.id 
                            ";

        DB::statement($migrate_keys_sql);

        echo "\n ___________Migrated keys___________";



        // move lpo_statuses from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('LPOStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['lpo_status']         = $data[$key]['LPOStatus'];
            $data_to_migrate[$key]['next_status']              = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['status_security_level']                = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']          = $data[$key]['ID'];


            echo "\n Lpo Statuses---";
            echo $data[$key]['LPOStatus'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('lpo_statuses')->insert($data_to_migrate);




      
    }
}
