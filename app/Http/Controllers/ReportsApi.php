<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * Contact: kennlebu@live.com
 *
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App;
use Excel;
use Illuminate\Support\Facades\Response;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\Payment;
use App\Models\AllocationModels\Allocation;
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use App\Models\GrantModels\Grant;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;

class ReportsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }




    /**
     * Operation get2016Report
     *
     * Get 2016 report.
     *
     *
     * @return Http response
     */
    public function get2016Report()
    {
        $form = Request::only(
            'start_date',
            'end_date',
            'currency',
            'operation'
            );

        $fromDate = $form['start_date'];
        $toDate = $form['end_date'];
        $operation = $form['operation'];
        $currency = 0;
        if($form['currency'] == 'kes') $currency = 1;
        elseif($form['currency'] == 'usd') $currency = 2;

        $report_data = [];
        
        $result = [
            'vendor_name'=>'',
            'date_posted'=>'',
            'project'=>'',
            'program_id'=>'',
            'program_desc'=>'',
            'grant'=>'',
            'invoice_title'=>'',
            'account_number'=>'',
            'account_description'=>'',
            'general_jr'=>'',
            'specific_jr'=>'',
            'allocation_amount'=>'',
            'voucher_number'=>'',
            'total_amount'=>'',
            'transaction_type'=>'',
            'currency'=>''
        ];

        $payments = Payment::whereHas('payment_batch', function($query) use ($fromDate, $toDate){
            $query->whereBetween('created_at', [$fromDate, $toDate]);  
        })->where('currency_id', $currency)->get();  

        $payables = [];
        foreach($payments as $payment){
            $res = array();
            $batch_date = PaymentBatch::find($payment->payment_batch_id);
            if($payment->payable_type=='advances'){
                $advance = Advance::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$advance, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='claims'){
                $claim = Claim::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$claim, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='invoices'){
                $invoice = Invoice::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$invoice, 'payment_date'=>$batch_date->created_at];
            }
            array_push($payables, $res);
        }

        $mobile_payments = MobilePayment::whereBetween('management_approval_at', [$fromDate, $toDate])->where('currency_id', $currency)->get();
        foreach($mobile_payments as $mobile_payment){
            $res = array();
            $res = ['payable_type'=>'mobile_payments', 'payment'=>null, 'payable'=>$mobile_payment, 'payment_date'=>$mobile_payment->management_approval_at];
            array_push($payables, $res);
        }

        foreach($payables as $row){
            foreach($row['payable']['allocations'] as $allocation){

                $my_result = $result;
                $grant = null;
                $program = null;
                $project = Project::find($allocation['project_id']);
                if(!empty($project->grant_id)){
                    $grant = Grant::find($project->grant_id);
                }
                if(!empty($project->program_id)){
                    $grant = Grant::find($project->grant_id);
                }
                $account = Account::find($allocation['account_id']);

                $my_result['date_posted'] = $row['payment_date'];
                $my_result['project'] = $project;
                $my_result['program_id'] = $project->program_id;
                $my_result['program_desc'] = $project->project_name;
                $my_result['grant'] = $grant;
                $my_result['invoice_title'] = $row['payable']['expense_desc'];
                $my_result['account_number'] = $account->account_code;
                $my_result['account_description'] = $account->account_desc;
                $my_result['allocation_amount'] = $allocation['amount_allocated'];
                $my_result['currecny'] = $currency;

                if($row['payable_type'] == 'mobile_payments'){
                    $mpesa_payee = Staff::find($row['payable']['requested_by_id'])->full_name;

                    $my_result['vendor_name'] = $mpesa_payee;
                    $my_result['general_jr'] = $mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$row['payable']['expense_purpose'];
                    $my_result['specific_jr'] = $mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$allocation['allocation_purpose'];
                    $my_result['total_amount'] = $row['payable']['totals'];
                    $my_result['transaction_type'] = 'Bulk MMTS';
                }
                else{
                    $my_result['vendor_name'] = $row['payment']['paid_to_name']; 
                    $my_result['general_jr'] = $row['payment']['paid_to_name'].': '.$row['payable']['expense_desc'].'; '.$row['payable']['expense_purpose'];
                    $my_result['specific_jr'] = $row['payment']['paid_to_name'].': '.$row['payable']['expense_desc'].'; '.$allocation['allocation_purpose'];
                    $my_result['total_amount'] = $row['payment']['amount'];

                    if($row['payment']['payment_mode_id'] == 1){ $my_result['transaction_type'] = 'EFT'; }
                    elseif($row['payment']['payment_mode_id'] == 4){ $my_result['transaction_type'] = 'RTGS'; }
                    elseif($row['payment']['payment_mode_id'] == 2){ $my_result['transaction_type'] = 'MMTS'; }
                }


                $prefix = '';
                if($row['payable_type'] == 'invoices'){ $prefix = 'INV'; }
                elseif($row['payable_type'] == 'advances'){ $prefix = 'ADV'; }
                elseif($row['payable_type'] == 'claims'){ $prefix = 'CLM'; }
                else { $prefix = 'CHAI'; }
                $my_result['voucher_number'] = $prefix.''.$row['payable']['id'];


                array_push($report_data, $my_result);
    
            }

        }

        if($operation == 'preview') {
            return response()->json($report_data, 200,array(),JSON_PRETTY_PRINT);
        }
        elseif($operation == 'excel') {
            // Generate excel and return it
            // return response()->json($report_data, 200,array(),JSON_PRETTY_PRINT);
            return null;
        }

    }



    /**
     * Adds zeros at the beginning of string until the desired
     * length is reached.
     */
    public function pad_zeros($desired_length, $data){
        if(strlen($data)<$desired_length){
            return str_repeat('0', $desired_length-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }
}
?>