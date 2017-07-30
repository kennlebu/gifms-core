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

class PaymentApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }






















    

    /**
     * Operation addPayment
     *
     * Add a new payment.
     *
     *
     * @return Http response
     */
    public function addPayment()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addPayment');
        }
        $body = $input['body'];


        return response('How about implementing addPayment as a POST method ?');
    }






















    
    /**
     * Operation updatePayment
     *
     * Update an existing payment.
     *
     *
     * @return Http response
     */
    public function updatePayment()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updatePayment');
        }
        $body = $input['body'];


        return response('How about implementing updatePayment as a PUT method ?');
    }






















    
    /**
     * Operation getPaymentById
     *
     * Find payment by ID.
     *
     * @param int $payment_id ID of payment to return object (required)
     *
     * @return Http response
     */
    public function getPaymentById($payment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getPaymentById as a GET method ?');
    }






















    
    /**
     * Operation deletePayment
     *
     * Deletes an payment.
     *
     * @param int $payment_id payment id to delete (required)
     *
     * @return Http response
     */
    public function deletePayment($payment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePayment as a DELETE method ?');
    }






















    
    /**
     * Operation getDocumentById
     *
     * get payment document by ID.
     *
     * @param int $payment_id ID of payment to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($payment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getDocumentById as a GET method ?');
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




            // $total_records          = $qb->count();     //may need this
        }




        // $qb->where('requested_by_id',$this->current_user()->id);



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

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


        }

        //migrated
        if(array_key_exists('migrated', $input)){

            $mig = (int) $input['migrated'];

            if($mig==1){
                $qb->where('migration_id', '<', 1);
            }else if($mig==0){
                $qb->where('migration_id', '>', 0);
            }


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





            $sql = Payment::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Payment::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Payment::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }























    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $payment = Payment::find($data[$key]['id']);

            $data[$key]['payable']                      = $payment->payable;
            $data[$key]['debit_bank_account']           = $payment->debit_bank_account;
            $data[$key]['currency']                     = $payment->currency;
            $data[$key]['paid_to_bank']                 = $payment->paid_to_bank;
            $data[$key]['paid_to_bank_branch']          = $payment->paid_to_bank_branch;
            $data[$key]['payment_mode']                 = $payment->payment_mode;
            $data[$key]['payment_batch']                = $payment->payment_batch;

        }

        return $data;


    }
















    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {

            if($value["payable"]==null){
                $data[$key]['payable'] = array("expense_desc"=>"N/A");
                
            }
            if($value["debit_bank_account"]==null){
                $data[$key]['debit_bank_account'] = array("title"=>"N/A","account_number"=>"N/A");
                
            }
            if($value["currency"]==null){
                $data[$key]['currency'] = array("currency_name"=>"N/A");
                
            }
            if($value["paid_to_bank"]==null){
                $data[$key]['paid_to_bank'] = array("bank_name"=>"N/A");
                
            }
            if($value["paid_to_bank_branch"]==null){
                $data[$key]['paid_to_bank_branch'] = array("branch_name"=>"N/A");
                
            }
            if($value["payment_mode"]==null){
                $data[$key]['payment_mode'] = array("payment_mode_description"=>"N/A");
                
            }
            if($value["payment_batch"]==null){
                $data[$key]['payment_batch'] = array("ref"=>"N/A");
                
            }


        }

        return $data;


    }






}
