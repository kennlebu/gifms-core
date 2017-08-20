<?php

use Illuminate\Database\Seeder;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\ClaimsModels\Claim;
use App\Models\AdvancesModels\Advance;

class post_migration_allocations_data extends Seeder
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
	     *					mobile_payments
	     * 
	     * 
	     * 
	     * 
	     * 
	     */



        $data = DB::table('mobile_payments')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

	        $mobile_payment = MobilePayment::find($data[$key]['id']);

            $data_to_migrate[$key]['allocatable_id']				= $data[$key]['id'];
            $data_to_migrate[$key]['allocatable_type']				= 'mobile_payments';
            $data_to_migrate[$key]['amount_allocated']				= $mobile_payment->totals;
            $data_to_migrate[$key]['allocation_month']				= $mobile_payment->created_at->month;
            $data_to_migrate[$key]['allocation_year']				= $mobile_payment->created_at->year;
            $data_to_migrate[$key]['allocation_purpose']			= $data[$key]['expense_purpose'];
            $data_to_migrate[$key]['percentage_allocated']			= 100;
            $data_to_migrate[$key]['allocated_by_id']				= $data[$key]['requested_by_id'];
            $data_to_migrate[$key]['project_id']					= $data[$key]['project_id'];
            $data_to_migrate[$key]['account_id']					= 31;


            echo "\n mobile_payments-$key---";
            echo $data[$key]['id'];
        }
        
        DB::table('allocations')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";























    	/**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					claims
	     * 
	     * 
	     * 
	     * 
	     * 
	     */



        $data = DB::table('claims')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

	        $claim = Claim::find($data[$key]['id']);

            $data_to_migrate[$key]['allocatable_id']				= $data[$key]['id'];
            $data_to_migrate[$key]['allocatable_type']				= 'claims';
            $data_to_migrate[$key]['amount_allocated']				= $claim->total;
            $data_to_migrate[$key]['allocation_month']				= $claim->created_at->month;
            $data_to_migrate[$key]['allocation_year']				= $claim->created_at->year;
            $data_to_migrate[$key]['allocation_purpose']			= $data[$key]['expense_purpose'];
            $data_to_migrate[$key]['percentage_allocated']			= 100;
            $data_to_migrate[$key]['allocated_by_id']				= $data[$key]['requested_by_id'];
            $data_to_migrate[$key]['project_id']					= $data[$key]['project_id'];
            $data_to_migrate[$key]['account_id']					= '';


            echo "\n claims-$key---";
            echo $data[$key]['id'];
        }
        
        DB::table('allocations')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";


















    	/**
	     * 
	     * 
	     * 
	     * 
	     * 
	     * 
	     *					advances
	     * 
	     * 
	     * 
	     * 
	     * 
	     */



        $data = DB::table('advances')->get();

        $data_to_migrate=array();

        foreach ($data as $key => $value) {

	        $advance = Advance::find($data[$key]['id']);

            $data_to_migrate[$key]['allocatable_id']				= $data[$key]['id'];
            $data_to_migrate[$key]['allocatable_type']				= 'advances';
            $data_to_migrate[$key]['amount_allocated']				= $advance->total;
            $data_to_migrate[$key]['allocation_month']				= $advance->created_at->month;
            $data_to_migrate[$key]['allocation_year']				= $advance->created_at->year;
            $data_to_migrate[$key]['allocation_purpose']			= $data[$key]['expense_purpose'];
            $data_to_migrate[$key]['percentage_allocated']			= 100;
            $data_to_migrate[$key]['allocated_by_id']				= $data[$key]['requested_by_id'];
            $data_to_migrate[$key]['project_id']					= '';
            $data_to_migrate[$key]['account_id']					= '';


            echo "\n advances-$key---";
            echo $data[$key]['id'];
        }
        
        DB::table('allocations')->insert($data_to_migrate);

        echo "\n-----------------------------------------------------------------------------------------------------\n";
    }
}
