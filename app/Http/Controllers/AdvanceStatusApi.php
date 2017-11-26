<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator advance_status.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AdvancesModels\AdvanceStatus;
use App\Models\AdvancesModels\Advance;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class AdvanceStatusApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addAdvanceStatus
     *
     * Add a new advance_status.
     *
     *
     * @return Http response
     */
    public function addAdvanceStatus()
    {
        $form = Request::only(
            'advance_status',
            'next_status_id',
            'status_security_level',
            'order_priority',
            'display_color',
            'default_status',
            'approval_level_id'
            );

        $advance_status = new AdvanceStatus;

            $advance_status->advance_status                 =         $form['advance_status'];
            $advance_status->next_status_id                 =  (int)  $form['next_status_id'];
            $advance_status->status_security_level          =         $form['status_security_level'];
            $advance_status->order_priority                 =         $form['order_priority'];
            $advance_status->display_color                  =         $form['display_color'];
            $advance_status->default_status                 =         $form['default_status'];
            $advance_status->approval_level_id              =  (int)  $form['approval_level_id'];

        if($advance_status->save()) {

            return Response()->json(array('msg' => 'Success: advance_status added','advance_status' => $advance_status), 200);
        }
    }
    




















    /**
     * Operation updateAdvanceStatus
     *
     * Update an existing advance_status.
     *
     *
     * @return Http response
     */
    public function updateAdvanceStatus()
    {
        $form = Request::only(
            'id',
            'next_status_id',
            'status_security_level',
            'order_priority',
            'display_color',
            'default_status',
            'approval_level_id'
            );

        $advance_status = AdvanceStatus::find($form['id']);


            $advance_status->advance_status                 =         $form['advance_status'];
            $advance_status->next_status_id                 =  (int)  $form['next_status_id'];
            $advance_status->status_security_level          =         $form['status_security_level'];
            $advance_status->order_priority                 =         $form['order_priority'];
            $advance_status->display_color                  =         $form['display_color'];
            $advance_status->default_status                 =         $form['default_status'];
            $advance_status->approval_level_id              =  (int)  $form['approval_level_id'];

        if($advance_status->save()) {

            return Response()->json(array('msg' => 'Success: advance_status updated','advance_status' => $advance_status), 200);
        }
    }
    




















    /**
     * Operation deleteAdvanceStatus
     *
     * Deletes an advance_status.
     *
     * @param int $advance_status_id advance_status id to delete (required)
     *
     * @return Http response
     */
    public function deleteAdvanceStatus($advance_status_id)
    {
        $input = Request::all();


        $deleted = AdvanceStatus::destroy($advance_status_id);

        if($deleted){
            return response()->json(['msg'=>"advance_status deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"advance_status not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getAdvanceStatusById
     *
     * Find advance_status by ID.
     *
     * @param int $advance_status_id ID of advance_status to return object (required)
     *
     * @return Http response
     */
    public function getAdvanceStatusById($advance_status_id)
    {
        $input = Request::all();

        try{

            $response   = AdvanceStatus::findOrFail($advance_status_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"advance_status could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation getAdvanceStatuses
     *
     * advance_statuses List.
     *
     *
     * @return Http response
     */
    public function getAdvanceStatuses()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('advance_statuses');

        $qb->whereNull('advance_statuses.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;
        $user = JWTAuth::parseToken()->authenticate();




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('advance_statuses.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('advance_statuses.advance_status','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('advance_statuses.display_color','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = AdvanceStatus::bind_presql($qb->toSql(),$qb->getBindings());
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
                
                $query->orWhere('advance_statuses.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('advance_statuses.advance_status','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('advance_statuses.display_color','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = AdvanceStatus::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = AdvanceStatus::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = AdvanceStatus::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = AdvanceStatus::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);

            foreach ($response as $key => $value) {
                $response[$key]['advances_count'] = Advance::where('requested_by_id',$this->current_user()->id)
                                            ->where('status_id', $value['id'] )
                                            ->count();
            }

            //add -1 and -2 statuses

            if(array_key_exists('allowed_only', $input)){

                //-1
                $response[]=array(
                        "id"=> -1,
                        "advance_status"=> "My Advances",
                        "order_priority"=> 998,
                        "display_color"=> "#37A9E17A",
                        "advances_count"=> Advance::where('requested_by_id',$this->current_user()->id)->count()
                      );


                if ($user->hasRole('program-manager')){
                    $response[]=array(
                            "id"=> -3,
                            "advance_status"=> "My PM-Assigned Advances",
                            "order_priority"=> 999,
                            "display_color"=> "#49149c7a",
                            "advances_count"=> Advance::where('project_manager_id',$this->current_user()->id)->count()
                          );
                }

                if ($user->can('READ_ADVANCE_-2')){

                    //-1
                    $response[]=array(
                            "id"=> -2,
                            "advance_status"=> "All Advances",
                            "order_priority"=> 1000,
                            "display_color"=> "#092D50",
                            "advances_count"=> Advance::count()
                          );
                }


            }

        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $advance_statuses = AdvanceStatus::find($data[$key]['id']);

            $data[$key]['next_status']                = $advance_statuses->next_status;
            $data[$key]['approval_level']             = $advance_statuses->approval_level;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["next_status"]==null){
                $data[$key]["next_status"] = array("advance_status"=>"N/A");
            }
            if($data[$key]["approval_level"]==null){
                $data[$key]["approval_level"] = array("approval_level"=>"N/A");
            }


        }

        return $data;


    }
}
