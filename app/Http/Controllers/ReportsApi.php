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
use DateTime;
use Excel;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\Payment;
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use App\Models\ProgramModels\ProgramStaff;
use App\Models\ProgramModels\ProgramManager;
use App\Models\GrantModels\Grant;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\PaymentModels\VoucherNumber;
use App\Models\ReportModels\ReportingObjective;
use App\Models\ActivityModels\ActivityObjective;
use App\Models\BankingModels\BankTransaction;
use App\Models\FinanceModels\Budget;
use App\Models\FinanceModels\ExchangeRate;
use Illuminate\Http\Request as HttpRequest;

class ReportsApi extends Controller
{
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
            'user_id',
            'programs',
            'objectives',
            'projects'
            );
        $fromDateOnly = $form['start_date'];
        $toDateOnly = $form['end_date'];
        $fromDate = $form['start_date']." 00:00:00";
        $toDate = $form['end_date']." 23:59:59";
        $operation = $form['operation'];
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
            $payables[] = $res;
        }

        $mobile_payments = MobilePayment::whereHas('voucher_number', function($query) use ($fromDate, $toDate){
            $query->whereBetween('created_at', [$fromDate, $toDate]);  
        })->where('currency_id', $currency)->get();

        foreach($mobile_payments as $mobile_payment){
            $res = array();
            $res = ['payable_type'=>'mobile_payments', 'payment'=>null, 'payable'=>$mobile_payment, 'payment_date'=>$mobile_payment->management_approval_at ?? $mobile_payment->voucher_number->created_at ?? ''];
            $payables[] = $res;
        }

        foreach($payables as $row){if(isset($row['payable']['allocations'])){
            $voucher_no = '';
            if(($row['payable_type']=='mobile_payments' && empty($row['payable']['migration_invoice_id']))){
                $voucher = VoucherNumber::where('payable_id', $row['payable']['id'])->first();
                $voucher_no = $voucher->voucher_number;
                if(!empty($voucher->voucher_number)) $voucher_no = $voucher->voucher_number;
                else $voucher_no = '-';
            }
            elseif(($row['payable_type']!='mobile_payments' && empty($row['payable']['migration_id']))){
                $voucher = VoucherNumber::where('payable_id', $row['payment']['id'])->first();
                $voucher_no = $voucher->voucher_number;
                if(!empty($voucher->voucher_number)) $voucher_no = $voucher->voucher_number;
                else $voucher_no = '-';
            }
            else{
                if($row['payable_type']=='mobile_payments'){
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_invoice_id']);
                }
                else {
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_id']);
                }
            }
                
                foreach($row['payable']['allocations'] as $allocation){

                    $my_result = $result;
                    $grant = null;
                    $project = Project::find($allocation['project_id']);
                    if(!empty($project->grant_id)){
                        $grant = Grant::find($project->grant_id);
                    }
                    if(!empty($project->program_id)){
                        $grant = Grant::find($project->grant_id);
                    }
                    $account = Account::find($allocation['account_id']);
    
                    $my_result['date_posted'] = (string)$row['payment_date'] ?? '';
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

                    $sum = 0;
                    foreach($row['payable']['bank_transactions'] as $b){
                        $sum += $b['amount'];
                    }
                    if($sum > 0){
                        $my_result['amount_paid'] = $sum;
                    }
    
                    if($row['payable_type'] == 'mobile_payments'){
                        $mpesa_payee = Staff::find($row['payable']['requested_by_id'])->full_name ?? '-';
    
                        $my_result['vendor_name'] = 'MOH OFFICIALS';
                        $my_result['general_jr'] = 'MOH OFFICIALS c/o '.$mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$row['payable']['expense_purpose'];
                        $my_result['specific_jr'] = 'MOH OFFICIALS c/o '.$mpesa_payee.': '.$allocation['allocation_purpose'];
                        $my_result['total_amount'] = $row['payable']['totals'];
                        $my_result['transaction_type'] = 'Bulk MMTS';
                    }
                    else{
                        $my_result['vendor_name'] = $row['payment']['paid_to_name']; 
                        $my_result['general_jr'] = $row['payment']['paid_to_name'].': '.$row['payable']['expense_desc'];
                        $my_result['specific_jr'] = $row['payment']['paid_to_name'].': '.$allocation['allocation_purpose'];
                        $my_result['total_amount'] = $row['payment']['amount'];
    
                        if($row['payment']['payment_mode_id'] == 1){ $my_result['transaction_type'] = 'EFT'; }
                        elseif($row['payment']['payment_mode_id'] == 4){ $my_result['transaction_type'] = 'RTGS'; }
                        elseif($row['payment']['payment_mode_id'] == 2){ $my_result['transaction_type'] = 'MMTS'; }
                    }
    
                    $my_result['voucher_number'] = $voucher_no;
    
                    $report_data[] = $my_result;        
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
                $excel_row['date'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_posted'])->format('M-d-Y'); //'Jan 08 2018';
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
                $excel_row['amount_paid'] = isset($row['amount_paid']) ? $row['amount_paid'] : '';

                
                $payment_id = $row['payable_id'];
                array_push($excel_data, $excel_row);
            }
            $headers = [
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
    
                $headings = array('Vendor Name', 'Post Date', 'Project ID', 'Grant ID', 'Account Number', 'Account Description', 'Invoice Title(Main Memo - General JR)', 'Allocation Memo - Specific JR', 'Allocation Amount', 'Total Amount', 'Transaction Type (Payment Mode)', 'Voucher number', 'Amount paid');
    
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
                        'L' => 20,
                        'M' => 20
                    ));
                    $sheet->getStyle('K1')->getAlignment()->setWrapText(true);

                    $sheet->setFreeze('C2');
                    $sheet->setAutoFilter('C1:C1');
                });
    
            })->download('xlsx', $headers);
        }
    }




    /**
     * Operation getPmJournal
     * Get PM journal.
     * @return Http response
     */
    public function PmJournal()
    {
        $form = Request::only(
            'start_date',
            'end_date',
            'currency',
            'operation',
            'is_pm',
            'user_id',
            'programs',
            'objectives',
            'projects'
            );
        $fromDateOnly = $form['start_date'];
        $toDateOnly = $form['end_date'];
        $fromDate = $form['start_date']." 00:00:00";
        $toDate = $form['end_date']." 23:59:59";
        $operation = $form['operation'];
        $user_id = $form['user_id'];
        $currency = 0;
        if($form['currency'] == 'kes') $currency = 1;
        elseif($form['currency'] == 'usd') $currency = 2;

        $programs = [];
        $objectives = [];
        $projects = [];

        foreach($form['objectives'] as $objective){
            $objectives[] = $objective['id'];
        }
        foreach($form['programs'] as $program){
            $programs[] = $program['id'];
        }
        foreach($form['projects'] as $project){
            $projects[] = $project['id'];
        }

        $pm_pids =  DB::table('projects')->select(DB::raw('projects.*'))
                    ->rightJoin('programs', 'programs.id', '=', 'projects.program_id')
                    ->rightJoin('program_managers', 'program_managers.program_id', '=', 'programs.id')
                    ->rightJoin('staff', 'staff.id', '=', 'program_managers.program_manager_id')
                    ->where('staff.id', '=', $this->current_user()->id)
                    ->whereNotNull('projects.id')
                    ->groupBy('projects.id')->pluck('projects.id')->toArray();

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
            $res = [];
            $pids = [];
            $batch_date = PaymentBatch::find($payment->payment_batch_id);
            if($payment->payable_type=='advances'){
                $advance = Advance::with('allocations.objective')->where('project_manager_id', $user_id)
                            ->where('id', $payment->payable_id);

                if(!empty($programs)){
                    $pids = Project::whereIn('program_id', $programs)->pluck('id')->toArray();
                    $advance = $advance->whereHas('allocations', function($query) use ($pids, $pm_pids){
                        $query->whereIn('project_id', $pids);
                        $query->whereIn('project_id', $pm_pids);
                    });
                }

                if(!empty($projects)){
                    $advance = $advance->whereHas('allocations', function($query) use ($projects){
                        $query->whereIn('project_id', $projects);  
                    });
                }

                if(!empty($objectives)){
                    $advance = $advance->whereHas('allocations', function($query) use ($objectives){
                        $query->whereIn('objective_id', $objectives);  
                    });
                }

                $advance = $advance->first();
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$advance, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='claims'){
                $claim = Claim::with('allocations.objective')->where('project_manager_id', $user_id)
                            ->where('id', $payment->payable_id);
                if(!empty($programs)){
                    $pids = Project::whereIn('program_id', $programs)->pluck('id')->toArray();
                    $claim = $claim->whereHas('allocations', function($query) use ($pids, $pm_pids){
                        $query->whereIn('project_id', $pids);  
                        $query->whereIn('project_id', $pm_pids);
                    });
                }

                if(!empty($projects)){
                    $claim = $claim->whereHas('allocations', function($query) use ($projects){
                        $query->whereIn('project_id', $projects);  
                    });
                }

                if(!empty($objectives)){
                    $claim = $claim->whereHas('allocations', function($query) use ($objectives){
                        $query->whereIn('objective_id', $objectives);  
                    });
                }

                $claim = $claim->first();
                    
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$claim, 'payment_date'=>$batch_date->created_at];
            }
            elseif($payment->payable_type=='invoices'){
                $invoice = Invoice::with('allocations.objective')->where('project_manager_id', $user_id)
                                ->where('id', $payment->payable_id);

                if(!empty($programs)){
                    $pids = Project::whereIn('program_id', $programs)->pluck('id')->toArray();
                    $invoice = $invoice->whereHas('allocations', function($query) use ($pids, $pm_pids){
                        $query->whereIn('project_id', $pids);
                        $query->whereIn('project_id', $pm_pids);
                    });
                }

                if(!empty($projects)){
                    $invoice = $invoice->whereHas('allocations', function($query) use ($projects){
                        $query->whereIn('project_id', $projects);  
                    });
                }

                if(!empty($objectives)){
                    $invoice = $invoice->whereHas('allocations', function($query) use ($objectives){
                        $query->whereIn('objective_id', $objectives);  
                    });
                }

                $invoice = $invoice->first();
                $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$invoice, 'payment_date'=>$batch_date->created_at];
            }
            $payables[] = $res;
        }

        $mobile_payments = MobilePayment::with('allocations.objective')->whereBetween('management_approval_at', [$fromDate, $toDate])->where('currency_id', $currency)
                            ->where('project_manager_id', $user_id);
            
                            if(!empty($programs)){
                                $pids = Project::whereIn('program_id', $programs)->pluck('id')->toArray();
                                $mobile_payments = $mobile_payments->whereHas('allocations', function($query) use ($pids, $pm_pids){
                                    $query->whereIn('project_id', $pids);
                                    $query->whereIn('project_id', $pm_pids); 
                                });
                            }
            
                            if(!empty($projects)){
                                $mobile_payments = $mobile_payments->whereHas('allocations', function($query) use ($projects){
                                    $query->whereIn('project_id', $projects);  
                                });
                            }
            
                            if(!empty($objectives)){
                                $mobile_payments = $mobile_payments->whereHas('allocations', function($query) use ($objectives){
                                    $query->whereIn('objective_id', $objectives);  
                                });
                            }  
                            
                            $mobile_payments = $mobile_payments->get();

        foreach($mobile_payments as $mobile_payment){
            $res = ['payable_type'=>'mobile_payments', 'payment'=>null, 'payable'=>$mobile_payment, 'payment_date'=>$mobile_payment->management_approval_at];
            array_push($payables, $res);
        }

        foreach($payables as $row){if(isset($row['payable']['allocations'])){
            $voucher_no = '';
            if(($row['payable_type']=='mobile_payments' && empty($row['payable']['migration_invoice_id']))){
                $voucher = VoucherNumber::where('payable_id', $row['payable']['id'])->first();
                $voucher_no = $voucher->voucher_number;
                if(!empty($voucher->voucher_number)) $voucher_no = $voucher->voucher_number;
                else $voucher_no = '-';
            }
            elseif(($row['payable_type']!='mobile_payments' && empty($row['payable']['migration_id']))){
                $voucher = VoucherNumber::where('payable_id', $row['payment']['id'])->first();
                $voucher_no = $voucher->voucher_number;
                if(!empty($voucher->voucher_number)) $voucher_no = $voucher->voucher_number;
                else $voucher_no = '-';
            }
            else{
                if($row['payable_type']=='mobile_payments'){
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_invoice_id']);
                }
                else {
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $row['payable']['migration_id']);
                }
            }
                
                foreach($row['payable']['allocations'] as $allocation){

                    // Filter out PIDs not belonging to the current PM
                    if(!empty($pm_pids) && (empty($allocation['project_id']) || !in_array($allocation['project_id'], $pm_pids))){
                        continue;
                    }

                    // Filter out results not in the search arrays
                    if(!empty($objectives) && (empty($allocation['objective_id']) || !in_array($allocation['objective_id'], $objectives))){
                        continue;
                    }
                    
                    if(!empty($projects) && (empty($allocation['project_id']) || !in_array($allocation['project_id'], $projects))){
                        continue;
                    }

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
                    $my_result['objective'] = $allocation['objective'];
    
                    if($row['payable_type'] == 'mobile_payments'){
                        $mpesa_payee = Staff::find($row['payable']['requested_by_id'])->full_name;
    
                        $my_result['vendor_name'] = 'MOH OFFICIALS';
                        $my_result['general_jr'] = 'MOH OFFICIALS c/o '.$mpesa_payee.': '.$row['payable']['expense_desc'].'; '.$row['payable']['expense_purpose'];
                        $my_result['specific_jr'] = 'MOH OFFICIALS c/o '.$mpesa_payee.': '.$allocation['allocation_purpose'];
                        $my_result['total_amount'] = $row['payable']['totals'];
                        $my_result['transaction_type'] = 'Bulk MMTS';
                    }
                    else{
                        $my_result['vendor_name'] = $row['payment']['paid_to_name']; 
                        $my_result['general_jr'] = $row['payment']['paid_to_name'].': '.$row['payable']['expense_desc'];
                        $my_result['specific_jr'] = $row['payment']['paid_to_name'].': '.$allocation['allocation_purpose'];
                        $my_result['total_amount'] = $row['payment']['amount'];
    
                        if($row['payment']['payment_mode_id'] == 1){ $my_result['transaction_type'] = 'EFT'; }
                        elseif($row['payment']['payment_mode_id'] == 4){ $my_result['transaction_type'] = 'RTGS'; }
                        elseif($row['payment']['payment_mode_id'] == 2){ $my_result['transaction_type'] = 'MMTS'; }
                    }
    
                    $my_result['voucher_number'] = $voucher_no;
    
                    $report_data[] = $my_result;        
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
                $excel_row['date'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_posted'])->format('M-d-Y'); //'Jan 08 2018';
                $excel_row['project_id'] = $row['project']['project_code'];
                $excel_row['grant_details'] = $row['grant']['grant_code'];
                $excel_row['objective'] = $row['objective'] ? $row['objective']['objective'] : '';
                $excel_row['account_number'] = $row['account_number'];
                $excel_row['account_description'] = $row['account_description'];
                $excel_row['general_jr'] = $row['general_jr'];
                $excel_row['specific_jr'] = $row['specific_jr'];
                $excel_row['allocation_amount'] = $row['allocation_amount'];
                $row['payable_id'] != $payment_id ? $excel_row['total'] = $row['total_amount'] : $excel_row['total'] = '';
                $excel_row['transaction_type'] = $row['transaction_type'];
                $excel_row['voucher_number'] = $row['voucher_number'];
                
                $payment_id = $row['payable_id'];
                $excel_data[] = $excel_row;
            }
            $headers = [
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
    
                $headings = array('Vendor Name', 'Post Date', 'Project ID', 'Grant ID', 'Objective', 'Account Number', 'Account Description', 'Invoice Title(Main Memo - General JR)', 'Allocation Memo - Specific JR', 'Allocation Amount', 'Total Amount', 'Transaction Type (Payment Mode)', 'Voucher number');
    
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
                        'E' => 30,
                        'F' => 15,
                        'G' => 35,
                        'H' => 50,
                        'I' => 50,
                        'J' => 20,
                        'K' => 15,
                        'L' => 20,
                        'M' => 20
                    ));
                    $sheet->getStyle('K1')->getAlignment()->setWrapText(true);

                    $sheet->setFreeze('C2');
                    $sheet->setAutoFilter('C1:C1');
                });
    
            })->download('xlsx', $headers);
        }
    }




    /**
     * Operation getReportingCategories
     * Get reporting categories
     * @return Http response
     */
    public function getReportingCategories(){
        try{
            $qb = DB::table('reporting_categories')->whereNull('deleted_at');
            $response = $qb->get();
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            $response =  ["error"=>"reporting_category could not be found", "message"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }




    /**
     * Operation expenseReport
     * Get expense report
     * @return Http response
     */
    public function expenseReport(HttpRequest $request){
        try{
            $table_data = [
                'account'=>null,
                'budget'=>null,
                'expenditure'=>0
            ];
            $project = Project::findOrFail($request->project_id);
            $result = ['project'=> $project, 'table_data'=>[]];
            $fromDate = date('Y-m-d', strtotime($request->date_range[0]));
            $toDate = date('Y-m-d', strtotime($request->date_range[1]));
            $budget = Budget::whereDate('end_date', '>=', $toDate)->whereDate('start_date', '<=', $fromDate)->where('project_id', $request->project_id)->first();

            if(!empty($budget)){

                // Get all transactions
                $payments = Payment::whereHas('payment_batch', function($query) use ($fromDate, $toDate){
                    $query->whereBetween('created_at', [$fromDate, $toDate]);  
                })->get();

                $payables = [];
                foreach($payments as $payment){
                    $res = [];
                    if($payment->payable_type=='advances'){
                        $advance = Advance::find($payment->payable_id);
                        $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$advance];
                    }
                    elseif($payment->payable_type=='claims'){
                        $claim = Claim::find($payment->payable_id);
                        $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$claim];
                    }
                    elseif($payment->payable_type=='invoices'){
                        $invoice = Invoice::find($payment->payable_id);
                        $res = ['payable_type'=>$payment->payable_type, 'payment'=>$payment, 'payable'=>$invoice];
                    }
                    $payables[] = $res;
                }

                $mobile_payments = MobilePayment::whereHas('voucher_number', function($query) use ($fromDate, $toDate){
                    $query->whereBetween('created_at', [$fromDate, $toDate]);  
                })->get();

                foreach($mobile_payments as $mobile_payment){
                    $res = array();
                    $res = ['payable_type'=>'mobile_payments', 'payment'=>null, 'payable'=>$mobile_payment];
                    $payables[] = $res;
                }

                // $count = 0;
                // Go through the items
                foreach($budget->items as $item){
                    $exp = 0;
                    $exp2 = 0;
                    $my_result = $table_data;
                    $my_result['account'] = $item->account;
                    $my_result['budget'] = $item->amount;                    

                    foreach($payables as $row){
                        if(isset($row['payable']['allocations'])){
                            foreach($row['payable']['allocations'] as $allocation){
                                // if($count < 5){
                                //     file_put_contents ( "C://Users//kennl//Documents//debug.txt" , PHP_EOL.json_encode($item) , FILE_APPEND);
                                //     $count += 1;
                                // } 
            
                                // if($item->account){
                                    if($allocation->account_id == $item->account_id && $allocation->project_id == $request->project_id){
                                        // if(!empty($my_result['expenditure'])){
                                        //     $my_result['expenditure'] += $allocation['converted_usd'] ?? 0;
                                        // }
                                        // else {
                                        $exp += $allocation->converted_usd;
                                        $exp2 += $allocation->amount_allocated;
                                        // }
                                    }
                                // }
                            }
                        }
                    }
                    $my_result['expenditure'] = $exp;

                    $result['table_data'][] = $my_result;
                }
            }

            return response()->json($result, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            $response =  ["error"=>"An error occurred during processing", "message"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }




    /**
     * Operation getReportingObjectives
     * Get reporting objectives
     * @return Http response
     */
    public function getReportingObjectives(){
        try{
            $input = Request::all();

            $objectives = array();
            if(array_key_exists('lean', $input)){
                $objectives = ReportingObjective::query();
            }
            else{
                $objectives = ReportingObjective::with('program');
            }

            $response;
            $response_dt;
            $total_records          = $objectives->count();
            $records_filtered       = 0;

            //ordering
            if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
                $order_direction     = "asc";
                $order_column_name   = $input['order_by'];
                if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                    $order_direction = $input['order_dir'];
                }

                $objectives = $objectives->orderBy($order_column_name, $order_direction);
            }

            //limit
            if(array_key_exists('limit', $input)){
                $objectives = $objectives->limit($input['limit']);
            }

            // My assigned
            if(array_key_exists('my_assigned', $input)){
                // If PM
                if($this->current_user()->hasRole(['program-manager'])){
                    $programs = ProgramManager::where('program_manager_id', $this->current_user()->id)->get();
                    $program_ids = array();
                    foreach($programs as $prog){
                        array_push($program_ids, $prog->program_id);
                    }
                    $objectives = $objectives->whereIn('program_id', $program_ids);
                }
                else {
                    $program_teams = ProgramStaff::where('staff_id', $this->current_user()->id)->get();
                    $program_ids = array();                    
                    foreach($program_teams as $team){
                        array_push($program_ids, $team->program_id);
                    }
                    $program_ids = array_unique($program_ids);

                    $objectives = $objectives->whereIn('program_id', $program_ids);
                }
            }

            if(array_key_exists('activity', $input) && !empty($input['activity'])){
                $activity_objectives = ActivityObjective::where('activity_id', $input['activity'])->pluck('objective_id')->toArray();
                if(!empty($activity_objectives)){
                    $objectives = $objectives->whereIn('id', $activity_objectives);
                }
            }

            if(array_key_exists('datatables', $input)){
                //limit $ offset
                if((int)$input['start']!= 0 ){
                    $response_dt = $objectives->limit($input['length'])->offset($input['start']);
                }else{
                    $objectives = $objectives->limit($input['length']);
                }

                $records_filtered = (int) $objectives->count();
                $response_dt = $objectives->get();

                $response = ReportingObjective::arr_to_dt_response( 
                    $response_dt, $input['draw'],
                    $total_records,
                    $records_filtered
                    );
            }
            else{
                $response = $objectives->get();
            }

            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            $response =  ["error"=>"reporting_objective could not be found", "message"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Add Reporting Objective
     */
    public function addReportingObjective(){
        try{
            $form = Request::all();
            $objective = new ReportingObjective;
            $objective->objective = $form['objective'];
            $objective->description = $form['description'];
            $objective->program_id = $form['program_id'];
            $objective->disableLogging();

            if($objective->save()){
                return Response()->json(array('msg' => 'Success: Objective created'), 200);
            }
        }
        catch(\Exception $e){
            $response =  ["error"=>"An error occurred during processing", "message"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Update Reporting Objective
     */
    public function updateReportingObjective(){
        try{
            $form = Request::all();
            $objective = ReportingObjective::find($form['id']);
            $objective->objective = $form['objective'];
            $objective->description = $form['description'];
            $objective->program_id = $form['program_id'];
            $objective->disableLogging();

            if($objective->save()){
                return Response()->json(array('msg' => 'Success: Objective updated'), 200);
            }
        }
        catch(\Exception $e){
            $response =  ["error"=>"An error occurred during processing", "message"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Delete Reporting Objective
     */
    public function deleteReportingObjective($objective_id)
    {
        $deleted = ReportingObjective::destroy($objective_id);
        if($deleted){
            return response()->json(['msg'=>"Objective removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }

    }


    /**
     * Get Reporting Objective by ID
     */
    public function getReportingObjectiveById($objective_id){
        try{
            $objective = ReportingObjective::with('program')->find($objective_id);
            return response()->json($objective, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }






    /**
     * Operation setReportingCategoryObjective
     * Set or Update reporting categories/objectives
     * @return Htpp response
     */
    public function setReportingCategoryObjective(){
        try{            
            $user = JWTAuth::parseToken()->authenticate();
            $form = Request::only(
                'id',
                'reporting_objective_id',
                'reporting_categories_id',
                'payable_type'
                );

            if($form['payable_type'] == 'claims'){
                $claim = Claim::find($form['id']);
                $claim->reporting_objective_id = $form['reporting_objective_id'];
                $claim->reporting_categories_id = $form['reporting_categories_id'];
                $claim->disableLogging();
                $claim->save();

                // Logging
                activity()
                ->performedOn($claim)
                ->causedBy($user)
                ->log('set reporting objective/category');

                return Response()->json(array('msg' => 'Success: objective/category set','claim' => $claim), 200);
            }

            else if($form['payable_type'] == 'advances'){
                $advance = Advance::find($form['id']);
                $advance->reporting_objective_id = $form['reporting_objective_id'];
                $advance->reporting_categories_id = $form['reporting_categories_id'];
                $advance->disableLogging();
                $advance->save();

                // Logging
                activity()
                ->performedOn($advance)
                ->causedBy($user)
                ->log('set reporting objective/category');

                return Response()->json(array('msg' => 'Success: objective/category set','advance' => $advance), 200);
            }

            else if($form['payable_type'] == 'invoices'){
                $invoice = Invoice::find($form['id']);
                $invoice->reporting_objective_id = $form['reporting_objective_id'];
                $invoice->reporting_categories_id = $form['reporting_categories_id'];
                $invoice->disableLogging();
                $invoice->save();

                // Logging
                activity()
                ->performedOn($invoice)
                ->causedBy($user)
                ->log('set reporting objective/category');

                return Response()->json(array('msg' => 'Success: objective/category set','invoice' => $invoice), 200);
            }

            else if($form['payable_type'] == 'mobile_payments'){
                $mobile_payment = MobilePayment::find($form['id']);
                $mobile_payment->reporting_objective_id = $form['reporting_objective_id'];
                $mobile_payment->reporting_categories_id = $form['reporting_categories_id'];
                $mobile_payment->disableLogging();
                $mobile_payment->save();

                // Logging
                activity()
                ->performedOn($mobile_payment)
                ->causedBy($user)
                ->log('set reporting objective/category');

                return Response()->json(array('msg' => 'Success: objective/category set','mobile_payment' => $mobile_payment), 200);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
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



    // EXCHANGE RATES
    /**
     * Add USD Rates
     */
    public function addExchangeRate(HttpRequest $request){
        try{
            $rate = new ExchangeRate();
            $rate->exchange_rate = $request->exchange_rate;
            $rate->active_date = $request->active_date;
            $rate->added_by_id = $this->current_user()->id;
            $rate->save();

            // Add activity notification
            $this->addActivityNotification('Added USD rate for <strong>'.date('M Y', strtotime($rate->active_date)). '</strong>', null, $this->current_user()->id, $this->current_user()->id, 'success', 'exchange_rates', false);

            return Response()->json(['msg'=>'Success'], 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function updateRate(HttpRequest $request){
        try{
            $rate = ExchangeRate::findOrFail($request->id);
            $rate->exchange_rate = $request->exchange_rate;
            $rate->active_date = $request->active_date;
            $rate->save();

            return Response()->json(['msg'=>'Success'], 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage()], 500);
        }
    }

    public function deleteRate($id){
        try{
            ExchangeRate::destroy($id);
            return response()->json(['msg'=>"Success"], 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRates(){
        try{
            $rates = ExchangeRate::with('added_by')->orderBy('active_date')->get();
            return response()->json($rates, 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage()], 500);
        }
    }
}
