<?php

use Illuminate\Database\Seeder;

class migrate_budgeting_data extends Seeder
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
	     *					budgets
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        $data = DB::connection()->table('projects')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

            $data_to_migrate[$key]['budget_desc']  			= "Budget for ".$data[$key]['project_code'];
            $data_to_migrate[$key]['start_date']  			= $data[$key]['start_date'];
            $data_to_migrate[$key]['end_date']  			= $data[$key]['end_date'];
            $data_to_migrate[$key]['created_by_id']  		= $data[$key]['project_manager_id'];
            $data_to_migrate[$key]['create_action_by_id']  	= $data[$key]['project_manager_id'];


            echo "\n Budget -$key---";
            echo $data[$key]['project_code'];
        }

        
        DB::table('budgets')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";


















        
        /**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					budget_items
	     * 
	     * 
	     * 
	     * 
	     * 
	     */


        $data = DB::connection()->table('project_budget_accounts')->get();

        $data_to_migrate=array();

        $monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        foreach ($data as $key => $value) {
            $month = (int) $data[$key]['budget_month'];
            $month -= 1;
            $monthName = $monthNames[$month];

            $data_to_migrate[$key]['budget_item_purpose']  	= "Activities for $monthName, ".$data[$key]['budget_year'];
            $data_to_migrate[$key]['budget_id']  			= $data[$key]['project_id'];
            $data_to_migrate[$key]['project_id']  			= $data[$key]['project_id'];
            $data_to_migrate[$key]['account_id']  			= $data[$key]['account_id'];
            $data_to_migrate[$key]['amount']  				= $data[$key]['budget_amount'];
            $data_to_migrate[$key]['created_by_id']  		= "";
            $data_to_migrate[$key]['create_action_by_id']  	= "";


            echo "\n Budget -$key---";
            echo $data[$key]['budget_amount'];
        }

        
        DB::table('budget_items')->insert($data_to_migrate);
        
        echo "\n-----------------------------------------------------------------------------------------------------\n";
















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  Budget Status
         * 
         * 
         * 
         * 
         * 
         */
        echo "\nBudget Status -[ALL]---\n";

        DB::table('budget_statuses')->insert([
            [
                'budget_status'     => 'Accountant Review',
                'next_status_id'    => '2',
                'order_priority'    => '1',
                'default_status'    => '1'
            ],
            [
                'budget_status'     => 'PM Approval',
                'next_status_id'    => '',
                'order_priority'    => '2',
                'default_status'    => ''
            ]
        ]);
        echo "\n-----------------------------------------------------------------------------------------------------\n";






    }





    
}
