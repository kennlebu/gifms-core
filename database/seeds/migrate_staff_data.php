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
         *                  Rights
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nRights -[ALL]---\n";

        DB::table('rights')->insert([
           [
                'right_name'            => 'View All user LPOS',
                'operation_type'        => 'Read',
                'right_abbr'            => 'READ_LPO_-0',
                'entity'                => 'Lpo',
                'at_status_id'          => '0'
            ],
            [
                'right_name'            => 'View All LPOS',
                'operation_type'        => 'Read',
                'right_abbr'            => 'READ_LPO_-1',
                'entity'                => 'Lpo',
                'at_status_id'          => '-1'
            ],
            [
                'right_name'            => 'Create LPO',
                'operation_type'        => 'Create',
                'operation_type_abbr'   => 'CREATE_LPO_-0',
                'entity'                => 'Lpo',
                'at_status_id'          => '0'
            ],
            [
                'right_name'            => 'Update created LPO details',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'CREATE_LPO_-0',
                'entity'                => 'Lpo',
                'at_status_id'          => '1'
            ],
            [
                'right_name'            => 'Update LPO with no quotes',
                'operation_type'        => 'Update',
                'operation_type_abbr'   => 'CREATE_LPO_-0',
                'entity'                => 'Lpo',
                'at_status_id'          => '1'
            ],
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";








    }
}
