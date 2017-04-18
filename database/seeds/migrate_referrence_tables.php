<?php

use Illuminate\Database\Seeder;

class migrate_referrence_tables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//approval_levels
        DB::table('approval_levels')->insert([
		    ['approval_level' => 'Accountant Approval'],
		    ['approval_level' => 'PM Approval'],
		    ['approval_level' => 'Finance Approval'],
		    ['approval_level' => 'Management Approval']
		]);



    }
}
