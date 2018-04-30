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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;
use App;
use DateTime;
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
use App\Models\PaymentModels\VoucherNumber;

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
            'operation',
            'is_pm',
            'user_id'
            );
        $fromDateOnly = $form['start_date'];
        $toDateOnly = $form['end_date'];
        $fromDate = $form['start_date']." 00:00:00";
        $toDate = $form['end_date']." 23:59:59";
        $operation = $form['operation'];
        $is_pm = $form['is_pm'];
        $user_id = $form['user_id'];
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
            'currency'=>'',
            'payable_id'=>''
        ];

        $payments = Payment::whereHas('payment_batch', function($query) use ($fromDate, $toDate){
            $query->whereBetween('created_at', [$fromDate, $toDate]);  
        })->where('currency_id', $currency)->get();  

        $payables = [];
        foreach($payments as $payment){
            $res = array();
            $batch_date = PaymentBatch::find($payment->payment_batch_id);
            if($payment->payable_type=='advances'){
                if($is_pm == 'true'){
                    $advance = Advance::where('project_manager_id', $user_id)
                                ->where('id', $payment->payable_id)
                                ->first();
                }
                else $advance = Advance::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$advance, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='claims'){
                if($is_pm == 'true'){
                    $claim = Claim::where('project_manager_id', $user_id)
                                ->where('id', $payment->payable_id)
                                ->first();
                }
                else $claim = Claim::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$claim, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='invoices'){
                if($is_pm == 'true'){
                    $invoice = Invoice::where('project_manager_id', $user_id)
                                    ->where('id', $payment->payable_id)
                                    ->first();
                }
                else $invoice = Invoice::find($payment->payable_id);
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$invoice, 'payment_date'=>$batch_date->created_at];
            }
            array_push($payables, $res);
        }

        if($is_pm == 'true'){
            $mobile_payments = MobilePayment::whereBetween('management_approval_at', [$fromDate, $toDate])->where('currency_id', $currency)
                                ->where('project_manager_id', $user_id)->get();

            // $mobile_payments = MobilePayment::whereHas('approvals', function($query) use ($fromDate, $toDate){
            //     $query->whereBetween('created_at', [$fromDate, $toDate]);
            //     $query->whereNotIn('approval_level_id', [1,2,3,7,8,9,14,15,16]);
            // })->where('currency_id', $currency)->where('project_manager_id', $user_id)->get();
        }
        else{
            $mobile_payments = MobilePayment::whereBetween('management_approval_at', [$fromDate, $toDate])->where('currency_id', $currency)->get();

            // $mobile_payments = MobilePayment::whereHas('approvals', function($query) use ($fromDate, $toDate){
            //     $query->whereBetween('created_at', [$fromDate, $toDate]);
            //     $query->whereNotIn('approval_level_id', [1,2,3,7,8,9,14,15,16]);
            // })->where('currency_id', $currency)->get();
        }

        foreach($mobile_payments as $mobile_payment){
            $res = array();
            $res = ['payable_type'=>'mobile_payments', 'payment'=>null, 'payable'=>$mobile_payment, 'payment_date'=>$mobile_payment->management_approval_at];
            array_push($payables, $res);
        }

        foreach($payables as $row){if(isset($row['payable']['allocations'])){
            $voucher_no = '';
            if(empty($row['payable']['migration_id'])){
                $voucher_no = VoucherNumber::first($row['payable']['voucher_no']);
                $voucher_no = $voucher_no->voucher_number;
            }
            else{
                if($row['payable_type']=='mobile_payments'){
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_invoice_id']);
                    // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , '\nSQL:: '.json_encode($row['payable']) , FILE_APPEND);
                }
                else {
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_id']);
                }
                
            }
                
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
    
                    $my_result['date_posted'] = (string)$row['payment_date'];
                    $my_result['project'] = $project;
                    $my_result['program_id'] = isset($project->program_id)?$project->program_id: '';
                    $my_result['program_desc'] = isset($project->project_name)?$project->project_name: '';
                    $my_result['grant'] = $grant;
                    $my_result['invoice_title'] = $row['payable']['expense_desc'];
                    $my_result['account_number'] = isset($account->account_code)?$account->account_code:'';
                    $my_result['account_description'] = isset($account->account_desc)?$account->account_desc:'';
                    $my_result['allocation_amount'] = $allocation['amount_allocated'];
                    $my_result['currecny'] = $currency;
                    $my_result['payable_id'] = $row['payable']['id'];
    
                    if($row['payable_type'] == 'mobile_payments'){
                        $mpesa_payee = Staff::find($row['payable']['requested_by_id'])->full_name;
    
                        $my_result['vendor_name'] = $mpesa_payee;
                        $my_result['general_jr'] = $mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$row['payable']['expense_purpose'];
                        $my_result['specific_jr'] = 'MOH OFFICIALS c/o '.$mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$allocation['allocation_purpose'];
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
    
                    $my_result['voucher_number'] = $voucher_no;
    
    
                    array_push($report_data, $my_result);
        
                }
            }
        }
        

        if($operation == 'preview') {
            return response()->json($report_data, 200,array(),JSON_PRETTY_PRINT);
        }
        elseif($operation == 'excel') {
            // Generate excel and return it

            $excel_data = [];
            $payment_id = '';
            foreach($report_data as $row){
                $excel_row = array();
                $excel_row['vendor_name'] = $row['vendor_name'];
                $excel_row['date'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_posted'])->format('d-M-Y'); //'08 Jan 2018';
                $excel_row['project_id'] = $row['project']['project_code'];
                $excel_row['grant_details'] = $row['grant']['grant_code'];
                $excel_row['account_number'] = $row['account_number'];
                $excel_row['account_description'] = $row['account_description'];
                $excel_row['general_jr'] = $row['general_jr'];
                $excel_row['specific_jr'] = $row['specific_jr'];
                $excel_row['allocation_amount'] = $row['allocation_amount'];
                $row['payable_id'] != $payment_id ? $excel_row['total'] = $row['total_amount'] : $excel_row['total'] = '';
                $excel_row['transaction_type'] = $row['transaction_type'];
                $excel_row['voucher_number'] = $row['voucher_number'];

                
                $payment_id = $row['payable_id'];
                array_push($excel_data, $excel_row);
            }
            $headers = [
                // 'Content-type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Access-Control-Allow-Origin'      => '*',
                'Allow'                            => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true'
            ];
            // Build excel
            $file = Excel::create('Expense report '.$fromDateOnly.' to '.$toDateOnly, function($excel) use ($currency, $excel_data, $fromDateOnly, $toDateOnly) {
                $account_name = 'CHAI Account';
                if($currency==1) $account_name = 'Kenya Shillings Account';
                elseif($currency==2) $account_name = 'US Dollars Account';

                // Set the title
                $excel->setTitle($account_name);
    
                // Chain the setters
                $excel->setCreator('GIFMS')->setCompany('Clinton Health Access Initiative - Kenya');
    
                $excel->setDescription('A report of the transactions from '.$fromDateOnly.' to '.$toDateOnly);
    
                $headings = array('Vendor Name', 'Post Date', 'Project ID', 'Grant ID', 'Account Number', 'Account Description', 'Invoice Title(Main Memo - General JR)', 'Allocation Memo - Specific JR', 'Allocation Amount', 'Total Amount', 'Transaction Type (Payment Mode)', 'Voucher number');
    
                $excel->sheet($account_name, function ($sheet) use ($excel_data, $headings, $account_name) {
                    $sheet->setStyle([
                        'borders' => [
                            'allborders' => [
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);
                    $i = 1;
                    $alternate = true;
                    foreach($excel_data as $data_row){
                        if(empty($data_row['grant_details'])){
                            $data_row['grant_details'] = " ";
                        }

                        $sheet->appendRow($data_row);
                        $sheet->row($i, function($row) use ($data_row, $alternate){
                            if(!empty($data_row['total'])){
                                $row->setBorder('thin', 'none', 'none', 'none');
                                $row->setFontSize(10);
                            }
                        });
                        if($alternate){
                            $sheet->cells('C'.$i.':K'.$i, function($cells) {
                                $cells->setBackground('#edf1f3');  
                                $cells->setFontSize(10);                          
                            });
                        }
                        $i++;
                        $alternate = !$alternate;
                    }
                    
                    $sheet->prependRow(1, $headings);
                    $sheet->prependRow(2, [$account_name]);
                    $sheet->setFontSize(10);
                    $sheet->setHeight(1, 25);
                    $sheet->row(1, function($row){
                        $row->setFontSize(11);
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                        $row->setBorder('none', 'thin', 'none', 'thin');
                        $row->setBackground('#004080');                        
                        $row->setFontColor('#ffffff');
                    }); 
                    $sheet->row(2, function($row){
                        $row->setFontSize(12);
                        $row->setFontWeight('bold');
                        $row->setBorder('none', 'thin', 'none', 'thin');
                    }); 
                    $sheet->setWidth(array(
                        'A' => 30,
                        'B' => 15,
                        'C' => 20,
                        'D' => 20,
                        'E' => 15,
                        'F' => 35,
                        'G' => 50,
                        'H' => 50,
                        'I' => 20,
                        'J' => 15,
                        'K' => 20,
                        'L' => 20
                    ));
                    $sheet->getStyle('K1')->getAlignment()->setWrapText(true);

                    $sheet->setFreeze('C2');
                    $sheet->setAutoFilter('C1:C1');
                });
    
            })->download('xlsx', $headers);
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