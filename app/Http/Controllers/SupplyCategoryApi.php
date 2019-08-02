<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator supply_category.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\SuppliesModels\SupplyCategory;
use Exception;

class SupplyCategoryApi extends Controller
{
    /**
     * Operation addSupplyCategory
     *
     * Add a new supply_category.
     *
     *
     * @return Http response
     */
    public function addSupplyCategory()
    {
        $form = Request::only(
            'supply_category_name'
            );

        $supply_category = new SupplyCategory;
        $supply_category->supply_category_name                   =         $form['supply_category_name'];

        if($supply_category->save()) {
            return Response()->json(array('msg' => 'Success: supply_category added','supply_category' => $supply_category), 200);
        }
    }
    




















    /**
     * Operation updateSupplyCategory
     *
     * Update an existing supply_category.
     *
     *
     * @return Http response
     */
    public function updateSupplyCategory()
    {
        $form = Request::only(
            'id',
            'supply_category_name'
            );

        $supply_category = SupplyCategory::find($form['id']);
        $supply_category->supply_category_name                   =         $form['supply_category_name'];

        if($supply_category->save()) {
            return Response()->json(array('msg' => 'Success: supply_category updated','supply_category' => $supply_category), 200);
        }
    }
    




















    /**
     * Operation deleteSupplyCategory
     *
     * Deletes an supply_category.
     *
     * @param int $supply_category_id supply_category id to delete (required)
     *
     * @return Http response
     */
    public function deleteSupplyCategory($supply_category_id)
    {
        $deleted = SupplyCategory::destroy($supply_category_id);
        if($deleted){
            return response()->json(['msg'=>"supply_category deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getSupplyCategoryById
     *
     * Find supply_category by ID.
     *
     * @param int $supply_category_id ID of supply_category to return object (required)
     *
     * @return Http response
     */
    public function getSupplyCategoryById($supply_category_id)
    {
        try{
            $response   = SupplyCategory::findOrFail($supply_category_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation supplyCategoriesGet
     *
     * supply_categories List.
     *
     *
     * @return Http response
     */
    public function supplyCategoriesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('supply_categories');

        $qb->whereNull('supply_categories.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supply_categories.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('supply_categories.supply_category_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = SupplyCategory::bind_presql($qb->toSql(),$qb->getBindings());
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
        }else{
            $qb->orderBy("supply_category_name", "asc");
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('supply_categories.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('supply_categories.supply_category_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = SupplyCategory::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = SupplyCategory::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = SupplyCategory::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $sql            = SupplyCategory::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
