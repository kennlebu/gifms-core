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

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentModels\Payment;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\PaymentModels\VoucherNumber;
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;

class PaymentApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }


    
    /**
     * Operation getPayments
     *
     * payments List.
     *
     *
     * @return Http response
     */
    public function getPayments()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('payments');

        $qb->whereNull('deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //if batch is set
        if(array_key_exists('batch', $input)){
            $batch_ = (int) $input['batch'];

            if($batch_ >0){
                $qb->where('payment_batch_id', $input['batch']);
            }
        }

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('payable_type','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('paid_to_bank_account_no','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('paid_to_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('payment_desc','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }
            $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        //if currency is set
        if(array_key_exists('currency', $input)){
            $currency = (int) $input['currency'];
            if($currency >0){
                $qb->where('currency_id', $input['currency']);
            }
        }

        if(array_key_exists('mode', $input)){
            $mode = (int) $input['mode'];
            if($mode >0){
                $qb->where('payment_mode_id', $input['mode']);
            }
        }

        //migrated
        if(array_key_exists('migrated', $input)){
            $mig = (int) $input['migrated'];
            if($mig==0){
                $qb->whereNull('migration_id');
            }else if($mig==1){
                $qb->whereNotNull('migration_id');
            }
        }

        //unbatched
        if(array_key_exists('unbatched', $input)){
            $mig = (int) $input['unbatched'];
            $qb->where('status_id',1);
        }

        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('payable_type','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('paid_to_bank_account_no','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('paid_to_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('payment_desc','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb->limit($input['length']);
            }

            $sql = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Payment::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else {
            $sql            = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }
        
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {
            $payment = Payment::with('payable.project_manager','voucher_number')->find($data[$key]['id']);

            !empty($payment->payable) && ($data[$key]['payable'] = $payment->payable);
            !empty($payment->simple_date) && ($data[$key]['simple_date'] = $payment->simple_date);
            !empty($payment->payable->currency) && ($data[$key]['payable']['currency'] = $payment->payable->currency);
            !empty($payment->debit_bank_account) && ($data[$key]['debit_bank_account'] = $payment->debit_bank_account);
            !empty($payment->currency) && ($data[$key]['currency'] = $payment->currency);
            !empty($payment->paid_to_bank) && ($data[$key]['paid_to_bank'] = $payment->paid_to_bank);
            !empty($payment->paid_to_bank_branch) && ($data[$key]['paid_to_bank_branch'] = $payment->paid_to_bank_branch);
            !empty($payment->payment_mode) && ($data[$key]['payment_mode'] = $payment->payment_mode);
            !empty($payment->payment_batch) && ($data[$key]['payment_batch'] = $payment->payment_batch);
            !empty($payment->status) && ($data[$key]['status'] = $payment->status);
            !empty($payment->payable->project_manager) && ($data[$key]['project_manager'] = $payment->payable->project_manager);
            !empty($payment->voucher_number) && ($data[$key]['voucher_number'] = $payment->voucher_number);

        }
        return $data;
    }


    public function append_relationships_nulls($data = array()){
        foreach ($data as $key => $value) {
            if(!empty($value["payable"]) && $value["payable"]==null){
                $data[$key]['payable'] = array("expense_desc"=>"N/A");                
            }
            if(!empty($value["debit_bank_account"]) && $value["debit_bank_account"]==null){
                $data[$key]['debit_bank_account'] = array("title"=>"N/A","account_number"=>"N/A");                
            }
            if(!empty($value["currency"]) && $value["currency"]==null){
                $data[$key]['currency'] = array("currency_name"=>"N/A");                
            }
            if(!empty($value["paid_to_bank"]) && $value["paid_to_bank"]==null){
                $data[$key]['paid_to_bank'] = array("bank_name"=>"N/A");                
            }
            if(!empty($value["paid_to_bank_branch"]) && $value["paid_to_bank_branch"]==null){
                $data[$key]['paid_to_bank_branch'] = array("branch_name"=>"N/A");                
            }
            if(!empty($value["payment_mode"]) && $value["payment_mode"]==null){
                $data[$key]['payment_mode'] = array("payment_mode_description"=>"N/A");                
            }
            if(!empty($value["payment_batch"]) && $value["payment_batch"]==null){
                $data[$key]['payment_batch'] = array("ref"=>"N/A");                
            }
            if(!empty($value["voucher_number"]) && $value["voucher_number"]==null){
                $data[$key]['voucher_number'] = array("voucher_number"=>"N/A");                
            }
        }

        return $data;
    }


    public function getPaymentByPV(){
        $input = Request::all();
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
                    $res['vendor'] = $payment->requested_by->name;
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
            // array_push($result, $res);
        }        
    }


    function searchTransactions(){
        try{
            $form = Request::all();

            $voucher_no = $form['voucher_no'];
            $chai_ref = $form['chai_ref'];
            $start_date = $form['start_date'];
            $end_date = $form['end_date'];
            $supplier_id = $form['supplier_id'];
            $project_id = $form['project_id'];
    
            $result = [];

            // For voucher nos
            if(!empty($voucher_no)){
                $pv_nos = VoucherNumber::where('voucher_number', 'like', '%' . $voucher_no. '%')->get();
                foreach($pv_nos as $record){
                    $row = array();
                    if($record->payable_type == 'invoices'){
                        $payment = Payment::find($record->payable_id);
                        $invoice = Invoice::with('supplier')->where('id', $payment->payable_id);
                        if(!empty($start_date) && !empty($end_date)){
                            $invoice = $invoice->whereBetween('created_at', [$start_date, $end_date]);
                        }
                        elseif(!empty($start_date)){
                            $invoice = $invoice->where('created_at', '>=', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $invoice = $invoice->where('created_at', '<=', $end_date);
                        }

                        if(!empty($supplier_id)){
                            $invoice = $invoice->where('supplier_id', $supplier_id);
                        }

                        if(!empty($chai_ref)){
                            $invoice = $invoice->where('ref', 'like', '%'.$chai_ref.'%');
                        }

                        if(!empty($project_id)){
                            $invoice = $invoice->whereHas('allocations', function($query) use ($project_id){
                                $query->where('project_id', $project_id);  
                            });
                        }
                        
                        $invoice = $invoice->with('status')->first();
                        
                        if(!empty($invoice)){
                            $row['payable_type'] = $record->payable_type;
                            $row['payable'] = $invoice;
                            array_push($result, $row);
                        } 
                    }
                    elseif($record->payable_type == 'claims' && empty($supplier_id)){
                        $payment = Payment::find($record->payable_id);
                        $claim = Claim::where('id', $payment->payable_id);
                        if(!empty($start_date) && !empty($end_date)){
                            $claim = $claim->whereBetween('created_at', [$start_date, $end_date]);
                        }
                        elseif(!empty($start_date)){
                            $claim = $claim->where('created_at', '>=', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $claim = $claim->where('created_at', '<=', $end_date);
                        }

                        if(!empty($chai_ref)){
                            $claim = $claim->where('ref', 'like', '%'.$chai_ref.'%');
                        }

                        if(!empty($project_id)){
                            $claim = $claim->whereHas('allocations', function($query) use ($project_id){
                                $query->where('project_id', $project_id);  
                            });
                        }
                        $claim = $claim->with('status')->first();

                        if(!empty($claim)){
                            $row['payable_type'] = $record->payable_type;
                            $row['payable'] = $claim;
                            array_push($result, $row);
                        }                         
                    }
                    elseif($record->payable_type == 'advances' && empty($supplier_id)){
                        $payment = Payment::find($record->payable_id);
                        $advance = Advance::where('id', $payment->payable_id);
                        if(!empty($start_date) && !empty($end_date)){
                            $advance = $advance->whereBetween('created_at', [$start_date, $end_date]);
                        }
                        elseif(!empty($start_date)){
                            $advance = $advance->where('created_at', '>=', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $advance = $advance->where('created_at', '<=', $end_date);
                        }

                        if(!empty($chai_ref)){
                            $advance = $advance = $advance->where('ref', 'like', '%'.$chai_ref.'%');
                        }

                        if(!empty($project_id)){
                            $advance = $advance->whereHas('allocations', function($query) use ($project_id){
                                $query->where('project_id', $project_id);  
                            });
                        }
                        $advance = $advance->with('status')->first();

                        if(!empty($advance)){
                            $row['payable'] = $advance;
                            $row['payable_type'] = $record->payable_type;
                            array_push($result, $row);
                        } 
                    }
                    elseif($record->payable_type == 'mobile_payments' && empty($supplier_id)){
                        $mobile_payment = MobilePayment::where('id', $record->payable_id);
                        if(!empty($start_date) && !empty($end_date)){
                            $mobile_payment = $mobile_payment->whereBetween('created_at', [$start_date, $end_date]);
                        }
                        elseif(!empty($start_date)){
                            $mobile_payment = $mobile_payment->where('created_at', '>=', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $mobile_payment = $mobile_payment->where('created_at', '<=', $end_date);
                        }

                        if(!empty($chai_ref)){
                            $mobile_payment = $mobile_payment->where('ref', 'like', '%'.$chai_ref.'%');
                        }

                        if(!empty($project_id)){
                            $mobile_payment = $mobile_payment->whereHas('allocations', function($query) use ($project_id){
                                $query->where('project_id', $project_id);  
                            });
                        }
                        $mobile_payment = $mobile_payment->with('status')->first();

                        if(!empty($mobile_payment)){
                            $row['payable'] = $mobile_payment;
                            $row['payable_type'] = $record->payable_type;
                            array_push($result, $row);
                        }
                    }
                }
            }

            // No voucher number
            elseif(empty($voucher_no)){
                $row = array();

                // Invoice
                $invoice = Invoice::with('status','supplier');
                if(!empty($start_date) && !empty($end_date)){
                    $invoice = $invoice->whereBetween('created_at', [$start_date, $end_date]);
                }
                else{
                    if(!empty($start_date)){
                        $invoice = $invoice->where('created_at', '>', $start_date);
                    }
                    elseif(!empty($end_date)){
                        $invoice = $invoice->where('created_at', '<', $end_date);
                    }
                }

                if(!empty($supplier_id)){
                    $invoice = $invoice->where('supplier_id', $supplier_id);
                }

                if(!empty($chai_ref)){
                    $invoice = $invoice->where('ref', 'like', '%'.$chai_ref.'%');
                }

                if(!empty($project_id)){
                    $invoice = $invoice->whereHas('allocations', function($query) use ($project_id){
                        $query->where('project_id', $project_id);  
                    });
                }
                $invoice = $invoice->get();

                foreach($invoice as $record){
                    $row['payable_type'] = 'invoices';
                    $row['payable'] = $record;
                    array_push($result, $row);
                }

                // Search claims, advances, mobile payments only if supplier is not selected
                if(empty($supplier_id)){
                    // Claim
                    $claim = Claim::with('status','requested_by');
                    if(!empty($start_date) && !empty($end_date)){
                        $claim = $claim->whereBetween('created_at', [$start_date, $end_date]);
                    }
                    else{
                        if(!empty($start_date)){
                            $claim = $claim->where('created_at', '>', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $claim = $claim->where('created_at', '<', $end_date);
                        }
                    }

                    if(!empty($chai_ref)){
                        $claim = $claim->where('ref', 'like', '%'.$chai_ref.'%');
                    }

                    if(!empty($project_id)){
                        $claim = $claim->whereHas('allocations', function($query) use ($project_id){
                            $query->where('project_id', $project_id);  
                        });
                    }
                    $claim = $claim->get();

                    foreach($claim as $record){
                        $row['payable_type'] = 'claims';
                        $row['payable'] = $record;
                        array_push($result, $row);
                    }

                    // Advance
                    $advance = Advance::with('status','requested_by');
                    if(!empty($start_date) && !empty($end_date)){
                        $advance = $advance->whereBetween('created_at', [$start_date, $end_date]);
                    }
                    else{
                        if(!empty($start_date)){
                            $advance = $advance->where('created_at', '>', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $advance = $advance->where('created_at', '<', $end_date);
                        }
                    }

                    if(!empty($chai_ref)){
                        $advance = $advance->where('ref', 'like', '%'.$chai_ref.'%');
                    }

                    if(!empty($project_id)){
                        $advance = $advance->whereHas('allocations', function($query) use ($project_id){
                            $query->where('project_id', $project_id);  
                        });
                    }
                    $advance = $advance->get();

                    foreach($advance as $record){
                        $row['payable_type'] = 'advances';
                        $row['payable'] = $record;
                        array_push($result, $row);
                    }

                    // Mobile Payment
                    $mobile_payment = MobilePayment::with('status','requested_by');
                    if(!empty($start_date) && !empty($end_date)){
                        $mobile_payment = $mobile_payment->whereBetween('created_at', [$start_date, $end_date]);
                    }
                    else{
                        if(!empty($start_date)){
                            $mobile_payment = $mobile_payment->where('created_at', '>', $start_date);
                        }
                        elseif(!empty($end_date)){
                            $mobile_payment = $mobile_payment->where('created_at', '<', $end_date);
                        }
                    }

                    if(!empty($chai_ref)){
                        $mobile_payment = $mobile_payment->where('ref', 'like', '%'.$chai_ref.'%');
                    }

                    if(!empty($project_id)){
                        $mobile_payment = $mobile_payment->whereHas('allocations', function($query) use ($project_id){
                            $query->where('project_id', $project_id);  
                        });
                    }
                    $mobile_payment = $mobile_payment->get();

                    foreach($mobile_payment as $record){
                        $row['payable_type'] = 'mobile_payments';
                        $row['payable'] = $record;
                        array_push($result, $row);
                    }
                }
            }

            return Response()->json($result, 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'There was an error finding the transactions'], 500);
        }
        
    }






}
