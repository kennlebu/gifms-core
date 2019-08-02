<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator donor.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\GrantModels\Donor;
use Exception;

class DonorApi extends Controller
{
    /**
     * Operation addDonor
     *
     * Add a new donor.
     *
     *
     * @return Http response
     */
    public function addDonor()
    {
        $form = Request::only(
            'donor_name',
            'donor_code'
            );

        $donor = new Donor;
        $donor->donor_name                   =         $form['donor_name'];
        $donor->donor_code                   =         $form['donor_code'];

        if($donor->save()) {
            return Response()->json(array('msg' => 'Success: donor added','donor' => $donor), 200);
        }
    }

    /**
     * Operation updateDonor
     * Update an existing donor.
     * @return Http response
     */
    public function updateDonor()
    {
        $form = Request::only(
            'id',
            'donor_name',
            'donor_code'
            );

        $donor = Donor::find($form['id']);
        $donor->donor_name                   =         $form['donor_name'];
        $donor->donor_code                   =         $form['donor_code'];

        if($donor->save()) {
            return Response()->json(array('msg' => 'Success: donor updated','donor' => $donor), 200);
        }
    }
    






















    /**
     * Operation deleteDonor
     *
     * Deletes an donor.
     *
     * @param int $donor_id donor id to delete (required)
     *
     * @return Http response
     */
    public function deleteDonor($donor_id)
    {
        $deleted = Donor::destroy($donor_id);

        if($deleted){
            return response()->json(['msg'=>"donor deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"donor not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation getDonorById
     *
     * Find donor by ID.
     *
     * @param int $donor_id ID of donor to return object (required)
     *
     * @return Http response
     */
    public function getDonorById($donor_id)
    {
        try{
            $response   = Donor::findOrFail($donor_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"donor could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation donorsGet
     * Donors List.
     * @return Http response
     */
    public function donorsGet()
    {       
        $input = Request::all();
        //query builder
        $qb = DB::table('donors');

        $qb->whereNull('donors.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('donors.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('donors.donor_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('donors.donor_code','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = Donor::bind_presql($qb->toSql(),$qb->getBindings());
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
                $query->orWhere('donors.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('donors.donor_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('donors.donor_code','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = Donor::bind_presql($qb->toSql(),$qb->getBindings());
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
            
            $sql = Donor::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Donor::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }else{
            $sql            = Donor::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }




















    public function append_relationships_objects($data = array()){
        foreach ($data as $key => $value) {
            $donors = Donor::find($data[$key]['id']);
            $data[$key]['grants'] = $donors->grants;
        }

        return $data;
    }
}
