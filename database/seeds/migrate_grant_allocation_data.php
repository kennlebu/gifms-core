<?php

use Illuminate\Database\Seeder;

class migrate_grant_allocation_data extends Seeder
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
	     *					grant_allocations
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('DonorGrantProjects')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['migration_grant_id']  		= 	$data[$key]['DonorGrant'];
            $data_to_migrate[$key]['migration_project_id']  	= 	$data[$key]['Project'];
            $data_to_migrate[$key]['amount_allocated']  		= 	$data[$key]['GrantAllocation'];


            echo "\n Grant Allocation -$key---";
            echo $data[$key]['GrantAllocation'];
        }

        
        DB::table('grant_allocations')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";

    }
}
