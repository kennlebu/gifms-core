<?php

use Illuminate\Database\Seeder;

class migrate_project_programs_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	   //      SELECT s.id,s.email,pj.project_code,pj.program_id FROM Staff s
				// 	LEFT JOIN project_teams pt 
				//     ON s.id = pt.staff_id
				// 		LEFT JOIN projects pj
				//         ON pt.project_id = pj.id 
				        
				// 	WHERE pj.program_id <> 7
				        
				// GROUP BY s.email,pj.program_id
				// ORDER BY s.id ASC
	;


		$staffs = DB::table('staff')
                     ->select(DB::raw('staff.id,staff.email,projects.project_code,projects.program_id'))
                     ->leftJoin('project_teams', 'staff.id', '=', 'project_teams.staff_id')
                     ->leftJoin('projects', 'project_teams.project_id', '=', 'projects.id')
                     ->where('projects.program_id', '<>', 7)
                     ->groupBy('staff.email','projects.program_id')
                     ->orderBy('staff.id', 'asc')
                     ->get();

        // print_r($staff);die;

         foreach ($staffs as $key => $staff) {

         	DB::table('program_staffs')->insert(
				    ['program_id' => $staff["program_id"], 'staff_id' => $staff["id"]]
				);

         }
    }
}
