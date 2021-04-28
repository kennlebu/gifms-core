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
use App\Models\DeliveriesModels\Delivery;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Response;
use App\Models\LPOModels\Lpo;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyDelivery;
use App\Models\DeliveriesModels\DeliveryItem;
use App\Models\Requisitions\Requisition;
use App\Models\Requisitions\RequisitionItem;
use PDF;

class DeliveryApi extends Controller
{
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
                'file',
                'requisition_id'
            );

            $file = $form['file'];
            $delivery->received_by_id                    =   (int)   $form['received_by_id'];
            $delivery->comment                           =           $form['comment'];
            $delivery->external_ref                      =           $form['external_ref'];
            $delivery->lpo_id                            =   (int)   $form['lpo_id'];
            $delivery->supplier_id = (int) $form['supplier_id'];
            $delivery->delivery_made = $form['delivery_made'];
            $delivery->received_for_id = (int) $form['received_for_id'];
            $delivery->requisition_id = $form['requisition_id'];

            if($delivery->save()) {
                // Mark LPO as delivered if it's a full delivery
                if($delivery->delivery_made == 'full'){
                    $lpo = Lpo::with('received_by')->find($delivery->lpo_id);
                    $lpo->date_delivered = date("Y-m-d H:i:s");
                    $lpo->delivery_Comment = $delivery->comment;
                    $lpo->delivery_made = $delivery->delivery_made;
                    $lpo->status_id = 8;
                    $lpo->save();
                    
                    foreach($lpo->items as $lpo_item){ 
                        $delivery_item = new DeliveryItem();
                        $delivery_item->delivery_id = $delivery->id;
                        $delivery_item->item = $lpo_item->item;
                        $delivery_item->item_description = $lpo_item->item_description;
                        $delivery_item->qty = $lpo_item->qty;
                        $delivery_item->qty_description = $lpo_item->qty_description;
                        $delivery_item->disableLogging();
                        $delivery_item->save();
                    }

                    // Email delivery owner
                    Mail::queue(new NotifyDelivery($delivery, $lpo));
                }

                FTP::connection()->makeDir('/deliveries');
                FTP::connection()->makeDir('/deliveries/'.$delivery->id);
                FTP::connection()->uploadFile($file->getPathname(), '/deliveries/'.$delivery->id.'/'.$delivery->id.'.'.$file->getClientOriginalExtension());

                $delivery->delivery_document = $delivery->id.'.'.$file->getClientOriginalExtension();
                $delivery->save();

                $lpo = Lpo::find($delivery->lpo_id);
                $requisition = Requisition::find($lpo->requisition_id);

                if(!empty($lpo->lpo_requisition_items)){
                    foreach($lpo->lpo_requisition_items as $req_item){
                        if(!empty($req_item->id)){
                            $item = RequisitionItem::findOrFail($req_item->id);
                            $item->status_id = 3;
                            $item->disableLogging();
                            $item->save();
                        }
                    }
                }
                $delivery->ref = $requisition->ref.'-GRN-'.$this->pad_with_zeros(2, $delivery->getNextRefNumber());
                $delivery->save();

                activity()
                    ->performedOn($delivery)
                    ->causedBy($this->current_user())
                    ->withProperties(['detail' => 'Received delivery '.$delivery->ref])
                    ->log('Delivery received');

                // Add activity notification
                $this->addActivityNotification('Received delivery <strong>'.$delivery->ref.'</strong>', null, $this->current_user()->id, $delivery->received_for_id, 'info', 'deliveries', false);

                return Response()->json(array('msg'=>'Success', 'delivery'=>$delivery), 200);
            }

        }catch (\Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()], 500);
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
        $file = $form['file'];
        $delivery->received_by_id = (int)$form['received_by_id'];
        $delivery->received_for_id = (int) $form['received_for_id'];
        $delivery->comment = $form['comment'];
        $delivery->external_ref = $form['external_ref'];
        $delivery->lpo_id = (int) $form['lpo_id']; 
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
        $deleted = Delivery::destroy($delivery_id);
        if($deleted){
            return response()->json(['msg'=>"delivery deleted"], 200);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500);
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
                                        'logs.causer',
                                        'requisition',
                                        'items'
                                    )->findOrFail($delivery_id);

            return response()->json($response, 200);

        }catch(Exception $e){
            return response()->json(["error"=>$e->getMessage()], 404);
        }
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
            $delivery       = Delivery::findOrFail($delivery_id);
            $path           = '/deliveries/'.$delivery->id.'/'.$delivery->delivery_document;
            $path_info      = pathinfo($path);
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }catch (Exception $e ){
            $response       = Response::make("", 500);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
    }



    public function getDeliveryNote($delivery_id){
        try{
            $delivery = Delivery::findOrFail($delivery_id);
            $data = array('delivery' => $delivery);

            $pdf = PDF::loadView('pdf/goods_received_note', $data);
            $file_contents  = $pdf->stream();

            $response = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');

            return $response;
        }catch (Exception $e){
            $response = Response::make("", 500);
            $response->header('Content-Type', 'application/pdf');

            return $response;  
        }
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

        $qb = Delivery::query();
        if(!array_key_exists('lean', $input)){
            $qb = Delivery::with('lpo','received_by','received_for','supplier','requisition');
        }

        $total_records          = $qb->count();
        $records_filtered       = 0;

        if(array_key_exists('type', $input)){
            $type_ = $input['type'];

            if($type_==1){
                $qb = $qb->where('received_by_id',$this->current_user()->id);
            }elseif ($type_==2) {
                $qb = $qb->where('received_for_id',$this->current_user()->id);
            }
        }

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {                
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                $query->orWhere('external_ref','like', '%' . $input['search']['value']. '%');
            });
            $records_filtered = $qb->count();
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb = $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb = $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                $query->orWhere('external_ref','like', '%' . $input['search']['value']. '%');
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
            }
            else{
                $qb = $qb->limit($input['length']);
            }

            $response = Delivery::arr_to_dt_response( 
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
}
