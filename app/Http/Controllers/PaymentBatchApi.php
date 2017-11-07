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


                }

                return Response()->json(array('msg' => 'Success: Payment Batch added','payment_batch' => PaymentBatch::find((int)$payment_batch->id)), 200);
            }

        }catch (JWTException $e){

            return response()->json(['error'=>'something went wrong'], 500);

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
