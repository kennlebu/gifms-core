<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator approval_level.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ApprovalsModels\ApprovalLevel;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class ApprovalLevelApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addApprovalLevel
     *
     * Add a new approval_level.
     *
     *
     * @return Http response
     */
    public function addApprovalLevel()
    {
        $form = Request::only(
            'approval_level_name'
            );

        $approval_level = new ApprovalLevel;

            $approval_level->approval_level                   =         $form['approval_level'];

        if($approval_level->save()) {

            return Response()->json(array('msg' => 'Success: approval_level added','approval_level' => $approval_level), 200);
        }
    }
    




















    /**
     * Operation updateApprovalLevel
     *
     * Update an existing approval_level.
     *
     *
     * @return Http response
     */
    public function updateApprovalLevel()
    {
        $form = Request::only(
            'id',
            'approval_level'
            );

        $approval_level = ApprovalLevel::find($form['id']);

            $approval_level->approval_level                   =         $form['approval_level'];

        if($approval_level->save()) {

            return Response()->json(array('msg' => 'Success: approval_level updated','approval_level' => $approval_level), 200);
        }
    }
    




















    /**
     * Operation deleteApprovalLevel
     *
     * Deletes an approval_level.
     *
     * @param int $approval_level_id approval_level id to delete (required)
     *
     * @return Http response
     */
    public function deleteApprovalLevel($approval_level_id)
    {
        $input = Request::all();


        $deleted = ApprovalLevel::destroy($approval_level_id);

        if($deleted){
            return response()->json(['msg'=>"approval_level deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"approval_level not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getApprovalLevelById
     *
     * Find approval_level by ID.
     *
     * @param int $approval_level_id ID of approval_level to return object (required)
     *
     * @return Http response
     */
    public function getApprovalLevelById($approval_level_id)
    {
        $input = Request::all();

        try{

            $response   = ApprovalLevel::findOrFail($approval_level_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"approval_level could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation approvalLevelsGet
     *
     * approval_levels List.
     *
     *
     * @return Http response
     */
    public function approvalLevelsGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('approval_levels');

        $qb->whereNull('approval_levels.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('approval_levels.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('approval_levels.approval_level','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = ApprovalLevel::bind_presql($qb->toSql(),$qb->getBindings());
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
                
                $query->orWhere('approval_levels.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('approval_levels.approval_level','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = ApprovalLevel::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = ApprovalLevel::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = ApprovalLevel::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = ApprovalLevel::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $approval_levels = ApprovalLevel::find($data[$key]['id']);

            $data[$key]['managers']                = $approval_levels->managers;
            $data[$key]['staffs']                  = $approval_levels->staffs;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            // if($data[$key]["account"]==null){
            //     $data[$key]["account"] = array("account_name"=>"N/A");
            // }


        }

        return $data;


    }
}