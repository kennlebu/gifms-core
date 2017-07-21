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
use App\Models\DeliveriesModels\Delivery;

class DeliveryApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }
























    /**
     * Operation addDelivery
     *
     * Add a new delivery.
     *
     *
     * @return Http response
     */
    public function addDelivery()
    {

        $input = Request::all();



        $form = Request::only(
            'received_by_id',
            'comment',
            'external_ref',
            'lpo_id'
            );

        try{

            $delivery = new Delivery;

            $delivery->received_by_id                    =   (int)   $form['received_by_id'];
            $delivery->comment                           =           $form['comment'];
            $delivery->external_ref                      =           $form['external_ref'];
            $delivery->lpo_id                            =   (int)   $form['lpo_id'];
            // $delivery->status_id                         =   2;

            // $user = JWTAuth::parseToken()->authenticate();
            // $delivery->request_action_by_id            =   (int)   $user->id;



            if($delivery->save()) {

                $delivery->ref = "CHAI/DLV/#$delivery->id/".date_format($delivery->created_at,"Y/m/d");
                $delivery->save();

                return Response()->json(array('msg' => 'Success: delivery added','delivery' => Delivery::find((int)$delivery->id)), 200);
            }

        }catch (JWTException $e){

            return response()->json(['error'=>'something went wrong'], 500);

        }
    }
























    /**
     * Operation updateDelivery
     *
     * Update an existing delivery.
     *
     *
     * @return Http response
     */
    public function updateDelivery()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateDelivery');
        }
        $body = $input['body'];


        return response('How about implementing updateDelivery as a PUT method ?');
    }
























    /**
     * Operation deleteDelivery
     *
     * Deletes an delivery.
     *
     * @param int $delivery_id delivery id to delete (required)
     *
     * @return Http response
     */
    public function deleteDelivery($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteDelivery as a DELETE method ?');
    }
























    /**
     * Operation getDeliveryById
     *
     * Find delivery by ID.
     *
     * @param int $delivery_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function getDeliveryById($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getDeliveryById as a GET method ?');
    }
























    /**
     * Operation allocateDelivery
     *
     * Allocate delivery by ID.
     *
     * @param int $delivery_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function allocateDelivery($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing allocateDelivery as a PATCH method ?');
    }
























    /**
     * Operation approveDelivery
     *
     * Approve delivery by ID.
     *
     * @param int $delivery_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function approveDelivery($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing approveDelivery as a PATCH method ?');
    }
























    /**
     * Operation getDocumentById
     *
     * get delivery document by ID.
     *
     * @param int $delivery_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getDocumentById as a GET method ?');
    }
























    /**
     * Operation submitDeliveryForApproval
     *
     * Submit delivery by ID.
     *
     * @param int $delivery_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function submitDeliveryForApproval($delivery_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing submitDeliveryForApproval as a PATCH method ?');
    }


























    /**
     * Operation getDeliveries
     *
     * deliveries List.
     *
     *
     * @return Http response
     */
    public function getDeliveries()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('deliveries');

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
                $query->orWhere('external_ref','like', '\'%' . $input['search']['value']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
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
                $query->orWhere('external_ref','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Delivery::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }










































    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $delivery = Delivery::find($data[$key]['id']);

            $data[$key]['lpo']                      = $delivery->lpo;
            $data[$key]['received_by']              = $delivery->received_by;

        }

        return $data;


    }


































    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {

            if($value["lpo"]==null){
                $data[$key]['lpo'] = array("expense_desc"=>"N/A");
                
            }
            if($value["received_by"]==null){
                $data[$key]['received_by'] = array("full_name"=>"N/A");
                
            }


        }

        return $data;


    }
}
