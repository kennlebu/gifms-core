<?php

use Illuminate\Database\Seeder;
use App\Models\StaffModels\Permission;
use App\Models\StaffModels\Role;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;

class migrate_staff_roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::all();

        foreach ($users as $key => $user) {
        	try {
        	//admin
	        	if ($user->security_group_id == 1) {
	        		$user->attachRole(1);
	        	}

	        	//fm
	        	if ($user->security_group_id == 2) {
	        		$user->attachRole(6);
	        	}

	        	//dir
	        	if ($user->security_group_id == 7) {
	        		$user->attachRole(3);
	        	}

	        	//admin-man
	        	if ($user->security_group_id == 8) {
	        		$user->attachRole(10);
	        	}

	        	//fc
	        	if ($user->security_group_id == 9) {
	        		$user->attachRole(5);
	        	}

	        	//analyst
	        	if ($user->security_group_id == 10) {
	        		$user->attachRole(7);
	        	}

	        	//acc
	        	if ($user->security_group_id == 11) {
	        		$user->attachRole(8);
	        	}

	        	//audit
	        	if ($user->security_group_id == 13) {
	        		$user->attachRole(11);
	        	}

	        	//asst-acc
	        	if ($user->security_group_id == 14) {
	        		$user->attachRole(9);
	        	}

	        	//audit
	        	if ($user->security_group_id == 15) {
	        		$user->attachRole(11);
	        	}
	        	//jack admin
	        	if ($user->email == 'jhungu@clintonhealthaccess.org') {
	        		$user->attachRole(1);
	        	}
	        	//davis pm admin
	        	if ($user->email == 'dkarambi@clintonhealthaccess.org') {
	        		$user->attachRole(6);
	        	}
        		
        	} catch (Exception $e) {
        		
        	}

            echo "\n Staff-$key---";
            echo $user->email;
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n\n";
    }
}
