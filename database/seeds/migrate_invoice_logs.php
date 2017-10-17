<?php

use Illuminate\Database\Seeder;
use App\Models\InvoicesModels\Invoice;
use App\Models\ApprovalsModels\Approval;
use App\Models\AllocationModels\Allocation;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentVoucher;

class migrate_invoice_logs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	ini_set('memory_limit', '-1');









    	
    	
    	$invoices 				= 	Invoice::all();
    	$approvals 				= 	Approval::where("approvable_type",'invoices')->get();
    	$allocations 			= 	Allocation::where("allocatable_type",'invoices')->get();
    	$payments 				= 	Payment::where("payable_type",'invoices')->get();
    	$payment_vouchers 		= 	PaymentVoucher::where("vouchable_type",'invoices')->get();





















        $data_to_migrate=array();
    	foreach ($invoices as $key => $invoice) {
    		
            $data_to_migrate[$key]['log_name']  				=		'default';
            $data_to_migrate[$key]['description']  				=		'created';
            $data_to_migrate[$key]['subject_id']  				=		$invoice->id;
            $data_to_migrate[$key]['subject_type']  			=		'invoices';
            $data_to_migrate[$key]['causer_id']  				=		$invoice->raised_by_id;
            $data_to_migrate[$key]['causer_type']  				=		"staff";
            $data_to_migrate[$key]['properties']  				=		"[]";
            $data_to_migrate[$key]['created_at']  				=		$invoice->created_at;
            $data_to_migrate[$key]['updated_at']  				=		$invoice->created_at;

            echo "\n Invoices-$key--";
            echo $invoice->external_ref;
    	}

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('activity_log')->insert($batch);
             echo "\n-------------------------------------------------------Log Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";




























        $data_to_migrate=array();
    	foreach ($approvals as $key => $approval) {
    		
            $data_to_migrate[$key]['log_name']  				=		'default';
            $data_to_migrate[$key]['description']  				=		'approved';
            try{
            	$data_to_migrate[$key]['subject_id'] 			=		$approval->approvable->id;
            } catch (Exception $e){
            	$data_to_migrate[$key]['subject_id'] 			=		0;
            }
            $data_to_migrate[$key]['subject_type']  			=		'invoices';
            $data_to_migrate[$key]['causer_id']  				=		$approval->approver_id;
            $data_to_migrate[$key]['causer_type']  				=		"staff";
            $data_to_migrate[$key]['properties']  				=		"[]";
            $data_to_migrate[$key]['created_at']  				=		$approval->created_at;
            $data_to_migrate[$key]['updated_at']  				=		$approval->created_at;

            echo "\n Invoices approvals-$key--";
    	}

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('activity_log')->insert($batch);
             echo "\n-------------------------------------------------------Log Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";

























        $data_to_migrate=array();
    	foreach ($allocations as $key => $allocation) {
    		
            $data_to_migrate[$key]['log_name']  				=		'default';
            $data_to_migrate[$key]['description']  				=		'allocated';
            try{
            	$data_to_migrate[$key]['subject_id'] 			=		$allocation->allocatable->id;
            } catch (Exception $e){
            	$data_to_migrate[$key]['subject_id'] 			=		0;
            }
            $data_to_migrate[$key]['subject_type']  			=		'invoices';
            $data_to_migrate[$key]['causer_id']  				=		$allocation->allocated_by_id;
            $data_to_migrate[$key]['causer_type']  				=		"staff";
            $data_to_migrate[$key]['properties']  				=		"[]";
            try{
	            $data_to_migrate[$key]['created_at']  			=		$allocation->created_at;
	            $data_to_migrate[$key]['updated_at']  			=		$allocation->created_at;
            } catch (Exception $e){
	            $data_to_migrate[$key]['created_at']  			=		0;
	            $data_to_migrate[$key]['updated_at']  			=		0;
            }

            echo "\n Invoices allocations -$key--";
            echo $invoice->external_ref;
    	}

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('activity_log')->insert($batch);
             echo "\n-------------------------------------------------------Log Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";



























        $data_to_migrate=array();
    	foreach ($payment_vouchers as $key => $voucher) {
    		
            $data_to_migrate[$key]['log_name']  				=		'default';
            $data_to_migrate[$key]['description']  				=		'generated voucher';
            try{
            	$data_to_migrate[$key]['subject_id'] 			=		$voucher->vouchable->id;
            } catch (Exception $e){
            	$data_to_migrate[$key]['subject_id'] 			=		0;
            }
            $data_to_migrate[$key]['causer_id']  				=		39;
            $data_to_migrate[$key]['causer_type']  				=		"staff";
            $data_to_migrate[$key]['properties']  				=		"[]";
            $data_to_migrate[$key]['created_at']  				=		$voucher->created_at;
            $data_to_migrate[$key]['updated_at']  				=		$voucher->created_at;

            echo "\n Invoices payment voucher -$key--";
            echo $invoice->external_ref;
    	}

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('activity_log')->insert($batch);
             echo "\n-------------------------------------------------------Log Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";
























        $data_to_migrate=array();
    	foreach ($payments as $key => $payment) {
    		
            $data_to_migrate[$key]['log_name']  				=		'default';
            $data_to_migrate[$key]['description']  				=		'paid';
            try{
            	$data_to_migrate[$key]['subject_id'] 			=		$payment->payable->id;
            } catch (Exception $e){
            	$data_to_migrate[$key]['subject_id'] 			=		0;
            }
            $data_to_migrate[$key]['causer_id']  				=		36;
            $data_to_migrate[$key]['causer_type']  				=		"staff";
            $data_to_migrate[$key]['properties']  				=		"[]";
            $data_to_migrate[$key]['created_at']  				=		$payment->created_at;
            $data_to_migrate[$key]['updated_at']  				=		$payment->created_at;

            echo "\n Invoicespayment -$key--";
            echo $invoice->external_ref;
    	}

        $insertBatchs = array_chunk($data_to_migrate, 500);
        foreach ($insertBatchs as $batch) {
            DB::table('activity_log')->insert($batch);
             echo "\n-------------------------------------------------------Log Batch inserted\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";
























    }
}
