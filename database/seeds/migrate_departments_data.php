<?php

use Illuminate\Database\Seeder;

class migrate_departments_data extends Seeder
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
	     *					Departments
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        $departments = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Departments')->get();

        $departments_to_migrate=array();

        foreach ($departments as $key => $value) {

        	$departments_to_migrate[$key]['department_name'] 	= $departments[$key]['Department'];
        	$departments_to_migrate[$key]['description'] 		= $departments[$key]['Description'];
        	$departments_to_migrate[$key]['acronym'] 			= $departments[$key]['Acronym'];
        	$departments_to_migrate[$key]['HOD'] 				= $departments[$key]['HOD'];
        	$departments_to_migrate[$key]['migration_id'] 		= $departments[$key]['DID'];


        	echo "\nDepartments-$key---";
        	echo $departments[$key]['Department'];
        }
        
        DB::table('departments')->insert($departments_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";





   }
}
