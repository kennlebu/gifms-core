<?php

use Illuminate\Database\Seeder;
use App\Models\StaffModels\Permission;
use App\Models\StaffModels\Role;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;

class migrate_program_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Programs
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nPrograms -[ALL]---\n";

        DB::table('programs')->insert([
		    ['program_name' => 'HIV & AIDS, Lab Diagnostics and TB'],
		    ['program_name' => 'Essential Medicines & Pneumonia'],
		    ['program_name' => 'Malaria'],
		    ['program_name' => 'Family Planning'],
		    ['program_name' => 'Vaccines'],
		    ['program_name' => 'Cancer'],
		    ['program_name' => 'Hepatitis'],
		    ['program_name' => 'Health Systems Strengthening'], 
		    ['program_name' => 'Nutrition'],
		    ['program_name' => 'Office Operational Costs & Overheads'],
		    ['program_name' => 'Global PIDs/Programs'],
		    ['program_name' => 'Health Financing']
		]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Program managers
         * 
         * 
         * 
         * 
         * 
         */


       
        $users = User::all();

        foreach ($users as $key => $user) {
        	try {


	        	//davis
	        	// if ($user->email == 'dkarambi@clintonhealthaccess.org') {
	        	// 	 DB::table('program_managers')->insert([
				// 	    ['program_manager_id' => $user->id,'program_id' =>1],
				// 	    ['program_manager_id' => $user->id,'program_id' =>7]
				// 	]);
	        	// }
	        	//judi
	        	if ($user->email == 'jlusike@clintonhealthaccess.org') {
	        		 DB::table('program_managers')->insert(
					    ['program_manager_id' => $user->id,'program_id' =>1,'default_approver'=>1]
					);
	        	}
	        	//rosemary
	        	if ($user->email == 'rkihoto@clintonhealthaccess.org') {
	        		 DB::table('program_managers')->insert(
						['program_manager_id' => $user->id,'program_id' =>2,'default_approver'=>1],
						['program_manager_id' => $user->id,'program_id' =>7,'default_approver'=>1],
						['program_manager_id' => $user->id,'program_id' =>9,'default_approver'=>1]
					);
	        	}
	        	//patricia
	        	if ($user->email == 'pnjiri@clintonhealthaccess.org') {
	        		 DB::table('program_managers')->insert(
						['program_manager_id' => $user->id,'program_id' =>3,'default_approver'=>1],
						['program_manager_id' => $user->id,'program_id' =>6,'default_approver'=>1]
					);
	        	}
	        	//ngatia
	        	if ($user->email == 'angatia@clintonhealthaccess.org') {
	        		 DB::table('program_managers')->insert([
					    ['program_manager_id' => $user->id,'program_id' =>4,'default_approver'=>1],
					    ['program_manager_id' => $user->id,'program_id' =>5,'default_approver'=>1]
					]);
	        	}
	        	//jane
	        	if ($user->email == 'jayuma@clintonhealthaccess.org') {
	        		 DB::table('program_managers')->insert([
					    ['program_manager_id' => $user->id,'program_id' =>10,'default_approver'=>1],
					    ['program_manager_id' => $user->id,'program_id' =>11,'default_approver'=>1]
					]);
				}
				//jackson hungu
	        	if ($user->email == 'jhungu@clintonhealthaccess.org') {
					DB::table('program_managers')->insert([
					   ['program_manager_id' => $user->id,'program_id' =>8,'default_approver'=>1]
				   ]);
			   }
			   //jackson kimani
			   if ($user->email == 'jkimani@clintonhealthaccess.org') {
				DB::table('program_managers')->insert([
				   ['program_manager_id' => $user->id,'program_id' =>12,'default_approver'=>1]
			   ]);
		   }


        	} catch (Exception $e) {
        		
        	}

            echo "\n PM?-$key---";
            echo $user->email;
        }








    }
}
