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

use App\Models\FinanceModels\TaxRate;
use Illuminate\Support\Facades\Request;
use App\Models\LPOModels\LpoItem;
use App\Models\LPOModels\Lpo;

class LPOItemApi extends Controller
{
    /**
     * Operation addLpoItem
     *
     * Add a new lpo item.
     *
     *
     * @return Http response
     */
    public function addLpoItem()
    {
        try{
            $form = Request::all();
            if(array_key_exists('requisition_item_id', $form) && !empty($form['requisition_item_id'])){
                $lpo_item = LpoItem::where('requisition_item_id', $form['requisition_item_id'])
                                    ->where('lpo_id', $form['lpo_id'])->first();
                $lpo_item->requisition_item_id = $form['requisition_item_id'];
            }
            else {
                $lpo_item = new LpoItem;
            }

            $lpo_item->lpo_id                       =               $form['lpo_id'];
            $lpo_item->item                         =               $form['item'];
            $lpo_item->item_description             =               $form['item_description'];
            $lpo_item->qty                          =   (int)       $form['qty'];
            $lpo_item->qty_description              =               $form['qty_description'];
            $lpo_item->unit_price                   =   (double)    $form['unit_price'];
            $lpo_item->vat_charge                   =   (int)       $form['vat_charge'];

            $tax_rate = TaxRate::where('charge', 'VAT')->first();
            $lpo_item->vat_rate = $tax_rate->rate ?? 16;

            if(array_key_exists('lpo_type', $form) && $form['lpo_type']=='prenegotiated'){
                $lpo = Lpo::findOrFail($form['lpo_id']);
                $lpo->supplier_id = $form['supplier_id'];
                $lpo->currency_id = $form['currency_id'];
                $lpo->save();
            }
            if(!empty($form['unit'])){
                $lpo_item->qty_description = $form['qty'].' '.$form['unit'];                                // Get quantity description from
            }                                                                                               // the quantity and the unit of 
            if(!empty($form['no_of_days'])){                                                                // measure.
                $lpo_item->no_of_days = $form['no_of_days'];
                $lpo_item->qty_description = $lpo_item->qty_description.'; '.$form['no_of_days'].' days';   // Add the number of days to the
            }                                                                                               // quantity description.

            if(!empty($form['expensive_quotation_reason'])){
                $lpo = Lpo::find($lpo_item->lpo_id);
                $lpo->expensive_quotation_reason = $form['expensive_quotation_reason'];
                $lpo->disableLogging(); //! Do not log the update
                $lpo->save();
            }

            if($lpo_item->save()) {
                return Response()->json(array('success' => 'lpo quoatation added','lpo_item' => $lpo_item), 200);
            }

        }catch (JWTException $e){
                return response()->json(['error'=>'You are not Authenticated'], 500);
        }     
    }



























    
    /**
     * Operation updateLpoItem
     *
     * Update an existing LPO Item.
     *
     *
     * @return Http response
     */
    public function updateLpoItem()
    {
        try{
            $form = Request::all();

            $item = LpoItem::findOrFail($form['id']);
            $item->lpo_id                   =               $form['lpo_id'];
            $item->item                     =               $form['item'];
            $item->item_description         =               $form['item_description'];
            $item->qty                      =               $form['qty'];
            $item->qty_description          =               $form['qty_description'];
            $item->unit_price               =               $form['unit_price'];
            $item->vat_charge               =               $form['vat_charge'];
            $item->disableLogging();
            
            if(array_key_exists('lpo_type', $form) && $form['lpo_type']=='prenegotiated'){
                $lpo = Lpo::findOrFail($form['lpo_id']);
                $lpo->supplier_id = $form['supplier_id'];
                $lpo->currency_id = $form['currency_id'];
                $lpo->disableLogging();
                $lpo->save();
            }
            if(!empty($form['no_of_days'])){
                $item->no_of_days = $form['no_of_days'];
            }

            if($item->save()) {
                $lpo = Lpo::find($form['lpo_id']);

                // Logging submission
                activity()
                   ->performedOn($lpo)
                   ->causedBy($this->current_user())
                   ->withProperties(['detail'=>'LPO item '.$item->item.' has been edited'])
                   ->log('Item updated');

                return Response()->json(array('success' => 'Item updated','lpo_item' => $item), 200);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'You are not Authenticated'], 401);
        }
    }



























    
    /**
     * Operation deleteLpoItem
     *
     * Deletes an lpo_item.
     *
     * @param int $lpo_item_id lpo item id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpoItem($lpo_item_id)
    {
        $deleted_lpo_item = LpoItem::destroy($lpo_item_id);
        if($deleted_lpo_item){
            return response()->json(['msg'=>"lpo item deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }



























    
    /**
     * Operation getLpoItemById
     *
     * Find lpo item by ID.
     *
     * @param int $lpo_item_id ID of lpo item to return object (required)
     *
     * @return Http response
     */
    public function getLpoItemById($lpo_item_id)
    {
       try{
            $response = LpoItem::with('lpo')->findOrFail($lpo_item_id);
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }



























    
    /**
     * Operation lpoItemsGet
     *
     * lpo items List.
     *
     *
     * @return Http response
     */
    public function lpoItemsGet()
    {
        $input = Request::all();
        $response;

        if(array_key_exists('lpo_id', $input)){
            $response = LpoItem::where("deleted_at",null)
                ->where('lpo_id', $input['lpo_id'])
                ->get();
        }else{
            $response = LpoItem::all();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }    
}
