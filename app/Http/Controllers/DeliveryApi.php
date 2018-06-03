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
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\ApprovalsModels\Approval;
use App\Models\ApprovalsModels\ApprovalLevel;
use App\Models\StaffModels\Staff;
use Illuminate\Support\Facades\Response;
use App\Models\LPOModels\Lpo;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyDelivery;

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

        $delivery = new Delivery;

        try{

            $form = Request::only(
                'received_by_id',
                'comment',
                'external_ref',
                'lpo_id',
                'supplier_id',
                'delivery_made',
                'received_for_id',
                'file'
            );

            $ftp = FTP::connection()->getDirListing();
            $file = $form['file'];

            $delivery->received_by_id                    =   (int)   $form['received_by_id'];
            $delivery->comment                           =           $form['comment'];
            $delivery->external_ref                      =           $form['external_ref'];
            $delivery->lpo_id                            =   (int)   $form['lpo_id'];
            $delivery->supplier_id = (int) $form['supplier_id'];
            $delivery->delivery_made = $form['delivery_made'];
            $delivery->received_for_id = (int) $form['received_for_id'];

            if($delivery->save()) {
                // Mark LPO as delivered if it's a partial delivery
                if($delivery->delivery_made == 'full'){
                    $lpo = Lpo::find($delivery->lpo_id);
                    $lpo->date_delivered = date("Y-m-d H:i:s");
                    $lpo->delivery_Comment = $delivery->comment;
                    $lpo->delivery_made = $delivery->delivery_made;
                    $lpo->status_id = $lpo->status->next_status_id;
                    $lpo->save();

                // Email delivery owner
                    try{
                        Mail::queue(new NotifyDelivery($delivery, $lpo));
                    }catch(Exception $e){

                    }
                }

                FTP::connection()->makeDir('/deliveries');
                FTP::connection()->makeDir('/deliveries/'.$delivery->id);
                FTP::connection()->uploadFile($file->getPathname(), '/deliveries/'.$delivery->id.'/'.$delivery->id.'.'.$file->getClientOriginalExtension());

                $delivery->delivery_document           =   $delivery->id.'.'.$file->getClientOriginalExtension();
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
        $form = Request::only(
            'id',
            'received_by_id',
            'received_for_id',
            'comment',
            'external_ref',
            'lpo_id',
            'supplier_id',
            'file'
            );

        $delivery = Delivery::find($form['id']);
        $ftp = FTP::connection()->getDirListing();
        $file = $form['file'];
        file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , '\nSQL:: '.$file , FILE_APPEND);




            $delivery->received_by_id                   =   (int)       $form['received_by_id'];
            $delivery->received_for_id  = (int) $form['received_for_id'];
            $delivery->comment                      =               $form['comment'];
            $delivery->external_ref                   =               $form['external_ref'];
            $delivery->lpo_id                =   (int)       $form['lpo_id']; 
            $delivery->supplier_id = (int) $form['supplier_id'];

        if($delivery->save()) {
            if($file!=0){
                FTP::connection()->makeDir('/deliveries');
                FTP::connection()->makeDir('/deliveries/'.$delivery->id);
                FTP::connection()->uploadFile($file->getPathname(), '/deliveries/'.$delivery->id.'/'.$delivery->id.'.'.$file->getClientOriginalExtension());
            }

            return Response()->json(array('msg' => 'Success: Delivery updated','delivery' => $delivery), 200);
        }
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

        $deleted = Delivery::destroy($delivery_id);

        if($deleted){
            return response()->json(['msg'=>"delivery deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"delivery not found"], 404,array(),JSON_PRETTY_PRINT);
        }
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
        $response = [];

        try{
            $response   = Delivery::with( 
                                        'received_by',
                                        'received_for',
                                        'comments',
                                        'supplier',
                                        'lpo',
                                        'logs'
                                    )->findOrFail($delivery_id);

            foreach ($response->logs as $key => $value) {
                
                $response['logs'][$key]['causer']   =   $value->causer;
                $response['logs'][$key]['subject']  =   $value->subject;
            }

            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
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
        try{


            $delivery          = Delivery::findOrFail($delivery_id);

            $path           = '/deliveries/'.$delivery->id.'/'.$delivery->delivery_document;

            $path_info      = pathinfo($path);

            // $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('deliveries/'.$delivery->id.'.temp', $file_contents);

            $url            = storage_path("app/deliveries/".$delivery->id.'.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make("", 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;  

        }
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


        //if type is set

        if(array_key_exists('type', $input)){

            $type_ = (int) $input['type'];

            if($type_==1){
                $qb->where('received_by_id',$this->current_user()->id);
            }elseif ($type_==2) {
                $qb->where('received_for_id',$this->current_user()->id);
            }
        }





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


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb->orderBy($order_column_name, $order_direction);
        }else{
            //$qb->orderBy("project_code", "asc");
        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


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
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }










































    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $delivery = Delivery::with('supplier')->find($data[$key]['id']);

            $data[$key]['lpo']                      = $delivery->lpo;
            $data[$key]['received_by']              = $delivery->received_by;
            $data[$key]['received_for']             = $delivery->received_for;
            $data[$key]['supplier']                 = $delivery->supplier;

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
