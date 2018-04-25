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

class PaymentBatchApi extends Controller
{

    private $default_payment_status = '';
    // private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {        
        $status = PaymentStatus::where('default_status','1')->first();
        // $this->approvable_statuses = PaymentBatch::where('approvable','1')->get();
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
       

        $form = Request::only(
            'payments'
            );

        try{

            $payment_batch = new PaymentBatch;


            $user = JWTAuth::parseToken()->authenticate();
            $payment_batch->processed_by_id              =   (int)   $user->id;
            $payment_batch->status_id             =   (int)  1;

            // die;

            if($payment_batch->save()) {

                $payment_batch->ref = "CHAI/PYTBT/#$payment_batch->id/".date_format($payment_batch->created_at,"Y/m/d");
                $payment_batch->save();


                foreach ($form['payments'] as $key => $value) {                   

                    $payment                    = Payment::find($value);
                    
                    $payment->status_id         = $payment->status->next_status_id;
                    $payment->payment_batch_id  = $payment_batch->id;

                    $payment->save();

                    $payment->ref = "CHAI/PYMT/#$payment->id/".date_format($payment->created_at,"Y/m/d");
                    $payment->save();

                    // Now update the invoices, claims and advances
                    if($payment->payable_type == 'invoices'){
                        $invoice                = Invoice::find($payment->payable_id);
                        $invoice->status_id     = $invoice->status->next_status_id;
                        $invoice->save();
                    }
                    elseif($payment->payable_type == 'advances'){
                        $advance                = Advance::find($payment->payable_id);
                        $advance->status_id     = $advance->status->next_status_id;
                        $advance->save();
                    }
                    elseif($payment->payable_type == 'claims'){
                        $claim                = Claim::find($payment->payable_id);
                        $claim->status_id     = $claim->status->next_status_id;
                        $claim->save();
                    }


                }

                return Response()->json(array('msg' => 'Success: Payment Batch added','payment_batch' => PaymentBatch::find((int)$payment_batch->id)), 200);
            }

        }catch (JWTException $e){

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

        $input = Request::all();

        try{

            $payment_batch   = PaymentBatch::find($payment_batch_id);
           
            $payment_batch->status_id = (int) 2;
            $payment_batch->upload_date = date('Y-m-d H:i:s');
            $payment_batch->save();

            // Get the payments and move them to the next status            
            $qb = DB::table('payments')
               ->whereNull('deleted_at')
               ->where('payment_batch_id', '=', ''.$payment_batch_id)
               ->select('id')
               ->get();

            foreach ($qb as $record) {                   

                $payment                    = Payment::find($record['id']);
                $payment->status_id         = (int) 3;
                $payment->save();

                // Now update the invoices, claims and advances
                if($payment->payable_type == 'invoices'){
                    $invoice                = Invoice::find($payment->payable_id);
                    // $invoice->status_id     = $invoice->status->next_status_id;
                    $invoice->status_id     = 7;
                    $invoice->save();
                }
                elseif($payment->payable_type == 'advances'){
                    $advance                = Advance::find($payment->payable_id);
                    // $advance->status_id     = $advance->status->next_status_id;
                    $advance->status_id     = 7;
                    $advance->save();
                }
                elseif($payment->payable_type == 'claims'){
                    $claim                = Claim::find($payment->payable_id);
                    // $claim->status_id     = $claim->status->next_status_id;
                    $claim->status_id     = 7;
                    $claim->save();
                }
            }

            if($payment_batch->save()) {
                return Response()->json(array('msg' => 'Success: batch uploaded','payment_batch' => $payment_batch), 200);
            }


        }catch(Exception $e){

            $response =  ["error"=>"There was an error uploading the batch"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }





















    /**
     * Operation getCSVData
     *
     * get data for generating CSV.
     *
     *
     * @return Http response
     */
    public function getCSVData(){
        $input = Request::all();
        $payment_batch_id = $input['payment_batch_id'];
        $payment_mode = $input['payment_mode'];

        try{
            // EFT
            if($payment_mode=='1'){
                $eft_result = [];
                
                //Getting the payments
                $qb = DB::table('payments')
                    ->whereNull('deleted_at')
                    ->where('payment_batch_id', '=', ''.$payment_batch_id)
                    ->where('payment_mode_id', '=', $payment_mode)
                    ->select('id')
                    ->get();
                foreach ($qb as $record) {          

                    $eft_data = array('date'=>'', 'bank_code'=>'', 'branch'=>'','account'=>'','amount'=>'','chaipv'=>'','acct_name'=>'');

                    $payment                    = Payment::find($record['id']);
                    $eft_data['amount'] = $payment->amount;
                    $eft_data['date'] = date('Ymd', strtotime($payment->created_at));

                    if($payment->payable_type == 'invoices'){
                        $invoice                = Invoice::find($payment->payable_id);

                        $qb_invoice = DB::table('invoices')
                            ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
                            ->join('banks', 'suppliers.bank_id', '=', 'banks.id')
                            ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                            ->where('invoices.id', '=', $invoice->raised_by_id)
                            ->select('banks.bank_code','bank_branches.branch_code','suppliers.bank_account','suppliers.chaque_address')
                            ->get();
                            foreach($qb_invoice as $row){
                                $eft_data['bank_code'] = $this->pad_zeros(2,$row['bank_code']);
                                $eft_data['branch'] = $this->pad_zeros(3,$row['branch_code']);
                                $eft_data['account'] = $row['bank_account'];
                                $eft_data['chaipv'] = 'CHAI'.$payment->id.'-INV';
                                $eft_data['acct_name'] = $row['chaque_address'];
                            }

                            array_push($eft_result, $eft_data);
                    }
                    elseif($payment->payable_type == 'advances'){
                        $advance                = Advance::find($payment->payable_id);
                        
                        $advance_qb = DB::table('staff')
                        ->join('banks', 'staff.bank_id', '=', 'banks.id')
                        ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                        ->where('staff.id', '=', $advance->requested_by_id)
                        ->select('staff.bank_account','banks.bank_code','bank_branches.branch_code','staff.cheque_addressee')
                        ->get();
                        // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , json_encode($advance->requested_by_id) , FILE_APPEND);
                        foreach($advance_qb as $row){
                            $eft_data['bank_code'] = $row['bank_code'];
                            $eft_data['branch'] = $row['branch_code'];
                            $eft_data['account'] = $row['bank_account'];
                            $eft_data['chaipv'] = 'CHAI'.$payment->id.'-ADV';
                            $eft_data['acct_name'] = $row['cheque_addressee'];
                        }

                        array_push($eft_result, $eft_data);
                    }
                    elseif($payment->payable_type == 'claims'){
                        $claim                = Claim::find($payment->payable_id);
                        
                        $claim_qb = DB::table('staff')
                        ->join('banks', 'staff.bank_id', '=', 'banks.id')
                        ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                        ->where('staff.id', '=', $claim->requested_by_id)
                        ->select('staff.bank_account','banks.bank_code','bank_branches.branch_code','staff.cheque_addressee')
                        ->get();
                        foreach($claim_qb as $row){
                            $eft_data['bank_code'] = $row['bank_code'];
                            $eft_data['branch'] = $row['branch_code'];
                            $eft_data['account'] = $row['bank_account'];
                            $eft_data['chaipv'] = 'CHAI'.$payment->id.'-CLM';
                            $eft_data['acct_name'] = $row['cheque_addressee'];
                        }

                        array_push($eft_result, $eft_data);
                    }
                }
                
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $eft_result), 200);
            }




            // RTGS
            elseif($payment_mode=='4'){
                $rtgs_result = [];
                
                //Getting the payments
                $qb = DB::table('payments')
                    ->whereNull('deleted_at')
                    ->where('payment_batch_id', '=', ''.$payment_batch_id)
                    ->where('payment_mode_id', '=', $payment_mode)
                    ->select('id')
                    ->get();
                foreach ($qb as $record) {          

                    $rtgs_data = array('date'=>'', 'bank_code'=>'','account'=>'','acct_name'=>'','amount'=>'','chaipv'=>'','channel'=>'BANK');

                    $payment                    = Payment::find($record['id']);
                    $rtgs_data['amount'] = $payment->amount;
                    $rtgs_data['date'] = date('Ymd', strtotime($payment->created_at));

                    if($payment->payable_type == 'invoices'){
                        $invoice                = Invoice::find($payment->payable_id);

                        $qb_invoice = DB::table('invoices')
                            ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
                            ->join('banks', 'suppliers.bank_id', '=', 'banks.id')
                            ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                            ->where('invoices.id', '=', $invoice->raised_by_id)
                            ->select('banks.bank_code','bank_branches.branch_code','suppliers.bank_account','suppliers.chaque_address')
                            ->get();
                            foreach($qb_invoice as $row){
                                $rtgs_data['bank_code'] = $this->pad_zeros(2,$row['bank_code']).$this->pad_zeros(3,$row['branch_code']);
                                $rtgs_data['account'] = $row['bank_account'];
                                $rtgs_data['chaipv'] = 'CHAI'.$payment->id.'-INV';
                                $rtgs_data['acct_name'] = $row['chaque_address'];
                            }

                            array_push($rtgs_result, $rtgs_data);
                    }
                    elseif($payment->payable_type == 'advances'){
                        $advance                = Advance::find($payment->payable_id);
                        
                        $advance_qb = DB::table('staff')
                        ->join('banks', 'staff.bank_id', '=', 'banks.id')
                        ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                        ->where('staff.id', '=', $advance->requested_by_id)
                        ->select('staff.bank_account','banks.bank_code','bank_branches.branch_code','staff.cheque_addressee')
                        ->get();
                        
                        foreach($advance_qb as $row){
                            $rtgs_data['bank_code'] = $row['bank_code'].$row['branch_code'];
                            $rtgs_data['account'] = $row['bank_account'];
                            $rtgs_data['chaipv'] = 'CHAI'.$payment->id.'-ADV';
                            $rtgs_data['acct_name'] = $row['cheque_addressee'];
                        }

                        array_push($rtgs_result, $rtgs_data);
                    }
                    elseif($payment->payable_type == 'claims'){
                        $claim                = Claim::find($payment->payable_id);
                        
                        $claim_qb = DB::table('staff')
                        ->join('banks', 'staff.bank_id', '=', 'banks.id')
                        ->join('bank_branches', 'banks.id', '=', 'bank_branches.bank_id')
                        ->where('staff.id', '=', $claim->requested_by_id)
                        ->select('staff.bank_account','banks.bank_code','bank_branches.branch_code','staff.cheque_addressee')
                        ->get();
                        foreach($claim_qb as $row){
                            $rtgs_data['bank_code'] = $row['bank_code'].$row['branch_code'];
                            $rtgs_data['account'] = $row['bank_account'];
                            $rtgs_data['chaipv'] = 'CHAI'.$payment->id.'-CLM';
                            $rtgs_data['acct_name'] = $row['cheque_addressee'];
                        }

                        array_push($rtgs_result, $rtgs_data);
                    }
                }
                
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $rtgs_result), 200);
            }

            //MMTS
            elseif($payment_mode=='2'){
                $mmts_result = [];
                
                //Getting the payments
                $qb = DB::table('payments')
                    ->whereNull('deleted_at')
                    ->where('payment_batch_id', '=', ''.$payment_batch_id)
                    ->where('payment_mode_id', '=', $payment_mode)
                    ->select('id')
                    ->get();
                foreach ($qb as $record) {          

                    $mmts_data = array('date'=>'', 'bank_code'=>'99001','phone'=>'','mobile_name'=>'','bank_name'=>'NIC','amount'=>'','chaipv'=>'');

                    $payment                    = Payment::find($record['id']);
                    $mmts_data['amount'] = $payment->amount;
                    $mmts_data['date'] = date('Ymd', strtotime($payment->created_at));

                    if($payment->payable_type == 'invoices'){
                        $invoice                = Invoice::find($payment->payable_id);

                        $qb_invoice = DB::table('invoices')
                            ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
                            ->where('invoices.id', '=', $invoice->raised_by_id)
                            ->select('suppliers.mobile_payment_number','suppliers.mobile_payment_name')
                            ->get();
                            foreach($qb_invoice as $row){
                                $mmts_data['phone'] = $row['mobile_payment_number'];
                                $mmts_data['mobile_name'] = $row['mobile_payment_name'];
                                $mmts_data['chaipv'] = 'CHAI'.$payment->id.'-INV';
                            }

                            array_push($mmts_result, $mmts_data);
                    }
                    elseif($payment->payable_type == 'advances'){
                        $advance                = Advance::find($payment->payable_id);
                        
                        $advance_qb = DB::table('staff')
                        ->where('staff.id', '=', $advance->requested_by_id)
                        ->select('staff.mpesa_no','staff.cheque_addressee')
                        ->get();
                        
                        foreach($advance_qb as $row){
                            $mmts_data['phone'] = $row['mpesa_no'];
                            $mmts_data['mobile_name'] = $row['cheque_addressee'];
                            $mmts_data['chaipv'] = 'CHAI'.$payment->id.'-ADV';
                        }

                        array_push($mmts_result, $mmts_data);
                    }
                    elseif($payment->payable_type == 'claims'){
                        $claim                = Claim::find($payment->payable_id);
                        
                        $claim_qb = DB::table('staff')
                        ->where('staff.id', '=', $claim->requested_by_id)
                        ->select('staff.mpesa_no','staff.cheque_addressee')
                        ->get();
                        
                        foreach($claim_qb as $row){
                            $mmts_data['phone'] = $row['mpesa_no'];
                            $mmts_data['mobile_name'] = $row['cheque_addressee'];
                            $mmts_data['chaipv'] = 'CHAI'.$payment->id.'-CLM';
                        }

                        array_push($mmts_result, $mmts_data);
                    }
                }
                
            return Response()->json(array('msg' => 'Success: csv generated','csv_data' => $mmts_result), 200);
            }

        }
        catch(Exception $e){
            $response =  ["error"=>"There was an error getting the CSV"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }























/**
     * Operation uploadBankFile
     *
     * upload the bank file
     *
     *
     * @return Http response
     */
    public function uploadBankFile(){
        
        $input = Request::all();
        try{
            $payment_ids = [];
            $payments = [];
            $form = Request::only('file');
            $file = $form['file'];

            $handle = fopen($file, 'r');
            $header = true;
            while($csvLine = fgetcsv($handle, 1000, ',')){
                if ($header) {
                    $header = false;
                } else {
                    $ref = explode(" ", $csvLine[1])[0];
                    array_push($payment_ids, (int)preg_replace("/[^0-9,.]/", "", $ref)); 
                }
            }
            
            // Change the status of the payment to reconciled
            foreach($payment_ids as $id){
                $payment = Payment::find($id);
                // $payment->status_id = $payment->status->next_status_id;
                if($payment->status_id==3){
                    $payment->status_id = 4; // Hard-coded
                    $payment->save();
                }           

                // Change invoice status
                if($payment->payable_type=='invoices'){
                    $invoice = Invoice::find($payment->payable_id);
                    // $invoice->status_id = $invoice->status->next_status_id;
                    if($invoice->status_id==7){
                        $invoice->status_id = 8;
                        $invoice->save();
                        array_push($payments, ['type'=>'Invoice', 'ref'=>'INV'.$invoice->id, 'paid'=>true]);

                        // Change LPO to paid
                        $lpo = Lpo::findOrFail($invoice->lpo_id);
                        $lpo->invoice_paid = 'True';
                        $lpo->status_id = 9;
                        $lpo->save();
                    }
                    else{
                        array_push($payments, ['type'=>'Invoice', 'ref'=>'INV'.$invoice->id, 'paid'=>false]);
                    }
                }
                // Change advance status
                if($payment->payable_type=='advances'){
                    $advance = Advance::find($payment->payable_id);
                    // $advance->status_id = $advance->status->next_status_id;
                    if($advance->status_id==7){
                        $advance->status_id = 6;
                        $advance->save();
                        array_push($payments, ['type'=>'Advance', 'ref'=>'ADV'.$advance->id, 'paid'=>true]);
                    }
                    else{
                        array_push($payments, ['type'=>'Advance', 'ref'=>'ADV'.$advance->id, 'paid'=>false]);
                    }
                }
                // Change claim status
                if($payment->payable_type=='claims'){
                    $claim = Claim::find($payment->payable_id);
                    // $claim->status_id = $claim->status->next_status_id;
                    if($claim->status_id==7){
                        $claim->status_id = 8;
                        $claim->save();
                        array_push($payments, ['type'=>'Claim', 'ref'=>'CLM'.$claim->id, 'paid'=>true]);
                    }
                    else{
                        array_push($payments, ['type'=>'Claim', 'ref'=>'CLM'.$claim->id, 'paid'=>false]);
                    }
                }
            }

            return Response()->json(array('msg' => 'Success: payments reconciled','payments' => $payments), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'There was an error uploading the file'], 500);
        }
    }























    
    /**
     * Operation updatePaymentBatch
     *
     * Update an existing payment_batch.
     *
     *
     * @return Http response
     */
    public function updatePaymentBatch()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updatePaymentBatch');
        }
        $body = $input['body'];


        return response('How about implementing updatePaymentBatch as a PUT method ?');
    }






















    
    /**
     * Operation deletePaymentBatch
     *
     * Deletes an payment_batch.
     *
     * @param int $payment_batch_id payment_batch id to delete (required)
     *
     * @return Http response
     */
    public function deletePaymentBatch($payment_batch_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePaymentBatch as a DELETE method ?');
    }






















    
    /**
     * Operation getPaymentBatchById
     *
     * Find payment_batch by ID.
     *
     * @param int $payment_batch_id ID of payment_batch to return object (required)
     *
     * @return Http response
     */
    public function getPaymentBatchById($payment_batch_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getPaymentBatchById as a GET method ?');
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
        $qb = DB::table('payment_batches');

        $qb->whereNull('deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;



        // $qb->where('requested_by_id',$this->current_user()->id);



        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                // $query->orWhere('payable_type','like', '\'%' . $input['searchval']. '%\'');
                // $query->orWhere('paid_to_bank_account_no','like', '\'%' . $input['searchval']. '%\'');
                // $query->orWhere('paid_to_name','like', '\'%' . $input['searchval']. '%\'');
                // $query->orWhere('payment_desc','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = PaymentBatch::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }




        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                // $query->orWhere('payable_type','like', '\'%' . $input['search']['value']. '%\'');
                // $query->orWhere('paid_to_bank_account_no','like', '\'%' . $input['search']['value']. '%\'');
                // $query->orWhere('paid_to_name','like', '\'%' . $input['search']['value']. '%\'');
                // $query->orWhere('payment_desc','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = PaymentBatch::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];


            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];



            // if ($order_column_id == 0){
            //     $order_column_name = "created_at";
            // }
            // if ($order_column_id == 1){
            //     $order_column_name = "id";
            // }

            if($order_column_name!=''){

                $qb->orderBy($order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = PaymentBatch::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = PaymentBatch::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = PaymentBatch::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }























    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $mobile_payment = PaymentBatch::find($data[$key]['id']);
            

            $data[$key]['payment_modes']                      = $mobile_payment->payment_modes;
            // $data[$key]['payable']                      = $mobile_payment->payable;
            // $data[$key]['debit_bank_account']           = $mobile_payment->debit_bank_account;
            // $data[$key]['currency']                     = $mobile_payment->currency;
            // $data[$key]['paid_to_bank']                 = $mobile_payment->paid_to_bank;
            // $data[$key]['paid_to_bank_branch']          = $mobile_payment->paid_to_bank_branch;
            // $data[$key]['payment_mode']                 = $mobile_payment->payment_mode;
            // $data[$key]['payment_batch']                = $mobile_payment->payment_batch;

        }

        return $data;


    }










    public function pad_zeros($desired_length, $data){
        if(strlen($data)<$desired_length){
            return str_repeat('0', $desired_length-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }
















    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {

            // if($value["payable"]==null){
            //     $data[$key]['payable'] = array("expense_desc"=>"N/A");
                
            // }
            // if($value["debit_bank_account"]==null){
            //     $data[$key]['debit_bank_account'] = array("title"=>"N/A","account_number"=>"N/A");
                
            // }
            // if($value["currency"]==null){
            //     $data[$key]['currency'] = array("currency_name"=>"N/A");
                
            // }
            // if($value["paid_to_bank"]==null){
            //     $data[$key]['paid_to_bank'] = array("bank_name"=>"N/A");
                
            // }
            // if($value["paid_to_bank_branch"]==null){
            //     $data[$key]['paid_to_bank_branch'] = array("branch_name"=>"N/A");
                
            // }
            // if($value["payment_mode"]==null){
            //     $data[$key]['payment_mode'] = array("payment_mode_description"=>"N/A");
                
            // }
            // if($value["payment_batch"]==null){
            //     $data[$key]['payment_batch'] = array("ref"=>"N/A");
                
            // }


        }

        return $data;


    }
}
