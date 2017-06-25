<?php

use Illuminate\Database\Seeder;

class migrate_advances_data extends Seeder
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
         *                  Advances
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('EmployeeAdvances')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {


            $data_to_migrate[$key]['total']         						= $data[$key]['AdvanceAmount'];
            $data_to_migrate[$key]['approved_total']         				= $data[$key]['AmountApproved'];
            $data_to_migrate[$key]['expense_desc']         					= $data[$key]['Description'];
            $data_to_migrate[$key]['expense_purpose']         				= $data[$key]['AdvancePurpose'];
            $data_to_migrate[$key]['requested_date']         				= $data[$key]['AdvanceRequestDate'];
            $data_to_migrate[$key]['due_date']         						= $data[$key]['AdvanceEventDate'];
            $data_to_migrate[$key]['status_id']         					= $data[$key]['AdvanceStatus'];
            $data_to_migrate[$key]['comment']         						= $data[$key]['ApprovalComment'];
            $data_to_migrate[$key]['rejection_reason']         				= $data[$key]['RejectReason'];
            $data_to_migrate[$key]['currency_id']         					= $data[$key]['AdvanceCurrency'];
            $data_to_migrate[$key]['payment_mode_id']         				= $data[$key]['AdvancePaymentMode'];
            $data_to_migrate[$key]['migration_requested_by_id']				= $data[$key]['Employee'];
            $data_to_migrate[$key]['migration_project_id']         			= $data[$key]['Project'];
            $data_to_migrate[$key]['migration_project_manager_id']         	= $data[$key]['ProjectManager'];
        	$data_to_migrate[$key]['migration_id'] 							= $data[$key]['ID'];






            $data_to_migrate_fin[$key]['id']                                = $key+1;
            $data_to_migrate_fin[$key]['finance_approval']                  = 0;
            $data_to_migrate_fin[$key]['finance_approval_date']             = $data[$key]['ManagementApprovalDate'];

            $data_to_migrate_man[$key]['id']                                = $key+1;
            $data_to_migrate_man[$key]['management_approval']               = $data[$key]['ManagementApproval'];
            $data_to_migrate_man[$key]['management_approval_date']          = $data[$key]['ManagementApprovalDate'];

            $data_to_migrate_pm[$key]['id']                                 = $key+1;
            $data_to_migrate_pm[$key]['pm_approval']                        = 0;
            $data_to_migrate_pm[$key]['pm_approval_date']                   = $data[$key]['PMApprovalDate'];




        	echo "\n Advance -$key---";
        	echo $data[$key]['AdvancePurpose'];
        }
        

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('advances')->insert($batch);
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
         *                  advance_approvals
         * 
         * 
         * 
         * 
         * 
         */



        $pm_approval=array();

        foreach ($data_to_migrate_pm as $key => $value) {

            if($value){
                array_push($pm_approval,['approval_level_id' => 2,'created_at' => $value['pm_approval_date'],'advance_id' => $value['id'],'migration_approver_id' => $value['pm_approval']]);
            }

            echo "\n Advance Approval-$key---";
            echo $data_to_migrate_pm[$key]['pm_approval_date'];

        }


        $insertBatchs = array_chunk($pm_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('advance_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $man_approval=array();

        foreach ($data_to_migrate_man as $key => $value) {

            if($value){
                array_push($man_approval,['approval_level_id' => 4,'created_at' => $value['management_approval_date'],'advance_id' => $value['id'],'migration_approver_id' => $value['management_approval']]);
            }

            echo "\n Advance Approval-$key---";
            echo $data_to_migrate_man[$key]['management_approval_date'];

        }


        $insertBatchs = array_chunk($man_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('advance_approvals')->insert($batch);
             echo "\n-------------------------------------------------------Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




        $fin_approval=array();

        foreach ($data_to_migrate_fin as $key => $value) {

            if($value){
                array_push($fin_approval,['approval_level_id' => 3,'created_at' => $value['finance_approval_date'],'advance_id' => $value['id'],'migration_approver_id' => $value['finance_approval']]);
            }

            echo "\n Advance Approval-$key---";
            echo $data_to_migrate_fin[$key]['finance_approval_date'];

        }


        $insertBatchs = array_chunk($fin_approval, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('advance_approvals')->insert($batch);
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
         *                  advance_statuses
         * 
         * 
         * 
         * 
         * 
         */


        DB::table('advance_statuses')->insert([
            ['advance_status'   => 'Requested Pending Submission',
            'next_status_id'    =>2,
            'migration_status_security_level'=> 0,
            'migration_id'=> 0
            ]
        ]);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('AdvanceStatuses')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['advance_status']             		= $data[$key]['AdvanceStatus'];
            $data_to_migrate[$key]['next_status_id']                    = $data[$key]['NextStatus'];
            $data_to_migrate[$key]['migration_status_security_level']   = $data[$key]['StatusSecurityLevel'];
            $data_to_migrate[$key]['migration_id']                      = $data[$key]['ID'];


            echo "\n Advances Status Status -$key---";
            echo $data[$key]['AdvanceStatus'];
        }
        
        DB::table('advance_statuses')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";











    }
}
