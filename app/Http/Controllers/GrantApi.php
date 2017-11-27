<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator grant.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\GrantModels\Grant;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class GrantApi extends Controller
{
    






















    /**
     * Constructor
     */
    public function __construct()
    {
    }

    






















    /**
     * Operation addGrant
     *
     * Add a new grant.
     *
     *
     * @return Http response
     */
    public function addGrant()
    {
        $form = Request::only(
            'grant_name',
            'grant_code',
            'grant_amount',
            'currency_id',
            'donor_id',
            'start_date',
            'end_date'
            );

        $grant = new Grant;

            $grant->grant_name                   =         $form['grant_name'];
            $grant->grant_code                   =         $form['grant_code'];
            $grant->grant_amount                 =         $form['grant_amount'];
            $grant->currency_id                  =         $form['currency_id'];
            $grant->donor_id                     =         $form['donor_id'];
            $grant->start_date                   =         $form['start_date'];
            $grant->end_date                     =         $form['end_date'];

        if($grant->save()) {

            return Response()->json(array('msg' => 'Success: grant added','grant' => $grant), 200);
        }
    }
    






















    /**
     * Operation updateGrant
     *
     * Update an existing grant.
     *
     *
     * @return Http response
     */
    public function updateGrant()
    {
        $form = Request::only(
            'id',
            'grant_name',
            'grant_code',
            'grant_amount',
            'currency_id',
            'donor_id',
            'start_date',
            'end_date'
            );

        $grant = Grant::find($form['id']);

            $grant->grant_name                   =         $form['grant_name'];
            $grant->grant_code                   =         $form['grant_code'];
            $grant->grant_amount                 =         $form['grant_amount'];
            $grant->currency_id                  =         $form['currency_id'];
            $grant->donor_id                     =         $form['donor_id'];
            $grant->start_date                   =         $form['start_date'];
            $grant->end_date                     =         $form['end_date'];

        if($grant->save()) {

            return Response()->json(array('msg' => 'Success: grant updated','grant' => $grant), 200);
        }
    }
    






















    /**
     * Operation deleteGrant
     *
     * Deletes an grant.
     *
     * @param int $grant_id grant id to delete (required)
     *
     * @return Http response
     */
    public function deleteGrant($grant_id)
    {
        $input = Request::all();


        $deleted = Grant::destroy($grant_id);

        if($deleted){
            return response()->json(['msg'=>"grant deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"grant not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation getGrantById
     *
     * Find grant by ID.
     *
     * @param int $grant_id ID of grant to return object (required)
     *
     * @return Http response
     */
    public function getGrantById($grant_id)
    {
        $input = Request::all();

        try{

            $response   = Grant::findOrFail($grant_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"grant could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation grantsGet
     *
     * Grants List.
     *
     *
     * @return Http response
     */
    public function grantsGet()
    {
       
        $input = Request::all();
        //query builder
        $qb = DB::table('grants');

        $qb->whereNull('grants.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('grants.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('grants.grant_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('grants.grant_code','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Grant::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


        }

        //migrated
        if(array_key_exists('migrated', $input)){

            $mig = (int) $input['migrated'];

            if($mig==0){
                $qb->whereNull('migration_id');
            }else if($mig==1){
                $qb->whereNotNull('migration_id');
            }


        }



        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('grants.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('grants.grant_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('grants.grant_code','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Grant::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Grant::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Grant::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Grant::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $grants = Grant::find($data[$key]['id']);

            $data[$key]['status']                = $grants->status;
            $data[$key]['currency']              = $grants->currency;
            $data[$key]['donor']                 = $grants->donor;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["status"]==null){
                $data[$key]["status"] = array("grant_status"=>"N/A");
            }
            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
            if($data[$key]["donor"]==null){
                $data[$key]["donor"] = array("donor_name"=>"N/A");
            }


        }

        return $data;


    }
}