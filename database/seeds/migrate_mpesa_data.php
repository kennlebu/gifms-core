<?php

use Illuminate\Database\Seeder;

class migrate_mpesa_data extends Seeder
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
         *                  MPESA Payments
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Allowances')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {


            $data_to_migrate[$key]['meeting']         						= $data[$key]['Meeting'];
            $data_to_migrate[$key]['requested_date']         				= $data[$key]['RequestedDate'];
            $data_to_migrate[$key]['requested_by']         					= $data[$key]['RequestedBy'];
            $data_to_migrate[$key]['mpesa_payment_type']         			= $data[$key]['AllowanceType'];
            $data_to_migrate[$key]['title']         						= $data[$key]['Title'];
            $data_to_migrate[$key]['mpesa_payment_desc']         			= $data[$key]['AllowanceDescription'];
            $data_to_migrate[$key]['payment_document']         				= $data[$key]['PayeeDocument'];
            $data_to_migrate[$key]['status']         						= $data[$key]['Status'];
            $data_to_migrate[$key]['brevity']         						= $data[$key]['Berevity'];
            $data_to_migrate[$key]['region']         						= $data[$key]['Region'];
            $data_to_migrate[$key]['county']         						= $data[$key]['County'];
            $data_to_migrate[$key]['attentendance_sheet']         			= $data[$key]['AttendanceScheet'];
            $data_to_migrate[$key]['reject_reason']         				= $data[$key]['RejectReason'];
            $data_to_migrate[$key]['migration_project_id']         			= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_account_id']         			= $data[$key]['Account'];
            $data_to_migrate[$key]['migration_invoice_id']         			= $data[$key]['Invoice'];
            $data_to_migrate[$key]['migration_project_manager']         	= $data[$key]['ProjectManager'];
        	$data_to_migrate[$key]['migration_id'] 							= $data[$key]['ID'];






            $data_to_migrate_fin[$key]['id']                                = $key+1;
            $data_to_migrate_fin[$key]['finance_approval']                  = $data[$key]['FinanceApproval'];
            $data_to_migrate_fin[$key]['finance_approval_date']             = $data[$key]['FinanceApprovalDate'];

            $data_to_migrate_man[$key]['id']                                = $key+1;
            $data_to_migrate_man[$key]['management_approval']               = $data[$key]['ManagementApproval'];
            $data_to_migrate_man[$key]['management_approval_date']          = $data[$key]['ManagementApprovalDate'];

            $data_to_migrate_pm[$key]['id']                                 = $key+1;
            $data_to_migrate_pm[$key]['pm_approval']                        = $data[$key]['PMApproval'];
            $data_to_migrate_pm[$key]['pm_approval_date']                   = $data[$key]['PMApprovalDate'];




        	echo "\n MPESA Payments -$key---";
        	echo $data[$key]['Title'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('mpesa_payments')->insert($batch);
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
         *                  mpesa_payment_approvals
         * 
         * 
         * 
         * 
         * 
         */



        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'mpesa_payment_id' => $value['id'],'migration_approver' => $value['pm_approval']]);
            }

            echo "\n Mpesa Payment Approval-$key---";
            echo $data_to_migrate_pm[$key]['pm_approval_date'];

        }


        $insertBatchs = array_chunk($pm_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('mpesa_payment_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $man_approval=array();

        foreach ($data_to_migrate_man as $key => $value) {

            if($value){
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'mpesa_payment_id' => $value['id'],'migration_approver' => $value['management_approval']]);
            }

            echo "\n Mpesa Payment Approval-$key---";
            echo $data_to_migrate_man[$key]['management_approval_date'];

        }


        $insertBatchs = array_chunk($man_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('mpesa_payment_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $fin_approval=array();

        foreach ($data_to_migrate_fin as $key => $value) {

            if($value){
                array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'mpesa_payment_id' => $value['id'],'migration_approver' => $value['finance_approval']]);
            }

            echo "\n Mpesa Payment Approval-$key---";
            echo $data_to_migrate_fin[$key]['finance_approval_date'];

        }


        $insertBatchs = array_chunk($fin_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('mpesa_payment_approvals')->insert($batch);
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
         *                  Mpesa Payees
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AllowancePayees')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['name']                          = $data[$key]['Name'];
            $data_to_migrate[$key]['registered_name']               = $data[$key]['RegisteredName'];
            $data_to_migrate[$key]['id_number']                     = $data[$key]['IDNumber'];
            $data_to_migrate[$key]['mobile_number']                 = $data[$key]['MobileNumber'];
            $data_to_migrate[$key]['amount']                        = $data[$key]['Amount'];
            $data_to_migrate[$key]['email']                         = $data[$key]['email'];
            $data_to_migrate[$key]['mpesa_withdrawal_charges']      = $data[$key]['MPESA'];
            $data_to_migrate[$key]['total']                         = $data[$key]['Total'];
            $data_to_migrate[$key]['designation']                   = $data[$key]['Designation'];
            $data_to_migrate[$key]['sub_county_id']                 = $data[$key]['SubCounty'];
            $data_to_migrate[$key]['county_id']                     = $data[$key]['County'];
            $data_to_migrate[$key]['region_id']                     = $data[$key]['Region'];
            $data_to_migrate[$key]['paid']                          = $data[$key]['Paid'];
            $data_to_migrate[$key]['payment_reference']             = $data[$key]['PaymentReference'];
            $data_to_migrate[$key]['migration_mpesa_payment_id']    = $data[$key]['AllowanceRequest'];
            $data_to_migrate[$key]['migration_id']                  = $data[$key]['ID'];


            echo "\n Mpesa Payees-$key---";
            echo $data[$key]['RegisteredName'];
        }


        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('mpesa_payees')->insert($batch);
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
         *                  mpesa_payment_statuses
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AllowanceStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['mpesa_payment_status']              = $data[$key]['AllowanceStatus'];
            $data_to_migrate[$key]['next_status']                       = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['migration_status_security_level']   = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n Allowance Status -$key---";
            echo $data[$key]['AllowanceStatus'];
        }
        
        DB::table('mpesa_payment_statuses')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";







        
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_viewing_permissions
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AllowanceStatusView')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['mpesa_payment_status']              = $data[$key]['AllowanceStatus'];
            $data_to_migrate[$key]['migration_security_level']          = $data[$key]['SecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n mpesa viewing permissions -$key---";
            echo $data[$key]['AllowanceStatus'];
        }
        
        DB::table('mpesa_viewing_permissions')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";







        
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_payment_types
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AllowanceTypes')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['allowance_type_desc']               = $data[$key]['AllowanceType'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n mpesa payment types -$key---";
            echo $data[$key]['AllowanceType'];
        }
        
        DB::table('mpesa_payment_types')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";








        
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  mpesa_tarrifs
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('MPESATarrifs')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['min_limit']                 = $data[$key]['MinLimit'];
            $data_to_migrate[$key]['max_limit']                 = $data[$key]['MaxLimit'];
            $data_to_migrate[$key]['tarrif']                    = $data[$key]['Tarrif'];
            $data_to_migrate[$key]['migration_id']              = $data[$key]['ID'];


            echo "\n mpesa payment types -$key---";
            echo $data[$key]['Tarrif'];
        }
        
        DB::table('mpesa_tarrifs')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";







    }
}
