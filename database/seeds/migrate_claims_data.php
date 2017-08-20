<?php

use Illuminate\Database\Seeder;

class migrate_claims_data extends Seeder
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
         *                  Claims
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('StaffClaims')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {


            $data_to_migrate[$key]['total']         						= $data[$key]['Amount'];
            $data_to_migrate[$key]['expense_desc']         					= $data[$key]['ClaimTitle'];
            $data_to_migrate[$key]['expense_purpose']         				= $data[$key]['Description'];
            $data_to_migrate[$key]['requested_at']         				    = $data[$key]['DateSubmitted'];
            $data_to_migrate[$key]['claim_document']         				= $data[$key]['ClaimDocument'];
            $data_to_migrate[$key]['allocated_at']         					= $data[$key]['AllocationDate'];
            $data_to_migrate[$key]['allocated_by_id']         				= $data[$key]['AllocatedBy'];
            $data_to_migrate[$key]['status_id']         					= $data[$key]['ClaimsStatus'];
            $data_to_migrate[$key]['rejection_reason']         				= $data[$key]['RejectReason'];
            $data_to_migrate[$key]['currency_id']         					= $data[$key]['ClaimsCurrency'];
            $data_to_migrate[$key]['payment_mode_id']         				= $data[$key]['ClaimsPaymentMode'];
            $data_to_migrate[$key]['migration_requested_by_id']				= $data[$key]['RequestedBy'];
            $data_to_migrate[$key]['migration_project_id']         			= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_project_manager_id']         	= $data[$key]['ProjectManager'];
        	$data_to_migrate[$key]['migration_id'] 							= $data[$key]['ID'];






            $data_to_migrate_fin[$key]['id']                                = $key+1;
            $data_to_migrate_fin[$key]['finance_approval']                  = 0;
            $data_to_migrate_fin[$key]['finance_approval_date']             = $data[$key]['FinanceApprovalDate'];

            $data_to_migrate_man[$key]['id']                                = $key+1;
            $data_to_migrate_man[$key]['management_approval']               = 0;
            $data_to_migrate_man[$key]['management_approval_date']          = $data[$key]['ManagementApprovalDate'];

            $data_to_migrate_pm[$key]['id']                                 = $key+1;
            $data_to_migrate_pm[$key]['pm_approval']                        = 0;
            $data_to_migrate_pm[$key]['pm_approval_date']                   = $data[$key]['PMApprovalDate'];




        	echo "\n Claim -$key---";
        	echo $data[$key]['ClaimTitle'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('claims')->insert($batch);
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
         *                  claim_approvals
         * 
         * 
         * 
         * 
         * 
         */



        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['pm_approval'],'approvable_type'=>"claims"]);
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
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['management_approval'],'approvable_type'=>"claims"]);
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




        $fin_approval=array();

        foreach ($data_to_migrate_fin as $key => $value) {

            if($value){
                array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'approvable_id' => $value['id'],'migration_approver_id' => $value['finance_approval'],'approvable_type'=>"claims"]);
            }

            echo "\n Claim Approval-$key---";
            echo $data_to_migrate_fin[$key]['finance_approval_date'];

        }


        $insertBatchs = array_chunk($fin_approval, 500);
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
         *                  claim_statuses
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('ClaimStatus')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['claim_status']             			= $data[$key]['ClaimStatus'];
            $data_to_migrate[$key]['next_status_id']                    = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['migration_status_security_level']   = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n Claims Status Status -$key---";
            echo $data[$key]['ClaimStatus'];
        }
        
        DB::table('claim_statuses')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";









    }
}
