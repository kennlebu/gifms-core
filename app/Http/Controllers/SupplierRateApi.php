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
use App\Models\SuppliesModels\SupplierRate;
use App\Models\SuppliesModels\SupplierRateTerms;
use Illuminate\Support\Facades\DB;

class SupplierRateApi extends Controller
{
    /**
     * Operation addSupplierRate
     *
     * Add a new supplier_rate.
     *
     *
     * @return Http response
     */
    public function addSupplierRate()
    {
        try{
            $input = Request::all();
            $rate = new SupplierRate;
            $rate->service_id = (int) $input['service_id'];
            $rate->supplier_id = (int) $input['supplier_id'];
            $rate->rate = $input['rate'];
            $rate->currency_id = (int) $input['currency_id'];
            $rate->vat = (int) $input['vat'];
            if(!empty($input['daily_charge']))
            $rate->daily_charge = (int) $input['daily_charge'];
            $rate->unit = $input['unit'];

            if($rate->save()){
                return response()->json(['msg'=>"rate added"], 200,array(),JSON_PRETTY_PRINT);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }


    
    /**
     * Operation updateSupplierRate
     *
     * Update an existing supplier_rate.
     *
     *
     * @return Http response
     */
    public function updateSupplierRate()
    {
        try{
            $input = Request::all();
            $rate =  SupplierRate::findOrFail($input['id']);
            $rate->service_id = (int) $input['service_id'];
            $rate->supplier_id = (int) $input['supplier_id'];
            $rate->rate = $input['rate'];
            $rate->currency_id = (int) $input['currency_id'];
            $rate->vat = (int) $input['vat'];
            if(!empty($input['daily_charge']))
            $rate->daily_charge = (int) $input['daily_charge'];
            $rate->unit = $input['unit'];
    
            if($rate->save()){
                return response()->json(['msg'=>"rate updated"], 200,array(),JSON_PRETTY_PRINT);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }




    
    /**
     * Operation deleteSupplierRate
     *
     * Deletes an supplier_rate.
     *
     * @param int $supplier_rate_id supplier_rate id to delete (required)
     *
     * @return Http response
     */
    public function deleteSupplierRate($supplier_rate_id)
    {
        $deleted = SupplierRate::destroy($supplier_rate_id);
        if($deleted){
            return response()->json(['msg'=>"supplier rate deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }




    
    /**
     * Operation getSupplierRateById
     *
     * Find supplier_rate by ID.
     *
     * @param int $supplier_rate_id ID of supplier_rate to return object (required)
     *
     * @return Http response
     */
    public function getSupplierRateById($supplier_rate_id)
    {
        try{
            $response   = SupplierRate::with('terms')->findOrFail($supplier_rate_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(\Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }



    
    /**
     * Operation supplierRatesGet
     *
     * supplier_rates List.
     *
     *
     * @return Http response
     */
    public function supplierRatesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('supplier_rates');
        $qb->leftJoin('suppliers', 'supplier_rates.supplier_id', '=', 'suppliers.id');
        $qb->select('supplier_rates.*');

        $qb->whereNull('supplier_rates.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        // For county
        if(array_key_exists('county_id', $input) && !empty($input['county_id'])){
            $qb->where('suppliers.county_id', $input['county_id']);
        }

        // For supply category
        if(array_key_exists('supply_category_id', $input) && !empty($input['supply_category_id'])){
            $qb->where('suppliers.supply_category_id', $input['supply_category_id']);
        }

        // For service
        if(array_key_exists('service_id', $input) && !empty($input['service_id'])){
            $qb->where('supplier_rates.service_id', $input['service_id']);
        }
        
        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supplier_rates.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('supplier_rates.rate','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('suppliers.supplier_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = SupplierRate::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("supplier_rates.*"," count(*) AS count ", $sql);
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

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supplier_rates.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('supplier_rates.rate','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.supplier_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = SupplierRate::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("`supplier_rates`.*"," count(*) AS count ", $sql);
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
                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb->limit($input['length']);
            }

            $sql = SupplierRate::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = SupplierRate::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $sql            = SupplierRate::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    private function append_relationships_objects($data = array()){
        foreach ($data as $key => $value) {
            $supplier_rate = SupplierRate::find($data[$key]['id']);
            $data[$key]['service'] = $supplier_rate->service;
            $data[$key]['supplier'] = $supplier_rate->supplier;
            $data[$key]['currency'] = $supplier_rate->currency;            
            $data[$key]['terms'] = $supplier_rate->terms;
        }
        return $data;
    }

    private function append_relationships_nulls($data = array()){
        foreach ($data as $key => $value) {
            if($data[$key]["service"]==null){
                $data[$key]["service"] = array("service_name"=>"N/A");
            }
            if($data[$key]["supplier"]==null){
                $data[$key]["supplier"] = array("supplier_name"=>"N/A");
            }
            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
            if($data[$key]["terms"]==null){
                $data[$key]["terms"] = array("terms"=>"N/A");
            }
        }
        return $data;
    }


    /**
     * Add Supplier Rate terms
     */
    public function addSupplierRateTerm()
    {
        try{
        $input = Request::all();
        $rate_term = new SupplierRateTerms;
        $rate_term->terms = $input['terms'];
        $rate_term->supplier_rate_id = (int) $input['rate_id'];

        if($rate_term->save()){
            return response()->json(['msg'=>"term added"], 200,array(),JSON_PRETTY_PRINT);
        }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }


    /**
     * Update Supplier Rate terms
     */
    public function updateSupplierRateTerm()
    {
        try{
            $input = Request::all();
            $rate_term = SupplierRateTerms::findOrFail($input['id']);
            $rate_term->terms = $input['terms'];

            if($rate_term->save()){
                return response()->json(['msg'=>"term updated"], 200,array(),JSON_PRETTY_PRINT);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    /**
     * Delete rate term
     */
    public function deleteSupplierRateTerm($term_id)
    {
        $deleted = SupplierRateTerms::destroy($term_id);
        if($deleted){
            return response()->json(['msg'=>"term deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Find vendors
     */
    public function findVendors(){
        $input = Request::all();
        //query builder
        $qb = SupplierRate::with('supplier.county','currency','terms', 'service');

        $response;
        $response_dt;

        // Services
        $service_id_count;
        if(array_key_exists('services', $input) && !empty($input['services'])){
            $service_list = $input['services'];
            $service_ids = [];
            $county_ids = [];
            foreach($service_list as $service){
                $service_ids[] = $service['id'];
                if(!empty($service['county_id'])) $county_ids[] = $service['county_id'];
            }
            $service_id_count = count($service_ids);
            if(!empty($county_ids)) $qb->where('suppliers.county_id', $county_ids[0]);
            $qb = $qb->whereIn('supplier_rates.service_id', $service_ids);            
        }

        $response = $qb->get();
        $response = json_decode(json_encode($response), true);
        
        $final_response = [];
        $service_list = $input['services'];
        $items = [];
        
        $prev_supplier_id = 0;
        $arrayKeys = array_keys($response);
        $lastArrayKey = array_pop($arrayKeys);
        foreach($response as $key => $value) {
            if($response[$key]['supplier_id'] == $prev_supplier_id || $prev_supplier_id == 0){
                $qty = 1;
                $no_of_days = 1;
                foreach($service_list as $sl){
                    if($sl['id'] == $response[$key]['service_id']){
                        $qty = $sl['qty'];
                        $no_of_days = $sl['no_of_days'];
                    }
                }
                $subtotal = 0;
                $subtotal = $response[$key]['rate'] * $qty;
                if(!empty($no_of_days)) $subtotal *= $no_of_days;
                $items[] = [
                    'service'=>$response[$key]['service'], 
                    'service_id'=>$response[$key]['service_id'], 
                    'rate'=>$response[$key]['rate'],
                    'rate_id'=>$response[$key]['id'],
                    'subtotal'=>$subtotal,
                    'vat'=>$response[$key]['vat'],
                    'unit'=>$response[$key]['unit'],
                    'daily_charge'=>$response[$key]['daily_charge'],
                    'qty'=>$qty,
                    'no_of_days'=>$no_of_days
                ];
            }
            elseif($response[$key]['supplier_id'] != $prev_supplier_id && $prev_supplier_id != 0){
                $final_response[] = array(
                    'supplier'=>$response[$key-1]['supplier'],
                    'currency'=>$response[$key-1]['currency'],
                    'items'=>$items,
                    'total'=>array_sum(array_column($items, 'subtotal'))
                );      
                $items = [];
                $qty = 1;
                $no_of_days = 1;
                foreach($service_list as $sl){
                    if($sl['id'] == $response[$key]['service_id']){
                        $qty = $sl['qty'];
                        $no_of_days = $sl['no_of_days'];
                    }
                }
                $subtotal = 0;
                $subtotal = $response[$key]['rate'] * $qty;
                if(!empty($no_of_days)) $subtotal *= $no_of_days;
                array_push($items, array(
                    'service'=>$response[$key]['service'], 
                    'service_id'=>$response[$key]['service_id'], 
                    'rate'=>$response[$key]['rate'],
                    'rate_id'=>$response[$key]['id'],
                    'subtotal'=>$subtotal,
                    'vat'=>$response[$key]['vat'],
                    'unit'=>$response[$key]['unit'],
                    'daily_charge'=>$response[$key]['daily_charge'],
                    'qty'=>$qty,
                    'no_of_days'=>$no_of_days
                    )
                );
            }
            if($key == $lastArrayKey) {
                //during array iteration this condition states the last element.
                $final_response[] = array(
                    'supplier'=>$response[$key]['supplier'],
                    'currency'=>$response[$key]['currency'],
                    'items'=>$items,
                    'total'=>array_sum(array_column($items, 'subtotal'))
                ); 
            }
            $prev_supplier_id = $response[$key]['supplier_id'];
        }

        $real_final_response = [];
        foreach($final_response as $key => $value){
            if(count($final_response[$key]['items']) == $service_id_count){
                $real_final_response[] = $final_response[$key];
            }
        }
        
        return response()->json($real_final_response, 200,array(),JSON_PRETTY_PRINT);
    }   
}
