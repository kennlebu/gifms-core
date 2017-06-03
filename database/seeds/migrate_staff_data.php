<?php

use Illuminate\Database\Seeder;

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
            $data_to_migrate[$key]['email']                     = $data[$key]['email'];
            $data_to_migrate[$key]['security_group_id']         = $data[$key]['SecurityGroup'];
            $data_to_migrate[$key]['password']                  = bcrypt('secret');

            $data_to_migrate[$key]['f_name']					= $data[$key]['FirstName'];
            $data_to_migrate[$key]['l_name']					= $data[$key]['LastName'];
            $data_to_migrate[$key]['post']						= $data[$key]['Post'];
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

            $data_to_migrate[$key]['migration_bank_branch_id']  = (int)$data[$key]['BankBranch'];
            $data_to_migrate[$key]['migration_bank_id']			= (int)$data[$key]['Bank'];
            $data_to_migrate[$key]['migration_department_id']	= $data[$key]['Department'];
            $data_to_migrate[$key]['migration_id']				= $data[$key]['EID'];





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
                'role_name'             => 'Super Administrator',
                'acronym'               => 's-admin'
            ],
            [
                'role_name'             => 'Administrator',
                'acronym'               => 'admin'
            ],
            [
                'role_name'             => 'Director',
                'acronym'               => 'dir'
            ],
            [
                'role_name'             => 'Associate Director',
                'acronym'               => 'a-dir'
            ],
            [
                'role_name'             => 'Financial Controller',
                'acronym'               => 'fin'
            ],
            [
                'role_name'             => 'Program Manager',
                'acronym'               => 'pm'
            ],
            [
                'role_name'             => 'Program Analyst',
                'acronym'               => 'pa'
            ],
            [
                'role_name'             => 'Accountant',
                'acronym'               => 'ac'
            ],
            [
                'role_name'             => 'Assistant Accountant',
                'acronym'               => 'a-ac'
            ],
            [
                'role_name'             => 'Office Manager',
                'acronym'               => 'om'
            ],
            [
                'role_name'             => 'Auditor',
                'acronym'               => 'a-ac'
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
         *                 Access Rights
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nRights -[ALL]---\n";

        DB::table('access_rights')->insert([

            //viewing
           [
                'right_name'            => 'View All My LPOS',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_-1',
                'entity'                => 'Lpo',
                'at_status_id'          => '-1',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View All LPOS',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_-2',
                'entity'                => 'Lpo',
                'at_status_id'          => '-2',
                'approval_level_id'     => '0'
            ],




            [
                'right_name'            => 'View My LPOS Uploaded Pending Quotations',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_1',
                'entity'                => 'Lpo',
                'at_status_id'          => '1',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My LPOS Uploaded Pending Submission',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_2',
                'entity'                => 'Lpo',
                'at_status_id'          => '2',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My LPOS Uploaded Pending PM Approval',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_3',
                'entity'                => 'Lpo',
                'at_status_id'          => '3',
                'approval_level_id'     => '2'
            ],
            [
                'right_name'            => 'View My LPOS Uploaded Pending Finance Approval',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_4',
                'entity'                => 'Lpo',
                'at_status_id'          => '4',
                'approval_level_id'     => '3'
            ],
            [
                'right_name'            => 'View My LPOS Uploaded Pending Management Approval',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_5',
                'entity'                => 'Lpo',
                'at_status_id'          => '5',
                'approval_level_id'     => '4'
            ],
            [
                'right_name'            => 'View My Approved LPOS Pending Dispatch',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_6',
                'entity'                => 'Lpo',
                'at_status_id'          => '6',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My Approved LPOS Pending Delivery',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_7',
                'entity'                => 'Lpo',
                'at_status_id'          => '7',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My Approved LPOS Pending Invoicing',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_8',
                'entity'                => 'Lpo',
                'at_status_id'          => '8',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My LPOS With Invoice Matched & Accepted',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_9',
                'entity'                => 'Lpo',
                'at_status_id'          => '9',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'View My LPOS Payment In Process',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_10',
                'entity'                => 'Lpo',
                'at_status_id'          => '10',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'View My Cancelled LPOS',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_11',
                'entity'                => 'Lpo',
                'at_status_id'          => '11',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'View My Rejected LPOS',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_12',
                'entity'                => 'Lpo',
                'at_status_id'          => '12',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'View My LPOS Uploaded Pending Management Approval',
                'operation_type'        => 'Read',
                'operation_type_abbr'   => 'READ_LPO_13',
                'entity'                => 'Lpo',
                'at_status_id'          => '13',
                'approval_level_id'     => '0'
            ],





            [
                'right_name'            => 'Create LPO',
                'operation_type'        => 'Create',
                'operation_type_abbr'   => 'CREATE_LPO',
                'entity'                => 'Lpo',
                'at_status_id'          => '0',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Create LPO On Behalf of',
                'operation_type'        => 'Create',
                'operation_type_abbr'   => 'CREATE_LPO_OBO',
                'entity'                => 'Lpo',
                'at_status_id'          => '0',
                'approval_level_id'     => '0'
            ],




            [
                'right_name'            => 'Request LPO',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'REQUEST_LPO',
                'entity'                => 'Lpo',
                'at_status_id'          => '2',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Request LPO On Behalf of',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'REQUEST_LPO_OBO',
                'entity'                => 'Lpo',
                'at_status_id'          => '2',
                'approval_level_id'     => '0'
            ],




         
           [
                'right_name'            => 'Update All My LPOS',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_-1',
                'entity'                => 'Lpo',
                'at_status_id'          => '-1',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update All LPOS',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_-2',
                'entity'                => 'Lpo',
                'at_status_id'          => '-2',
                'approval_level_id'     => '0'
            ],




            [
                'right_name'            => 'Update My LPOS Uploaded Pending Quotations',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_1',
                'entity'                => 'Lpo',
                'at_status_id'          => '1',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My LPOS Uploaded Pending Submission',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_2',
                'entity'                => 'Lpo',
                'at_status_id'          => '2',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My LPOS Uploaded Pending PM Approval',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_3',
                'entity'                => 'Lpo',
                'at_status_id'          => '3',
                'approval_level_id'     => '2'
            ],
            [
                'right_name'            => 'Update My LPOS Uploaded Pending Finance Approval',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_4',
                'entity'                => 'Lpo',
                'at_status_id'          => '4',
                'approval_level_id'     => '3'
            ],
            [
                'right_name'            => 'Update My LPOS Uploaded Pending Management Approval',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_5',
                'entity'                => 'Lpo',
                'at_status_id'          => '5',
                'approval_level_id'     => '4'
            ],
            [
                'right_name'            => 'Update My Approved LPOS Pending Dispatch',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_6',
                'entity'                => 'Lpo',
                'at_status_id'          => '6',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My Approved LPOS Pending Delivery',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_7',
                'entity'                => 'Lpo',
                'at_status_id'          => '7',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My Approved LPOS Pending Invoicing',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_8',
                'entity'                => 'Lpo',
                'at_status_id'          => '8',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My LPOS With Invoice Matched & Accepted',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_9',
                'entity'                => 'Lpo',
                'at_status_id'          => '9',
                'approval_level_id'     => '0'
            ],
            [
                'right_name'            => 'Update My LPOS Payment In Process',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_10',
                'entity'                => 'Lpo',
                'at_status_id'          => '10',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'Update My Cancelled LPOS',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_11',
                'entity'                => 'Lpo',
                'at_status_id'          => '11',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'Update My Rejected LPOS',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_12',
                'entity'                => 'Lpo',
                'at_status_id'          => '12',
                'approval_level_id'     => '0'
            ],

            [
                'right_name'            => 'Update My LPOS Uploaded Pending Management Approval',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'UPDATE_LPO_13',
                'entity'                => 'Lpo',
                'at_status_id'          => '13',
                'approval_level_id'     => '0'
            ],




            [
                'right_name'            => 'LPO PM Approval',
                'operation_type'        => 'Approval',
                'operation_type_abbr'   => 'Approve_LPO_4',
                'entity'                => 'Lpo',
                'at_status_id'          => '4',
                'approval_level_id'     => '2'
            ],
            [
                'right_name'            => 'LPO PM Approval',
                'operation_type'        => 'Approval',
                'operation_type_abbr'   => 'Approve_LPO_3',
                'entity'                => 'Lpo',
                'at_status_id'          => '3',
                'approval_level_id'     => '2'
            ],
            [
                'right_name'            => 'LPO Accountant Approval',
                'operation_type'        => 'Approval',
                'operation_type_abbr'   => 'Approve_LPO_13',
                'entity'                => 'Lpo',
                'at_status_id'          => '13',
                'approval_level_id'     => '1'
            ],
            [
                'right_name'            => 'LPO Finance Approval',
                'operation_type'        => 'Approval',
                'operation_type_abbr'   => 'Approve_LPO_4',
                'entity'                => 'Lpo',
                'at_status_id'          => '4',
                'approval_level_id'     => '3'
            ],
            [
                'right_name'            => 'LPO Management Approval',
                'operation_type'        => 'Approval',
                'operation_type_abbr'   => 'Approve_LPO_5',
                'entity'                => 'Lpo',
                'at_status_id'          => '5',
                'approval_level_id'     => '4'
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
         *                 role Rights
         * 
         * 
         * 
         * 
         * 
         */

        echo "\n Role Rights -[ALL]---\n";

        DB::table('role_access_rights')->insert([



                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '1',
                    'access_right_id'   =>  '39'
                ],

























            
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '2',
                    'access_right_id'   =>  '39'
                ],





            




















                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '3',
                    'access_right_id'   =>  '39'
                ],










































            
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '4',
                    'access_right_id'   =>  '39'
                ],






















            
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '5',
                    'access_right_id'   =>  '39'
                ],

















            
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '6',
                    'access_right_id'   =>  '39'
                ],






















            
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '7',
                    'access_right_id'   =>  '39'
                ],



















            
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '8',
                    'access_right_id'   =>  '39'
                ],


















            
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '9',
                    'access_right_id'   =>  '39'
                ],




















            
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '10',
                    'access_right_id'   =>  '39'
                ],











            
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '1'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '2'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '3'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '4'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '5'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '6'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '7'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '8'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '9'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '10'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '11'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '12'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '13'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '14'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '15'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '16'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '17'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '18'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '19'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '20'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '21'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '22'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '23'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '24'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '25'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '26'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '27'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '28'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '29'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '30'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '31'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '32'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '33'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '34'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '35'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '36'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '37'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '38'
                ],
                [
                    'role_id'           =>  '11',
                    'access_right_id'   =>  '39'
                ],




            ]);




    }
}
