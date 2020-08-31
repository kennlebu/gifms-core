<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use App\Jobs\completeBatchUpload;
use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\PaymentStatus;
use App\Models\PaymentModels\Payment;
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo;
use App\Models\PaymentModels\VoucherNumber;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBatch;
use App\Mail\NotifyPayment;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Mail\RequestBankSigning;
use Illuminate\Support\Facades\Log;

class PaymentBatchApi extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {        
        $status = PaymentStatus::where('default_status','1')->first();
        $this->default_payment_status = $status->id;
    }

    

    /**
     * Operation addPaymentBatch
     *
     * Add a new payment_batch.
     *
     *
     * @return Http response
     */
    public function addPaymentBatch()
    {
        $form = Request::only('payments');

        try{
            $payment_batch = new PaymentBatch;
            $user = JWTAuth::parseToken()->authenticate();
            $payment_batch->processed_by_id = $user->id;
            $payment_batch->status_id = 1;

            if($payment_batch->save()) {
                $payment_batch->disableLogging();
                $payment_batch->ref = "CHAI/PYTBT/#$payment_batch->id/".date_format($payment_batch->created_at,"Y/m/d");
                $payment_batch->save();

                foreach ($form['payments'] as $key => $value) {
                    $payment = Payment::find($value);    
                    if($payment->status_id == 1){   // Only batch payments that have not been batched yet
                        $payment->status_id         = 2;
                        $payment->payment_batch_id  = $payment_batch->id;
                        $payment->disableLogging();

                        $payment->ref = "CHAI/PYMT/#$payment->id/".date_format($payment->created_at,"Y/m/d");
                        $v = DB::select('call generate_voucher_no(?,?)',array($payment->id, $payment->payable_type));
                        $payment->save();

                        // Now update the invoices, claims and advances
                        if($payment->payable_type == 'invoices'){
                            $invoice                = Invoice::find($payment->payable_id);
                            $invoice->status_id     = 5;
                            $invoice->disableLogging();
                            $invoice->save();
                            activity()
                                ->performedOn($invoice)
                                ->causedBy($user)
                                ->log('Confirmed & processed payments');
                        }
                        elseif($payment->payable_type == 'advances'){
                            $advance                = Advance::find($payment->payable_id);
                            $advance->status_id     = 5;
                            $advance->disableLogging();
                            $advance->save();
                            activity()
                                ->performedOn($advance)
                                ->causedBy($user)
                                ->log('Confirmed & processed payments');
                        }
                        elseif($payment->payable_type == 'claims'){
                            $claim                = Claim::find($payment->payable_id);
                            $claim->status_id     = 6;
                            $claim->disableLogging();
                            $claim->save();
                            activity()
                                ->performedOn($claim)
                                ->causedBy($user)
                                ->log('Confirmed & processed payments');
                        }
                    }                    
                }
                
                Mail::queue(new NotifyBatch($payment_batch->id));
                
                // Add activity notification
                $this->addActivityNotification('Payments consolidated (batched)', null, $this->current_user()->id, $this->current_user()->id, 'success', 'payment_batches', true);              

                return Response()->json(['msg' => 'Success: Payment Batch added'], 200);
            }
        }
        catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
    }


    /**
     * Operation completePaymentBatchUpload
     *
     * Complete upload of a payment_batch.
     *
     *
     * @return Http response
     */
    public function completePaymentBatchUpload($payment_batch_id)
    {
        // $user = $this->current_user();

        try{
            $payment_batch = PaymentBatch::find($payment_batch_id);    
            if($payment_batch->status_id == 1){
                $payment_batch->status_id = 2;
                $payment_batch->upload_date = date('Y-m-d H:i:s');
                $payment_batch->disableLogging();
                $payment_batch->save();
            }

            // Get the payments and move them to the next status
            $payment_ids = Payment::where('payment_batch_id', $payment_batch_id)->pluck('id')->toArray();
            dispatch(new completeBatchUpload($payment_ids, $this->current_user()));

            return Response()->json(['msg' => 'Success: batch uploaded'], 200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(["error"=>"There was an error uploading the batch"], 500);
        }
    }



    /**
     * request Bank Signitories
     * Send mail request bank signitories to approve payments
     * @return Http response
     */
    public function requestBankSignitories($payment_batch_id){
        Mail::queue(new RequestBankSigning($payment_batch_id));
    }


    /**
     * Operation getCSVData
     * get data for generating CSV.
     * @return Http response
     */
    public function getCSVData(){
        $input = Request::all();
        $payment_batch_id = $input['payment_batch_id'];
        $payment_mode = $input['payment_mode']; 
        $currency = $input['currency'];

        try{
            // EFT & INT
            if($payment_mode=='1' || $payment_mode == '6'){
                $eft_result = [];
                
                $payments = Payment::with(['currency','paid_to_bank','paid_to_bank_branch'])
                            ->where('payment_mode_id',$payment_mode)
                            ->where('payment_batch_id',$payment_batch_id)
                            ->where('currency_id', $currency)->get();
                foreach ($payments as $payment) {      

                    $eft_data = array('date'=>'', 'bank_code'=>'', 'branch'=>'','account'=>'','amount'=>'','chaipv'=>'','acct_name'=>'');

                    $eft_data['amount'] = $payment->net_amount;
                    $eft_data['date'] = date('Ymd', strtotime($payment->created_at));

                    $voucher_no = '';
                    if(empty($payment->migration_id)){
                        $voucher_no = VoucherNumber::find($payment->voucher_no);
                        $voucher_no = $voucher_no->voucher_number;
                    }
                    else{
                        if($payment->payable_type=='mobile_payments'){
                            $payable = MobilePayment::find($payment->payable_id);
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payable->migration_invoice_id);
                            }
                        else {
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payment->payable_id);
                        }
                        
                    } 

                    empty($payment->paid_to_bank->bank_code) ? $eft_data['bank_code'] = 0 : $eft_data['bank_code'] = $this->pad_zeros(2,(string)$payment->paid_to_bank->bank_code);
                    empty($payment->paid_to_bank_branch->branch_code) ? $eft_data['branch'] = 0 : $eft_data['branch'] = $this->pad_zeros(3,(string)$payment->paid_to_bank_branch->branch_code);
                    empty($payment->paid_to_bank_account_no) ? $eft_data['account'] = 0 : $eft_data['account'] = $payment->paid_to_bank_account_no;
                    $eft_data['chaipv'] = $voucher_no;
                    empty($payment->paid_to_name) ? $eft_data['acct_name'] = 0 : $eft_data['acct_name'] = $payment->paid_to_name;

                    $eft_result[] = $eft_data;
                }
                
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $eft_result), 200);
            }

            // RTGS
            elseif($payment_mode=='4'){
                $rtgs_result = [];
                
                $payments = Payment::with(['currency','paid_to_bank','paid_to_bank_branch'])
                            ->where('payment_mode_id',$payment_mode)
                            ->where('payment_batch_id',$payment_batch_id)
                            ->where('currency_id', $currency)->get();
                 
                foreach ($payments as $payment) {

                    $rtgs_data = array('date'=>'', 'bank_code'=>'','account'=>'','acct_name'=>'','amount'=>'','chaipv'=>'','channel'=>'BANK');
                    $rtgs_data['amount'] = $payment->net_amount;
                    $rtgs_data['date'] = date('Ymd', strtotime($payment->created_at));

                    $voucher_no = '';
                    if(empty($payment->migration_id)){
                        $voucher_no = VoucherNumber::find($payment->voucher_no);
                        $voucher_no = $voucher_no->voucher_number;
                    }
                    else{
                        if($payment->payable_type=='mobile_payments'){
                            $payable = MobilePayment::find($payment->payable_id);
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payable->migration_invoice_id);
                            }
                        else {
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payment->payable_id);
                        }
                        
                    }

                    $bank_code = 'n/a';
                    $branch_code = 'n/a';
                    if(empty($payment->paid_to_bank->bank_code)) $bank_code = 0;
                    else $bank_code = $payment->paid_to_bank->bank_code;
                    if(empty($payment->paid_to_bank_branch->branch_code)) $branch_code = 0;
                    else $branch_code = $payment->paid_to_bank_branch->branch_code;
                    $rtgs_data['bank_code'] = $this->pad_zeros(2,(string)$bank_code).$this->pad_zeros(3,(string)$branch_code);
                    $rtgs_data['account'] = $payment->paid_to_bank_account_no;
                    $rtgs_data['chaipv'] = $voucher_no;
                    $rtgs_data['acct_name'] = $payment->paid_to_name;

                    $rtgs_result[] = $rtgs_data;
                }
                
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $rtgs_result), 200);
            }

            //MMTS
            elseif($payment_mode=='2'){
                $mmts_result = [];
                
                $payments = Payment::with(['currency','paid_to_bank','paid_to_bank_branch','payable'])
                            ->where('payment_mode_id',$payment_mode)
                            ->where('payment_batch_id',$payment_batch_id)
                            ->where('currency_id', $currency)->get();
                foreach ($payments as $payment) {       

                    $mmts_data = array('date'=>'', 'bank_code'=>'99001','phone'=>'','mobile_name'=>'','bank_name'=>'NIC','amount'=>'','chaipv'=>'');

                    $mmts_data['amount'] = $payment->net_amount;
                    $mmts_data['date'] = date('Ymd', strtotime($payment->created_at));

                    $voucher_no = '';
                    if(empty($payment->migration_id)){
                        $voucher_no = VoucherNumber::find($payment->voucher_no);
                        $voucher_no = $voucher_no->voucher_number;
                    }
                    else{
                        if($payment->payable_type=='mobile_payments'){
                            $payable = MobilePayment::find($payment->payable_id);
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payable->migration_invoice_id);
                            }
                        else {
                            $voucher_no = 'CHAI'.$this->pad_zeros(5, $payment->payable_id);
                        }
                        
                    }
                    $mmts_data['chaipv'] = $voucher_no;

                    if($payment->payable_type == 'invoices'){      
                        $invoice = Invoice::with('supplier')->find($payment->payable_id);                  
                        $mmts_data['phone'] = $this->format_phone($invoice->supplier->mobile_payment_number);
                        $mmts_data['mobile_name'] = $invoice->supplier->mobile_payment_name;
                    }

                    elseif($payment->payable_type == 'advances'){
                        $advance = Advance::with('requested_by')->find($payment->payable_id);                
                        $mmts_data['phone'] = $this->format_phone($advance->requested_by->mpesa_no);
                        $mmts_data['mobile_name'] = $advance->requested_by->cheque_addressee;
                    }

                    elseif($payment->payable_type == 'claims'){
                        $claim = Claim::with('requested_by')->find($payment->payable_id);             
                        $mmts_data['phone'] = $this->format_phone($claim->requested_by->mpesa_no);
                        $mmts_data['mobile_name'] = $claim->requested_by->cheque_addressee;                        
                    }
                    $mmts_result[] = $mmts_data;
                }
              
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $mmts_result), 200);
            }
        }
        catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }























    /**
     * Operation uploadBankFile
     * upload the bank file
     * @return Http response
     */
    public function uploadBankFile(){
        
        try{
            $result = [];
            $csv_data = [];
            $form = Request::only('file');
            $file = $form['file'];

            $handle = fopen($file, 'r');
            $header = true;
            while($csvLine = fgetcsv($handle, 1000, ',')){
                if ($header) {
                    $header = false;
                } else {
                    $csv_row = [];
                    $csv_row['bank_ref'] = $csvLine[0];
                    $csv_row['chai_ref'] = $csvLine[1];
                    $csv_row['inputter'] = $csvLine[2];
                    $csv_row['approver'] = $csvLine[3];
                    $csv_row['amount'] = $csvLine[5];
                    $csv_row['bank_date'] = $csvLine[7];
                    $csv_row['time'] = $csvLine[8];
                    $csv_row['narrative'] = $csvLine[10];

                    $csv_data[] = $csv_row;
                }
            }

            foreach($csv_data as $csv_data_row){
                $pattern = "~CHAI[0-9]{5}|KE[0-9]{6}~";
                $match = '';
                $regex_result = preg_match($pattern, $csv_data_row['narrative'], $match);
                $voucher_no = '-';
                if($regex_result==1){
                    $voucher_no = $match[0];
                }
                    
                $res = array();
                $res['chai_ref'] = $voucher_no;
                $res['bank_ref'] = preg_replace("~;1~", "", $csv_data_row['bank_ref']);
                $res['inputter'] = $csv_data_row['inputter'];
                $res['approver'] = $csv_data_row['approver'];
                $res['amount'] = $csv_data_row['amount'];
                $res['bank_date'] = $csv_data_row['bank_date'];
                $res['time'] = $csv_data_row['time'];
                $res['narrative'] = $csv_data_row['narrative'];
                $res['notify_vendor'] = true;
                try{
                    $payment = "";

                    // New voucher nos.
                    if(substr($voucher_no, 0, 2) == 'KE'){
                        $voucher = VoucherNumber::where('voucher_number', $voucher_no)->firstOrFail();
                        if($voucher->payable_type != 'mobile_payments'){
                            $payment = Payment::with('payment_mode')->findOrFail($voucher->payable_id);
                            $res['vendor'] = $payment->paid_to_name;
                            $res['payment_mode'] = $payment->payment_mode->abrv;
                        }
                        elseif($voucher->payable_type == 'mobile_payments'){
                            $payment = MobilePayment::with('requested_by')->findOrFail($voucher->payable_id);
                            $res['vendor'] = "MOH OFFICIALS c/o ".$payment->requested_by->name;
                            $res['payment_mode'] = 'Bulk MMTS';
                        }
                        $payable_type = $voucher->payable_type;
                        $res['payment'] = $payment;
                        $res['payable_type'] = $payable_type;
                    }

                    // Old voucher nos.
                    elseif(substr($voucher_no, 0, 4) == 'CHAI'){
                        $invoice_id = (int)preg_replace("/[^0-9,.]/", "", $voucher_no);
                        $payment = Payment::where('payable_id', $invoice_id)->firstOrFail();
                        $res['payment'] = $payment;
                        $res['payable_type'] = $payment->payable_type;                        
                        $res['vendor'] = $payment->paid_to_name;
                    }
                    else
                        throw new \Exception("Invalid voucher number");
                } catch(\Exception $e){                    
                    $res['payable_type'] = 'missing';
                }
                $result[] = $res;
            }

            return Response()->json($result, 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'There was an error uploading the file'], 500);
        }
    }



    public function markPaymentsAsPaid(){
        try{
            $input = Request::all();
            foreach($input as $row){
                $already_saved = false;
                if($row['payable_type'] != 'mobile_payments'){
                    $payment = Payment::find($row['payment']['id']);
                    if($payment->status_id==4) $already_paid = true;
                    else{
                        $payment->status_id = 4; // Reconciled
                        $payment->save();

                        // Change invoice status
                        if($payment->payable_type=='invoices'){
                            $invoice = Invoice::with('raised_by','supplier')->find($payment->payable_id);
                            $invoice->status_id = 8; //Paid
                            $invoice->disableLogging();
                            $invoice->save();
                            activity()
                                ->performedOn($invoice)
                                ->causedBy($this->current_user())
                                ->log('Paid');
                            $bank_trans = $invoice->bank_transactions;
                            foreach($bank_trans as $tran){
                                if(trim($input['bank_ref']) == $tran->bank_ref) $already_saved = true;
                            }

                            // Change LPO to paid if it exists
                            if(!empty($invoice->lpo_id)){
                                $lpo = Lpo::findOrFail($invoice->lpo_id);
                                $lpo->invoice_paid = 'True';
                                $lpo->status_id = 14; // Paid and completed
                                $lpo->disableLogging();
                                $lpo->save();
                                activity()
                                    ->performedOn($lpo)
                                    ->causedBy($this->current_user())
                                    ->log('Paid and completed');
                            }

                            // Send email
                            if($row['notify_vendor'])
                            Mail::queue(new NotifyPayment($invoice, $payment));
                        }
                        // Change advance status
                        if($payment->payable_type=='advances'){
                            $advance = Advance::with('requested_by')->find($payment->payable_id);
                            $advance->status_id = 6; // Issued and Paid
                            $advance->disableLogging();
                            $advance->save();
                            activity()
                                ->performedOn($advance)
                                ->causedBy($this->current_user())
                                ->log('Issued and paid');

                            $bank_trans = $advance->bank_transactions;
                            foreach($bank_trans as $tran){
                                if(trim($input['bank_ref']) == $tran->bank_ref) $already_saved = true;
                            }

                            // Send email
                            if($row['notify_vendor'])
                            Mail::queue(new NotifyPayment($advance, $payment));
                        }
                        // Change claim status
                        if($payment->payable_type=='claims'){
                            $claim = Claim::with('requested_by')->find($payment->payable_id);
                            $claim->status_id = 8; // Paid
                            $claim->disableLogging();
                            $claim->save();
                            activity()
                                ->performedOn($claim)
                                ->causedBy($this->current_user())
                                ->log('Paid');

                            $bank_trans = $claim->bank_transactions;
                            foreach($bank_trans as $tran){
                                if(trim($input['bank_ref']) == $tran->bank_ref) $already_saved = true;
                            }

                            // Send email
                            if($row['notify_vendor'])
                            Mail::queue(new NotifyPayment($claim, $payment));
                        }
                    }

                }
                elseif($row['payable_type'] == 'mobile_payments'){
                    $mobile_payment = MobilePayment::with('requested_by')->find($row['payment']['id']);
                    if($mobile_payment->status_id==5) $already_paid = true;
                    else {
                        $mobile_payment->status_id = 5; //Paid
                        $mobile_payment->disableLogging();
                        $mobile_payment->save();
                        activity()
                                ->performedOn($mobile_payment)
                                ->causedBy($this->current_user())
                                ->log('Paid');
                                
                        $bank_trans = $mobile_payment->bank_transactions;
                        foreach($bank_trans as $tran){
                            if(trim($input['bank_ref']) == $tran->bank_ref) $already_saved = true;
                        }

                        // Send email
                        if($row['notify_vendor'])
                        Mail::queue(new NotifyPayment($mobile_payment, $row['payable_type'], 'text'));
                    }
                }

                if(!$already_saved){
                    // Save transaction details
                    $bank_transaction = array();
                    $bank_transaction['bank_ref'] = $row['bank_ref'];
                    $bank_transaction['chai_ref'] = $row['chai_ref'];
                    $bank_transaction['inputter'] = $row['inputter'];
                    $bank_transaction['approver'] = $row['approver'];
                    $bank_transaction['amount'] = preg_replace("/[^0-9.]/", "", $row['amount']);
                    $bank_transaction['txn_date'] =  date('Y-m-d', strtotime(str_replace('/', '-', $row['bank_date'])));
                    $bank_transaction['txn_time'] = $row['time'];
                    $bank_transaction['narrative'] = $row['narrative'];
                    DB::table('bank_transactions')->insert($bank_transaction);
                }
            }

            return response()->json(['success'=>'Payments marked as paid'], 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'An error occured', 'msg'=>$e->getMessage()], 500);
        }
    }






















    
    /**
     * Operation getPaymentBatches
     *
     * payment_batches List.
     *
     *
     * @return Http response
     */
    public function getPaymentBatches()
    {
        $input = Request::all();
        //query builder
        $qb = PaymentBatch::query();

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
            });

            $records_filtered = $qb->count();
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
            });

            $records_filtered = $qb->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb = $qb->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $qb = $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb = $qb->limit($input['length']);
            }

            $response = PaymentBatch::arr_to_dt_response( 
                $qb->get(), $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $qb->get();
        }

        return response()->json($response, 200);
    }








    public function pad_zeros($desired_length, $data){
        if(strlen($data)<$desired_length){
            return str_repeat('0', $desired_length-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }

    public function format_phone($phone){
        if((strlen($phone) == 9) && (substr($phone, 0, 1) == '7')){
            return '254'.$phone;
        }
        if((strlen($phone) == 10) && (substr($phone, 0, 2) == '07')){
            return '254'.substr($phone, 1);
        }
        return $phone;
    }
}
