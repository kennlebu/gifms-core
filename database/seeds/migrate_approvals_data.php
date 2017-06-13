<?php

use Illuminate\Database\Seeder;

class migrate_approvals_data extends Seeder
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
	     *					Approvals
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Approvals')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['approval']  		= $data[$key]['Approval'];
            $data_to_migrate[$key]['status_table']  	= $data[$key]['StatusTable'];
            $data_to_migrate[$key]['approval_table']  	= $data[$key]['ApprovalTable'];
            $data_to_migrate[$key]['status_field']  	= $data[$key]['StatusField'];
            $data_to_migrate[$key]['display_order']  	= $data[$key]['DisplayOrder'];
            $data_to_migrate[$key]['title_field']  		= $data[$key]['TitleField'];
            $data_to_migrate[$key]['payee_field']  		= $data[$key]['PayeeField'];
            $data_to_migrate[$key]['amounts_field']  	= $data[$key]['AmountField'];
            $data_to_migrate[$key]['migration_id']		= $data[$key]['ID'];


            echo "\n Approvals-$key---";
            echo $data[$key]['Approval'];
        }

        
        DB::table('approvals')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

    }
}
