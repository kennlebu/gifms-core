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
use App\Models\SuppliesModels\SupplierService;
use Illuminate\Support\Facades\DB;

class SupplierServiceApi extends Controller
{   

    public function addSupplierService()
    {
        try{
        $input = Request::all();
        $service = new SupplierService;
        $service->service_name = $input['service_name'];
        $service->supply_category_id = (int) $input['supply_category_id'];

        if($service->save()){
            return response()->json(['msg'=>"service added"], 200,array(),JSON_PRETTY_PRINT);
        }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function updateSupplierService()
    {
        try{
            $input = Request::all();
            $service =  SupplierService::findOrFail($input['id']);
            $service->service_name = $input['service_name'];
            $service->supply_category_id = (int) $input['supply_category_id'];
    
            if($service->save()){
                return response()->json(['msg'=>"service updated"], 200,array(),JSON_PRETTY_PRINT);
            }
            }
            catch(\Exception $e){
                return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
            }
    }


    public function deleteSupplierService($supplier_service_id)
    {
        $deleted = SupplierService::destroy($supplier_service_id);

        if($deleted){
            return response()->json(['msg'=>"supplier service deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"supplier service not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }


    public function getSupplierServiceById($supplier_service_id)
    {
        try{
            $response = SupplierService::findOrFail($supplier_service_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(\Exception $e){

            $response =  ["error"=>"supplier service could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


    public function supplierServicesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('supplier_services');

        $qb->whereNull('supplier_services.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supplier_services.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('supplier_services.service_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = SupplierService::bind_presql($qb->toSql(),$qb->getBindings());
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

        // Supply category
        if(array_key_exists('category', $input)){
            $qb->where('supply_category_id', $input['limit']);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supplier_services.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('supplier_services.service_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = SupplierService::bind_presql($qb->toSql(),$qb->getBindings());
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
                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb->limit($input['length']);
            }

            $sql = SupplierService::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);

            $response       = SupplierService::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $sql            = SupplierService::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {
            $supplier_service = SupplierService::find($data[$key]['id']);

            if($data[$key]["supply_category"]==null){
                $data[$key]["supply_category"] = array("supply_category_name"=>"N/A");
            }
            else{
                $data[$key]['supply_category'] = $suppliers->supply_category;
            }
        }
        return $data;
    }
}