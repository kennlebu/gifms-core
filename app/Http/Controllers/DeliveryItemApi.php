<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveriesModels\Delivery;
use App\Models\DeliveriesModels\DeliveryItem;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\ApprovalsModels\Approval;
use App\Models\ApprovalsModels\ApprovalLevel;
use App\Models\StaffModels\Staff;
use Illuminate\Support\Facades\Response;
// use App\Models\LPOModels\Lpo;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\NotifyDelivery;

class DeliveryItemApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addDeliveryItem
     *
     * Add a new delivery item.
     *
     *
     * @return Http response
     */
    public function addDeliveryItem()
    {

        $delivery_item = new DeliveryItem;

        try{

            $form = Request::all();

            $delivery_item->delivery_id = (int) $form['delivery_id'];
            $delivery_item->item = $form['item'];
            $delivery_item->item_description = $form['item_description'];
            $delivery_item->qty = (int) $form['qty'];
            $delivery_item->qty_description = $form['qty_description'];

            if($delivery_item->save()) {
                return Response()->json(array('msg' => 'Success: Item added','delivery_item' => $delivery_item), 200);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
    }

    /**
     * Operation updateDeliveryItem
     *
     * Update an existing delivery.
     *
     *
     * @return Http response
     */
    public function updateDeliveryItem()
    {
        $form = Request::all();

        $delivery_item = DeliveryItem::find($form['id']);

        // $delivery_item->delivery_id = (int) $form['delivery_id'];
        $delivery_item->item = $form['item'];
        $delivery_item->item_description = $form['item_description'];
        $delivery_item->qty = (int) $form['qty'];
        $delivery_item->qty_description = $form['qty_description'];

        if($delivery_item->save()) {
            return Response()->json(array('msg' => 'Success: Delivery updated','delivery' => $delivery_item), 200);
        }
    }

    /**
     * Operation deleteDeliveryItem
     *
     * Deletes a delivery item.
     *
     * @param int $delivery_item_id delivery id to delete (required)
     *
     * @return Http response
     */
    public function deleteDeliveryItem($delivery_item_id)
    {
        $input = Request::all();

        $deleted = DeliveryItem::destroy($delivery_item_id);

        if($deleted){
            return response()->json(['msg'=>"item deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"item not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Operation getDeliveryItemById
     *
     * Find delivery item by ID.
     *
     * @param int $delivery_item_id ID of delivery to return object (required)
     *
     * @return Http response
     */
    public function getDeliveryItemById($delivery_item_id)
    {
        $response = [];

        try{
            $response = DeliveryItem::with('delivery')->findOrFail($delivery_item_id);

            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Operation getDeliveryItems
     *
     * delivery items List.
     *
     *
     * @return Http response
     */
    public function getDeliveryItems()
    {

        $input = Request::all();

        $qb = DeliveryItem::with('delivery');

        if(array_key_exists('delivery_id', $input)){
            $qb->where('delivery_id', (int) $input['delivery_id']);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        //limit $ offset
        if(array_key_exists('datatables', $input)){
            if((int)$input['start']!= 0 ){
                $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb->limit($input['length']);
            }
        }


        $response = $qb->get();


        // //query builder
        // $qb = DB::table('deliveries');

        // $qb->whereNull('deleted_at');

        // $response;
        // $response_dt;

        // $total_records          = $qb->count();
        // $records_filtered       = 0;


        // //if type is set

        // if(array_key_exists('type', $input)){

        //     $type_ = (int) $input['type'];

        //     if($type_==1){
        //         $qb->where('received_by_id',$this->current_user()->id);
        //     }elseif ($type_==2) {
        //         $qb->where('received_for_id',$this->current_user()->id);
        //     }
        // }





        // // $qb->where('requested_by_id',$this->current_user()->id);



        // //searching
        // if(array_key_exists('searchval', $input)){
        //     $qb->where(function ($query) use ($input) {
                
        //         $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
        //         $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
        //         $query->orWhere('external_ref','like', '\'%' . $input['search']['value']. '%\'');

        //     });

        //     // $records_filtered       =  $qb->count(); //doesn't work

        //     $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
        //     $sql = str_replace("*"," count(*) AS count ", $sql);
        //     $dt = json_decode(json_encode(DB::select($sql)), true);

        //     $records_filtered = (int) $dt[0]['count'];
        //     // $records_filtered = 30;


        // }


        // //ordering
        // if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
        //     $order_direction     = "asc";
        //     $order_column_name   = $input['order_by'];
        //     if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
        //         $order_direction = $input['order_dir'];
        //     }

        //     $qb->orderBy($order_column_name, $order_direction);
        // }else{
        //     //$qb->orderBy("project_code", "asc");
        // }

        // //limit
        // if(array_key_exists('limit', $input)){


        //     $qb->limit($input['limit']);


        // }

        // //migrated
        // if(array_key_exists('migrated', $input)){

        //     $mig = (int) $input['migrated'];

        //     if($mig==0){
        //         $qb->whereNull('migration_id');
        //     }else if($mig==1){
        //         $qb->whereNotNull('migration_id');
        //     }


        // }




        // if(array_key_exists('datatables', $input)){

        //     //searching
        //     $qb->where(function ($query) use ($input) {
                
        //         $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
        //         $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
        //         $query->orWhere('external_ref','like', '\'%' . $input['search']['value']. '%\'');

        //     });




        //     $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
        //     $sql = str_replace("*"," count(*) AS count ", $sql);
        //     $dt = json_decode(json_encode(DB::select($sql)), true);

        //     $records_filtered = (int) $dt[0]['count'];


        //     //ordering
        //     $order_column_id    = (int) $input['order'][0]['column'];
        //     $order_column_name  = $input['columns'][$order_column_id]['order_by'];
        //     $order_direction    = $input['order'][0]['dir'];



        //     // if ($order_column_id == 0){
        //     //     $order_column_name = "created_at";
        //     // }
        //     // if ($order_column_id == 1){
        //     //     $order_column_name = "id";
        //     // }

        //     if($order_column_name!=''){

        //         $qb->orderBy($order_column_name, $order_direction);

        //     }






        //     //limit $ offset
        //     if((int)$input['start']!= 0 ){

        //         $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

        //     }else{
        //         $qb->limit($input['length']);
        //     }





        //     $sql = Delivery::bind_presql($qb->toSql(),$qb->getBindings());

        //     // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
        //     $response_dt = DB::select($sql);


        //     $response_dt = json_decode(json_encode($response_dt), true);

        //     $response_dt    = $this->append_relationships_objects($response_dt);
        //     $response_dt    = $this->append_relationships_nulls($response_dt);
        //     $response       = Delivery::arr_to_dt_response( 
        //         $response_dt, $input['draw'],
        //         $total_records,
        //         $records_filtered
        //         );


        // }else{

        //     $sql            = Delivery::bind_presql($qb->toSql(),$qb->getBindings());
        //     $response       = json_decode(json_encode(DB::select($sql)), true);
        //     if(!array_key_exists('lean', $input)){
        //         $response       = $this->append_relationships_objects($response);
        //         $response       = $this->append_relationships_nulls($response);
        //     }
        // }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }










































    // public function append_relationships_objects($data = array()){

    //     // print_r($data);

    //     foreach ($data as $key => $value) {

    //         $delivery = Delivery::find($data[$key]['id']);

    //         $data[$key]['lpo']                      = $delivery->lpo;
    //         $data[$key]['received_by']              = $delivery->received_by;
    //         $data[$key]['received_for']             = $delivery->received_for;

    //     }

    //     return $data;


    // }


































    



    // public function append_relationships_nulls($data = array()){


    //     foreach ($data as $key => $value) {

    //         if($value["lpo"]==null){
    //             $data[$key]['lpo'] = array("expense_desc"=>"N/A");
                
    //         }
    //         if($value["received_by"]==null){
    //             $data[$key]['received_by'] = array("full_name"=>"N/A");
                
    //         }


    //     }

    //     return $data;


    // }
}
