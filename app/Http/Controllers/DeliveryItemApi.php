<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Models\DeliveriesModels\DeliveryItem;

class DeliveryItemApi extends Controller
{
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
            return response()->json(['error'=>'Something went wrong'], 500);
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
        $deleted = DeliveryItem::destroy($delivery_item_id);

        if($deleted){
            return response()->json(['msg'=>"item deleted"], 200);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500);
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
        try{
            $response = DeliveryItem::with('delivery')->findOrFail($delivery_item_id);
            return response()->json($response, 200);
        }
        catch(Exception $e){
            return response()->json(["error"=>$e->getMessage()], 500);
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
        return response()->json($response, 200);
    }
}
