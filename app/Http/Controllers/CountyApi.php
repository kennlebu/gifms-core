<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LookupModels\County;
use Exception;

class countyApi extends Controller
{
    /**
     * Operation addCounty
     *
     * Add a new county.
     *
     *
     * @return Http response
     */
    public function addCounty()
    {
        $form = Request::only(
            'county_name'
            );

        $county = new County;
        $county->county_name = $form['county_name'];

        if($county->save()) {
            return Response()->json(array('msg' => 'Success: county added','county' => $county), 200);
        }
    }

    /**
     * Operation updateCounty
     *
     * Update an existing county.
     *
     *
     * @return Http response
     */
    public function updateCounty()
    {
        $form = Request::only(
            'id',
            'county_name'
            );

        $county = County::find($form['id']);
        $county->county_name = $form['county_name'];

        if($county->save()) {
            return Response()->json(array('msg' => 'Success: county updated','county' => $county), 200);
        }
    }

    /**
     * Operation deleteCounty
     *
     * Deletes an county.
     *
     * @param int $county_id county id to delete (required)
     *
     * @return Http response
     */
    public function deleteCounty($county_id)
    {
        $deleted = County::destroy($county_id);
        if($deleted){
            return response()->json(['msg'=>"county deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Operation getCountyById
     *
     * Find county by ID.
     *
     * @param int $county_id ID of county to return object (required)
     *
     * @return Http response
     */
    public function getCountyById($county_id)
    {
        try{
            $response = County::findOrFail($county_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Operation countiesGet
     *
     * Counties List.
     *
     *
     * @return Http response
     */
    public function countiesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('counties');
        $qb->whereNull('counties.deleted_at');

        $response;
        $response_dt;
        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('counties.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('counties.county_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = County::bind_presql($qb->toSql(),$qb->getBindings());
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
                $query->orWhere('counties.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('counties.county_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = County::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = County::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = County::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }else{
            $sql            = County::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}