<?php

use Illuminate\Database\Seeder;

class migrate_employees_data extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
               
        // move employeed from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Employees')->get();

        $data_to_migrate=array();



        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['f_name']					= $data[$key]['FirstName'];
            $data_to_migrate[$key]['l_name']					= $data[$key]['LastName'];
            $data_to_migrate[$key]['post']						= $data[$key]['Post'];
            $data_to_migrate[$key]['mobile_no']					= $data[$key]['Mobile'];
            $data_to_migrate[$key]['mpesa_no']					= $data[$key]['Mobile'];
            $data_to_migrate[$key]['bank_account']				= $data[$key]['BankAccount'];
            $data_to_migrate[$key]['cheque_addressee']			= $data[$key]['ChequeAddressee'];
            $data_to_migrate[$key]['payment_mode']				= $data[$key]['PaymentMode'];
            $data_to_migrate[$key]['bank_id']					= (int)$data[$key]['Bank'];
            $data_to_migrate[$key]['bank_branch_id']			= (int)$data[$key]['BankBranch'];
            $data_to_migrate[$key]['station']					= $data[$key]['Station'];
            $data_to_migrate[$key]['swift_code']				= $data[$key]['SWIFTCode'];
            $data_to_migrate[$key]['active']					= $data[$key]['Active'];
            $data_to_migrate[$key]['signature']					= $data[$key]['Signature'];
            $data_to_migrate[$key]['bank_signatory']			= $data[$key]['BankSignatory'];
            $data_to_migrate[$key]['migration_department_id']	= $data[$key]['Department'];
            $data_to_migrate[$key]['migration_user_id']			= $data[$key]['EID'];
            $data_to_migrate[$key]['migration_id']				= $data[$key]['EID'];


            $data_to_migrate_u[$key]['migration_id']			= $data[$key]['EID'];
            $data_to_migrate_u[$key]['username']				= $data[$key]['UserName'];
            $data_to_migrate_u[$key]['email']					= $data[$key]['email'];
            $data_to_migrate_u[$key]['security_group']			= $data[$key]['SecurityGroup'];
            $data_to_migrate_u[$key]['password']				= bcrypt('secret');


            echo "\n Employees---";
            echo $data[$key]['FirstName'];
        }
        
        echo "\n-----------------------------------------------------------------------------------------------------";

        DB::table('employees')->insert($data_to_migrate);
        DB::table('users')->insert($data_to_migrate_u);
    }
}
