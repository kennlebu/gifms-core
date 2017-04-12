<?php

use Illuminate\Database\Seeder;

class migrate_banking_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()


    {
         // move banks from previous db table

        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Banks')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['bank_name'] 		= $data[$key]['BankName'];
        	$data_to_migrate[$key]['bank_code'] 		= $data[$key]['BankCode'];
        	$data_to_migrate[$key]['swift_code'] 		= $data[$key]['SWIFTCode'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\n";
        	echo $data[$key]['BankName'];
        }

        DB::table('banks')->insert($data_to_migrate);





         // move bank branchess from previous db table


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('BankBranches')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

        	$data_to_migrate[$key]['bank_id'] 			= $data[$key]['Bank'];
        	$data_to_migrate[$key]['branch_name'] 		= $data[$key]['BankBranch'];
        	$data_to_migrate[$key]['branch_code'] 		= $data[$key]['BranchCode'];
        	$data_to_migrate[$key]['migration_id'] 		= $data[$key]['ID'];


        	echo "\n";
        	echo $data[$key]['BankBranch'];
        }

        DB::table('bank_branches')->insert($data_to_migrate);



    }
}
