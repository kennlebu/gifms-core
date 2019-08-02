<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator city.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LookupModels\City;
use Exception;

class CityApi extends Controller
{
    /**
     * Operation addCity
     *
     * Add a new city.
     *
     *
     * @return Http response
     */
    public function addCity()
    {
        $form = Request::only(
            'city_name'
            );

        $city = new City;
        $city->city_name = $form['city_name'];

        if($city->save()) {
            return Response()->json(array('msg' => 'Success: city added','city' => $city), 200);
        }
    }
    




















    /**
     * Operation updateCity
     *
     * Update an existing city.
     *
     *
     * @return Http response
     */
    public function updateCity()
    {
        $form = Request::only(
            'id',
            'city_name'
            );

        $city = City::find($form['id']);
        $city->city_name                   =         $form['city_name'];

        if($city->save()) {
            return Response()->json(array('msg' => 'Success: city updated','city' => $city), 200);
        }
    }
    




















    /**
     * Operation deleteCity
     *
     * Deletes an city.
     *
     * @param int $city_id city id to delete (required)
     *
     * @return Http response
     */
    public function deleteCity($city_id)
    {
        $deleted = City::destroy($city_id);
        if($deleted){
            return response()->json(['msg'=>"city deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getCityById
     *
     * Find city by ID.
     *
     * @param int $city_id ID of city to return object (required)
     *
     * @return Http response
     */
    public function getCityById($city_id)
    {
        try{
            $response   = City::findOrFail($city_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation citiesGet
     *
     * cities List.
     *
     *
     * @return Http response
     */
    public function citiesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('cities');

        $qb->whereNull('cities.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('cities.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('cities.city_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = City::bind_presql($qb->toSql(),$qb->getBindings());
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

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('cities.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('cities.city_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = City::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = City::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = City::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }else{
            $sql            = City::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
