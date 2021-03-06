<?php

use Illuminate\Database\Seeder;
use App\Models\StaffModels\Permission;
use App\Models\StaffModels\Role;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;

class migrate_staff_data extends Seeder
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
         *                  Staff
         * 
         * 
         * 
         * 
         * 
         */

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Employees')->get();

        $data_to_migrate=array();



        foreach ($data as $key => $value) {
            


            $data_to_migrate[$key]['username']                  = $data[$key]['UserName'];
            $data_to_migrate[$key]['email']                     = strtolower(preg_replace('/\s+/', '', $data[$key]['email']));
            $data_to_migrate[$key]['security_group_id']         = $data[$key]['SecurityGroup'];
            $data_to_migrate[$key]['password']                  = bcrypt('secret');
            $data_to_migrate[$key]['old_password']              = $data[$key]['PasswdOne'];

            $data_to_migrate[$key]['f_name']					= $data[$key]['FirstName'];
            $data_to_migrate[$key]['l_name']					= $data[$key]['LastName'];
            $data_to_migrate[$key]['official_post']				= $data[$key]['Post'];
            $data_to_migrate[$key]['mobile_no']					= $data[$key]['Mobile'];
            $data_to_migrate[$key]['mpesa_no']					= $data[$key]['Mobile'];
            $data_to_migrate[$key]['bank_account']				= $data[$key]['BankAccount'];
            $data_to_migrate[$key]['cheque_addressee']			= $data[$key]['ChequeAddressee'];
            $data_to_migrate[$key]['payment_mode_id']			= $data[$key]['PaymentMode'];
            $data_to_migrate[$key]['station']                   = $data[$key]['Station'];
            $data_to_migrate[$key]['swift_code']                = $data[$key]['SWIFTCode'];
            $data_to_migrate[$key]['active']                    = $data[$key]['Active'];
            $data_to_migrate[$key]['signature']                 = $data[$key]['Signature'];
            $data_to_migrate[$key]['bank_signatory']            = $data[$key]['BankSignatory'];

            $data_to_migrate[$key]['migration_bank_branch_id']  = $data[$key]['BankBranch'];
            $data_to_migrate[$key]['migration_bank_id']			= $data[$key]['Bank'];
            $data_to_migrate[$key]['migration_department_id']	= $data[$key]['Department'];
            $data_to_migrate[$key]['migration_id']				= $data[$key]['EID'];

            // if ( (int) $data_to_migrate[$key]['migration_id'] == 123) {//aran
            //     $data_to_migrate[$key]['security_group_id'] = 10;
            // }



            echo "\n Staff-$key---";
            echo $data[$key]['FirstName'];
        }

        DB::table('staff')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n\n";












































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Roles
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nRoles -[ALL]---\n";

        DB::table('roles')->insert([
            [
                'display_name'       => 'Super Administrator',
                'description'        => '',
                'acronym'            => 's-admin',
                'name'               => 'super-admin'
            ],
            [
                'display_name'       => 'Administrator',
                'description'        => '',
                'acronym'            => 'admin',
                'name'               => 'admin'
            ],
            [
                'display_name'       => 'Director',
                'description'        => '',
                'acronym'            => 'dir',
                'name'               => 'director'
            ],
            [
                'display_name'       => 'Associate Director',
                'description'        => '',
                'acronym'            => 'a-dir',
                'name'               => 'associate-director'
            ],
            [
                'display_name'       => 'Financial Controller',
                'description'        => '',
                'acronym'            => 'fin',
                'name'               => 'financial-controller'
            ],
            [
                'display_name'       => 'Program Manager',
                'description'        => '',
                'acronym'            => 'pm',
                'name'               => 'program-manager'
            ],
            [
                'display_name'       => 'Program Analyst',
                'description'        => '',
                'acronym'            => 'pa',
                'name'               => 'program-analyst'
            ],
            [
                'display_name'       => 'Accountant',
                'description'        => '',
                'acronym'            => 'ac',
                'name'               => 'accountant'
            ],
            [
                'display_name'       => 'Assistant Accountant',
                'description'        => '',
                'acronym'            => 'a-ac',
                'name'               => 'assistant-accountant'
            ],
            [
                'display_name'       => 'Admin Manager',
                'description'        => '',
                'acronym'            => 'am',
                'name'               => 'admin-manager'
            ],
            [
                'display_name'       => 'Auditor',
                'description'        => '',
                'acronym'            => 'au',
                'name'               => 'auditor'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";



































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Lpo
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Lpo -[ALL]---\n";

        DB::table('permissions')->insert([

            //viewing
           [
                'display_name'              => 'View All My Lpos',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_-1',
                'entity'                    => 'Lpo',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View All Lpos',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_-2',
                'entity'                    => 'Lpo',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'View My Lpos - Requested Pending Quotations',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_1',
                'entity'                    => 'Lpo',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Request Uploaded Pending Submission',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_2',
                'entity'                    => 'Lpo',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - LPO Requested Pending PM Approval ',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_3',
                'entity'                    => 'Lpo',
                'at_status_id'              => '3',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'View My Lpos - Pending Finance Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_4',
                'entity'                    => 'Lpo',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Lpos - Pending Management Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_5',
                'entity'                    => 'Lpo',
                'at_status_id'              => '5',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Lpos - Approved Pending Dispatch',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_6',
                'entity'                    => 'Lpo',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Dispatched Pending Delivery',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_7',
                'entity'                    => 'Lpo',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Delivered Pending Invoicing',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_8',
                'entity'                    => 'Lpo',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Invoice Matched & Accepted',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_9',
                'entity'                    => 'Lpo',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Payment In Process',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_10',
                'entity'                    => 'Lpo',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Cancelled',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_11',
                'entity'                    => 'Lpo',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - Rejected',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_12',
                'entity'                    => 'Lpo',
                'at_status_id'              => '12',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Lpos - LPO Requested Pending Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_13',
                'entity'                    => 'Lpo',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'View My Lpos - Paid and completed',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LPO_14',
                'entity'                    => 'Lpo',
                'at_status_id'              => '14',
                'approval_level_id'         => '0'
            ],





            [
                'display_name'              => 'Create Lpo',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_LPO',
                'entity'                    => 'Lpo',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Lpo On Behalf of',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_LPO_OBO',
                'entity'                    => 'Lpo',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Request Lpo',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_LPO',
                'entity'                    => 'Lpo',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Request Lpo On Behalf of',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_LPO_OBO',
                'entity'                    => 'Lpo',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],




         
           [
                'display_name'              => 'Update All My Lpos',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_-1',
                'entity'                    => 'Lpo',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update All Lpos',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_-2',
                'entity'                    => 'Lpo',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Update My Lpos - Requested Pending Quotations',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_1',
                'entity'                    => 'Lpo',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Request Uploaded Pending Submission',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_2',
                'entity'                    => 'Lpo',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - LPO Requested Pending PM Approval ',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_3',
                'entity'                    => 'Lpo',
                'at_status_id'              => '3',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Update My Lpos - Pending Finance Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_4',
                'entity'                    => 'Lpo',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Lpos - Pending Management Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_5',
                'entity'                    => 'Lpo',
                'at_status_id'              => '5',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Lpos - Approved Pending Dispatch',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_6',
                'entity'                    => 'Lpo',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Dispatched Pending Delivery',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_7',
                'entity'                    => 'Lpo',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Delivered Pending Invoicing',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_8',
                'entity'                    => 'Lpo',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Invoice Matched & Accepted',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_9',
                'entity'                    => 'Lpo',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Payment In Process',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_10',
                'entity'                    => 'Lpo',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Cancelled',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_11',
                'entity'                    => 'Lpo',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - Rejected',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_12',
                'entity'                    => 'Lpo',
                'at_status_id'              => '12',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Lpos - LPO Requested Pending Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_13',
                'entity'                    => 'Lpo',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Update My Lpos - Paid and completed',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LPO_14',
                'entity'                    => 'Lpo',
                'at_status_id'              => '14',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Lpo PM Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_LPO_3',
                'entity'                    => 'Lpo',
                'at_status_id'              => '3',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Lpo Accountant Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_LPO_13',
                'entity'                    => 'Lpo',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Lpo Finance Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_LPO_4',
                'entity'                    => 'Lpo',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Lpo Management Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_LPO_5',
                'entity'                    => 'Lpo',
                'at_status_id'              => '5',
                'approval_level_id'         => '4'
            ],








            [
                'display_name'              => 'Delete My Lpos - Requested Pending Quotations',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_1',
                'entity'                    => 'Lpo',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Request Uploaded Pending Submission',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_2',
                'entity'                    => 'Lpo',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - LPO Requested Pending PM Approval ',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_3',
                'entity'                    => 'Lpo',
                'at_status_id'              => '3',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Delete My Lpos - Pending Finance Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_4',
                'entity'                    => 'Lpo',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Lpos - Pending Management Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_5',
                'entity'                    => 'Lpo',
                'at_status_id'              => '5',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Lpos - Approved Pending Dispatch',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_6',
                'entity'                    => 'Lpo',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Dispatched Pending Delivery',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_7',
                'entity'                    => 'Lpo',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Delivered Pending Invoicing',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_8',
                'entity'                    => 'Lpo',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Invoice Matched & Accepted',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_9',
                'entity'                    => 'Lpo',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Payment In Process',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_10',
                'entity'                    => 'Lpo',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Cancelled',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_11',
                'entity'                    => 'Lpo',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - Rejected',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_12',
                'entity'                    => 'Lpo',
                'at_status_id'              => '12',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Lpos - LPO Requested Pending Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_13',
                'entity'                    => 'Lpo',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Delete My Lpos - Paid and completed',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LPO_14',
                'entity'                    => 'Lpo',
                'at_status_id'              => '14',
                'approval_level_id'         => '0'
            ]



        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";






































































































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Invoice
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Invoice -[ALL]---\n";

        DB::table('permissions')->insert([

            //viewing
           [
                'display_name'              => 'View All My Invoices',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_-1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View All Invoices',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_-2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'View My Invoices - Allocated Pending PM Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '1',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'View My Invoices - Allocated Pending Finance Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Invoices - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_3',
                'entity'                    => 'Invoice',
                'at_status_id'              => '3',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Invoices - Approved by Director Pending Finance CSV Creation',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_4',
                'entity'                    => 'Invoice',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Invoices - CSV Generated for Upload',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_5',
                'entity'                    => 'Invoice',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - CSV At Office Admin/Accounts for Upload',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_6',
                'entity'                    => 'Invoice',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - CSV Uploaded to Bank',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_7',
                'entity'                    => 'Invoice',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - Paid',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_8',
                'entity'                    => 'Invoice',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - Rejected',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_9',
                'entity'                    => 'Invoice',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - Uploaded Pending Submission',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_10',
                'entity'                    => 'Invoice',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - Received pending upload',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_11',
                'entity'                    => 'Invoice',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Invoices - Allocated Pending Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_INVOICE_12',
                'entity'                    => 'Invoice',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],





            [
                'display_name'              => 'Create Invoice',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_INVOICE',
                'entity'                    => 'Invoice',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Invoice On Behalf of',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_INVOICE_OBO',
                'entity'                    => 'Invoice',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Request Invoice',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_INVOICE',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Request Invoice On Behalf of',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_INVOICE_OBO',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],




         
           [
                'display_name'              => 'Update All My Invoices',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_-1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update All Invoices',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_-2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




             [
                'display_name'              => 'Update My Invoices - Allocated Pending PM Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '1',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Update My Invoices - Allocated Pending Finance Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Invoices - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_3',
                'entity'                    => 'Invoice',
                'at_status_id'              => '3',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Invoices - Approved by Director Pending Finance CSV Creation',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_4',
                'entity'                    => 'Invoice',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Invoices - CSV Generated for Upload',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_5',
                'entity'                    => 'Invoice',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - CSV At Office Admin/Accounts for Upload',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_6',
                'entity'                    => 'Invoice',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - CSV Uploaded to Bank',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_7',
                'entity'                    => 'Invoice',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - Paid',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_8',
                'entity'                    => 'Invoice',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - Rejected',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_9',
                'entity'                    => 'Invoice',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - Uploaded Pending Submission',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_10',
                'entity'                    => 'Invoice',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - Received pending upload',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_11',
                'entity'                    => 'Invoice',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Invoices - Allocated Pending Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_INVOICE_12',
                'entity'                    => 'Invoice',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],




            [
                'display_name'              => 'Invoice PM Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_INVOICE_1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '1',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Invoice Accountant Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_INVOICE_12',
                'entity'                    => 'Invoice',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Invoice Finance Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_INVOICE_2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Invoice Management Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_INVOICE_3',
                'entity'                    => 'Invoice',
                'at_status_id'              => '3',
                'approval_level_id'         => '4'
            ],




            [
                'display_name'              => 'Delete My Invoices - Allocated Pending PM Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_1',
                'entity'                    => 'Invoice',
                'at_status_id'              => '1',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Delete My Invoices - Allocated Pending Finance Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_2',
                'entity'                    => 'Invoice',
                'at_status_id'              => '2',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Invoices - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_3',
                'entity'                    => 'Invoice',
                'at_status_id'              => '3',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Invoices - Approved by Director Pending Finance CSV Creation',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_4',
                'entity'                    => 'Invoice',
                'at_status_id'              => '4',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Invoices - CSV Generated for Upload',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_5',
                'entity'                    => 'Invoice',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - CSV At Office Admin/Accounts for Upload',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_6',
                'entity'                    => 'Invoice',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - CSV Uploaded to Bank',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_7',
                'entity'                    => 'Invoice',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - Paid',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_8',
                'entity'                    => 'Invoice',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - Rejected',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_9',
                'entity'                    => 'Invoice',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - Uploaded Pending Submission',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_10',
                'entity'                    => 'Invoice',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - Received pending upload',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_11',
                'entity'                    => 'Invoice',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Invoices - Allocated Pending Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_INVOICE_12',
                'entity'                    => 'Invoice',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";









































































































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Claim 
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Claim -[ALL]---\n";

        DB::table('permissions')->insert([

            //viewing
           [
                'display_name'              => 'View All My Claims',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_-1',
                'entity'                    => 'Claim',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View All Claims',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_-2',
                'entity'                    => 'Claim',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'View My Claims - Compiled Pending Submission',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_1',
                'entity'                    => 'Claim',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - Submitted Pending PM Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_2',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'View My Claims - Approved by PM Pending Finance Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_3',
                'entity'                    => 'Claim',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Claims - Approved by Finance Pending Directors Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_4',
                'entity'                    => 'Claim',
                'at_status_id'              => '4',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Claims - Approved by Director Pending Payment',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_5',
                'entity'                    => 'Claim',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - CSV Generated for Payment',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_6',
                'entity'                    => 'Claim',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - Uploaded to Bank And Paid',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_7',
                'entity'                    => 'Claim',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - Verified, Documented and Archived',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_8',
                'entity'                    => 'Claim',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - Rejected',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_9',
                'entity'                    => 'Claim',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Claims - Pending Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_CLAIM_10',
                'entity'                    => 'Claim',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],





            [
                'display_name'              => 'Create Claim ',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_CLAIM',
                'entity'                    => 'Claim',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Claim On Behalf of',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_CLAIM_OBO',
                'entity'                    => 'Claim',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Request Claim ',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_CLAIM',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Request Claim On Behalf of',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_CLAIM_OBO',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],




         
           [
                'display_name'              => 'Update All My Claims',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_-1',
                'entity'                    => 'Claim',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update All Claims',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_-2',
                'entity'                    => 'Claim',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Update My Claims - Compiled Pending Submission',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_1',
                'entity'                    => 'Claim',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - Submitted Pending PM Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_2',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Update My Claims - Approved by PM Pending Finance Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_3',
                'entity'                    => 'Claim',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Claims - Approved by Finance Pending Directors Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_4',
                'entity'                    => 'Claim',
                'at_status_id'              => '4',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Claims - Approved by Director Pending Payment',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_5',
                'entity'                    => 'Claim',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - CSV Generated for Payment',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_6',
                'entity'                    => 'Claim',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - Uploaded to Bank And Paid',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_7',
                'entity'                    => 'Claim',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - Verified, Documented and Archived',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_8',
                'entity'                    => 'Claim',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - Rejected',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_9',
                'entity'                    => 'Claim',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Claims - Pending Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_CLAIM_10',
                'entity'                    => 'Claim',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],




            [
                'display_name'              => 'Claim PM Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_CLAIM_2',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Claim Accountant Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_CLAIM_10',
                'entity'                    => 'Claim',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Claim Finance Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_CLAIM_3',
                'entity'                    => 'Claim',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Claim Management Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_CLAIM_4',
                'entity'                    => 'Claim',
                'at_status_id'              => '4',
                'approval_level_id'         => '4'
            ],




            [
                'display_name'              => 'Delete My Claims - Compiled Pending Submission',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_1',
                'entity'                    => 'Claim',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - Submitted Pending PM Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_2',
                'entity'                    => 'Claim',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Delete My Claims - Approved by PM Pending Finance Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_3',
                'entity'                    => 'Claim',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Claims - Approved by Finance Pending Directors Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_4',
                'entity'                    => 'Claim',
                'at_status_id'              => '4',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Claims - Approved by Director Pending Payment',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_5',
                'entity'                    => 'Claim',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - CSV Generated for Payment',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_6',
                'entity'                    => 'Claim',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - Uploaded to Bank And Paid',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_7',
                'entity'                    => 'Claim',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - Verified, Documented and Archived',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_8',
                'entity'                    => 'Claim',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - Rejected',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_9',
                'entity'                    => 'Claim',
                'at_status_id'              => '9',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Claims - Pending Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_CLAIM_10',
                'entity'                    => 'Claim',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";













































































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Mobile Payment
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Mobile Payment -[ALL]---\n";

        DB::table('permissions')->insert([

            //viewing
           [
                'display_name'              => 'View All My Mobile Payments',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_-1',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View All Mobile Payments',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_-2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'View My Mobile Payments - Mobile Payment Requested Pending Submission',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_1',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Mobile Payment Requested Pending PM Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Approved By PM Pending Finance Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_3',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Approved by Management And Sent',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_4',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Pending Documentation & Verification',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_5',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Verified, Documented and Archived',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_6',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Rejected',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_7',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_8',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '8',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Mobile Payment Requested Pending Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_9',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '9',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Rejected by bank Awaiting Accountant Confirmation',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_10',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Rejected By Bank Awaiting Corrections',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_11',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Corrected Awaiting Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_12',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'View My Mobile Payments - Corrected And Resent to Bank',
                'operation_type'            => 'Read',
                'name'                      => 'READ_MOBILE_PAYMENT_13',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '13',
                'approval_level_id'         => '0'
            ],





            [
                'display_name'              => 'Create Mobile Payment',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_MOBILE_PAYMENT',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Mobile Payment On Behalf of',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_MOBILE_PAYMENT_OBO',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Request Mobile Payment',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_MOBILE_PAYMENT',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Request Mobile Payment On Behalf of',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_MOBILE_PAYMENT_OBO',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],




         
           [
                'display_name'              => 'Update All My Mobile Payments',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_-1',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update All Mobile Payments',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_-2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Update My Mobile Payments - Mobile Payment Requested Pending Submission',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_1',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Mobile Payment Requested Pending PM Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Approved By PM Pending Finance Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_3',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Approved by Management And Sent',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_4',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Pending Documentation & Verification',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_5',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Verified, Documented and Archived',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_6',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Rejected',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_7',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_8',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '8',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Mobile Payment Requested Pending Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_9',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '9',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Rejected by bank Awaiting Accountant Confirmation',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_10',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Rejected By Bank Awaiting Corrections',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_11',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Corrected Awaiting Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_12',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Update My Mobile Payments - Corrected And Resent to Bank',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_MOBILE_PAYMENT_13',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '13',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Mobile Payment PM Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_MOBILE_PAYMENT_2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Mobile Payment Accountant Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_MOBILE_PAYMENT_9',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '9',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Mobile Payment Finance Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_MOBILE_PAYMENT_3',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Mobile Payment Management Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_MOBILE_PAYMENT_8',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '8',
                'approval_level_id'         => '4'
            ],




            [
                'display_name'              => 'Delete My Mobile Payments - Mobile Payment Requested Pending Submission',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_1',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Mobile Payment Requested Pending PM Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_2',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Approved By PM Pending Finance Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_3',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Approved by Management And Sent',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_4',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Pending Documentation & Verification',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_5',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Verified, Documented and Archived',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_6',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Rejected',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_7',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Approved by Finance Pending Management Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_8',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '8',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Mobile Payment Requested Pending Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_9',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '9',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Rejected by bank Awaiting Accountant Confirmation',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_10',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '10',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Rejected By Bank Awaiting Corrections',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_11',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Corrected Awaiting Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_12',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '12',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Delete My Mobile Payments - Corrected And Resent to Bank',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_MOBILE_PAYMENT_13',
                'entity'                    => 'MobilePayment',
                'at_status_id'              => '13',
                'approval_level_id'         => '0'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";







































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Advance
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Advance -[ALL]---\n";

        DB::table('permissions')->insert([

            //viewing
           [
                'display_name'              => 'View All My Advances',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_-1',
                'entity'                    => 'Advance',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View All Advances',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_-2',
                'entity'                    => 'Advance',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'View My Advances - Requested Pending Submission',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_1',
                'entity'                    => 'Advance',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Requested Pending PM Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_2',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'View My Advances - Pending Finance Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_3',
                'entity'                    => 'Advance',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'View My Advances - Approved by Director',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_4',
                'entity'                    => 'Advance',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - CSV Generated for Upload',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_5',
                'entity'                    => 'Advance',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Issued & Paid',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_6',
                'entity'                    => 'Advance',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Open',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_7',
                'entity'                    => 'Advance',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Submitted Pending Approval By PM',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_8',
                'entity'                    => 'Advance',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Submitted Pending Directors Approval ',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_9',
                'entity'                    => 'Advance',
                'at_status_id'              => '9',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Advances - Reconciled',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_10',
                'entity'                    => 'Advance',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Rejected',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_11',
                'entity'                    => 'Advance',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'View My Advances - Pending Management Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_12',
                'entity'                    => 'Advance',
                'at_status_id'              => '12',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'View My Advances - Pending Accountant Approval',
                'operation_type'            => 'Read',
                'name'                      => 'READ_ADVANCE_13',
                'entity'                    => 'Advance',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],





            [
                'display_name'              => 'Create Advance',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_ADVANCE',
                'entity'                    => 'Advance',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Advance On Behalf of',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_ADVANCE_OBO',
                'entity'                    => 'Advance',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Request Advance',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_ADVANCE',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Request Advance On Behalf of',
                'operation_type'            => 'Update',
                'name'                      => 'REQUEST_ADVANCE_OBO',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '0'
            ],




         
           [
                'display_name'              => 'Update All My Advances',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_-1',
                'entity'                    => 'Advance',
                'at_status_id'              => '-1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update All Advances',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_-2',
                'entity'                    => 'Advance',
                'at_status_id'              => '-2',
                'approval_level_id'         => '0'
            ],




            [
                'display_name'              => 'Update My Advances - Requested Pending Submission',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_1',
                'entity'                    => 'Advance',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Requested Pending PM Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_2',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Update My Advances - Pending Finance Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_3',
                'entity'                    => 'Advance',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Update My Advances - Approved by Director',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_4',
                'entity'                    => 'Advance',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - CSV Generated for Upload',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_5',
                'entity'                    => 'Advance',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Issued & Paid',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_6',
                'entity'                    => 'Advance',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Open',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_7',
                'entity'                    => 'Advance',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Submitted Pending Approval By PM',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_8',
                'entity'                    => 'Advance',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Submitted Pending Directors Approval ',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_9',
                'entity'                    => 'Advance',
                'at_status_id'              => '9',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Advances - Reconciled',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_10',
                'entity'                    => 'Advance',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Rejected',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_11',
                'entity'                    => 'Advance',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update My Advances - Pending Management Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_12',
                'entity'                    => 'Advance',
                'at_status_id'              => '12',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Update My Advances - Pending Accountant Approval',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_ADVANCE_13',
                'entity'                    => 'Advance',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],




            [
                'display_name'              => 'Advance PM Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_ADVANCE_2',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Advance Accountant Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_ADVANCE_13',
                'entity'                    => 'Advance',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ],
            [
                'display_name'              => 'Advance Finance Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_ADVANCE_3',
                'entity'                    => 'Advance',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Advance Management Approval',
                'operation_type'            => 'Approval',
                'name'                      => 'APPROVE_ADVANCE_4',
                'entity'                    => 'Advance',
                'at_status_id'              => '4',
                'approval_level_id'         => '4'
            ],




            [
                'display_name'              => 'Delete My Advances - Requested Pending Submission',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_1',
                'entity'                    => 'Advance',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Requested Pending PM Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_2',
                'entity'                    => 'Advance',
                'at_status_id'              => '2',
                'approval_level_id'         => '2'
            ],
            [
                'display_name'              => 'Delete My Advances - Pending Finance Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_3',
                'entity'                    => 'Advance',
                'at_status_id'              => '3',
                'approval_level_id'         => '3'
            ],
            [
                'display_name'              => 'Delete My Advances - Approved by Director',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_4',
                'entity'                    => 'Advance',
                'at_status_id'              => '4',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - CSV Generated for Upload',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_5',
                'entity'                    => 'Advance',
                'at_status_id'              => '5',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Issued & Paid',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_6',
                'entity'                    => 'Advance',
                'at_status_id'              => '6',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Open',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_7',
                'entity'                    => 'Advance',
                'at_status_id'              => '7',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Submitted Pending Approval By PM',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_8',
                'entity'                    => 'Advance',
                'at_status_id'              => '8',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Submitted Pending Directors Approval ',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_9',
                'entity'                    => 'Advance',
                'at_status_id'              => '9',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Advances - Reconciled',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_10',
                'entity'                    => 'Advance',
                'at_status_id'              => '10',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Rejected',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_11',
                'entity'                    => 'Advance',
                'at_status_id'              => '11',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete My Advances - Pending Management Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_12',
                'entity'                    => 'Advance',
                'at_status_id'              => '12',
                'approval_level_id'         => '4'
            ],
            [
                'display_name'              => 'Delete My Advances - Pending Accountant Approval',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_ADVANCE_13',
                'entity'                    => 'Advance',
                'at_status_id'              => '13',
                'approval_level_id'         => '1'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";
















































        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                 Permissions Lookup data
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPermissions Lookup data -[ALL]---\n";

        DB::table('permissions')->insert([

            //generel lookup data

            [
                'display_name'              => 'View Lookup Data',
                'operation_type'            => 'Read',
                'name'                      => 'READ_LOOKUP_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '1',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Create Lookup Data',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_LOOKUP_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update Lookup Data',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_LOOKUP_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete Lookup Data',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_LOOKUP_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],



            [
                'display_name'              => 'Create Suppliers Data',
                'operation_type'            => 'Create',
                'name'                      => 'CREATE_SUPPLIERS_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Update Suppliers Data',
                'operation_type'            => 'Update',
                'name'                      => 'UPDATE_SUPPLIERS_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ],
            [
                'display_name'              => 'Delete Suppliers Data',
                'operation_type'            => 'Delete',
                'name'                      => 'DELETE_SUPPLIERS_DATA',
                'entity'                    => 'lookup',
                'at_status_id'              => '0',
                'approval_level_id'         => '0'
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";




























    }
}
