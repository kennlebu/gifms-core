<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActivityModels\ActivityStatus;
use App\Models\ActivityModels\Activity;


use Exception;
use App;
use Illuminate\Support\Facades\Response;

class ActivityStatusApi extends Controller
{
    /**
     * Add activity status
     */
    public function addActivityStatus()
    {
        $form = Request::all();

        $activity_status = new ActivityStatus;

        $activity_status->status = $form['status'];
        $activity_status->next_status_id = (int) $form['next_status_id'];
        $activity_status->short_name =  $form['short_name'];
        $activity_status->display_color = $form['display_color'];
        $activity_status->approvable = $form['approvable'];

        if($activity_status->save()) {
            return Response()->json(array('msg' => 'Success: activity_status added','activity_status' => $activity_status), 200);
        }
    }
    

    /**
     * Update activity status
     */
    public function updateActivityStatus()
    {
        $form = Request::all();

        $activity_status = ActivityStatus::find($form['id']);

        $activity_status->status = $form['status'];
        $activity_status->next_status_id = (int) $form['next_status_id'];
        $activity_status->short_name =  $form['short_name'];
        $activity_status->display_color = $form['display_color'];
        $activity_status->approvable = $form['approvable'];

        if($activity_status->save()) {
            return Response()->json(array('msg' => 'Success: activity_status updated','activity_status' => $activity_status), 200);
        }
    }



    /**
     * Delete activity status
     */
    public function deleteActivityStatus($activity_status_id)
    {
        $deleted = ClaimStatus::destroy($activity_status_id);

        if($deleted){
            return response()->json(['msg'=>"activity_status deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"activity_status not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }



    /**
     * Get activity status by ID
     */
    public function getActivityStatusById($activity_status_id)
    {
        try{
            $response   = ActivityStatus::findOrFail($activity_status_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"activity_status could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }



    /**
     * Get claim statuses
     */
    public function getActivityStatuses()
    {    
        $input = Request::all();
        //query builder
        $qb = DB::table('activities_statuses');

        $qb->whereNull('activities_statuses.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;
        $user = JWTAuth::parseToken()->authenticate();
        if(array_key_exists('displayable_only',$input)){
            $qb->whereIn('id', [1,4]);
        }

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('activities_statuses.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities_statuses.status','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities_statuses.short_name','like', '\'%' . $input['searchval']. '%\'');

            });

            $sql = ActivityStatus::bind_presql($qb->toSql(),$qb->getBindings());
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
                
                $query->orWhere('activities_statuses.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities_statuses.status','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities_statuses.short_name','like', '\'%' . $input['search']['value']. '%\'');

            });

            $sql = ActivityStatus::bind_presql($qb->toSql(),$qb->getBindings());
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
                $response_dt = $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }

            $sql = ActivityStatus::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);

            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response       = ActivityStatus::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );

        }else{
            
            $qb->orderBy("order_priority", "asc");

            $sql            = ActivityStatus::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
            }

            foreach ($response as $key => $value) {
                $response[$key]['activities_count'] = Activity::where('requested_by_id',$this->current_user()->id)
                                            ->where('status_id', $value['id'] )
                                            ->count();
            }

            // Add statuses for "my activities", "program activities"
            // and "all activities".
            if(array_key_exists('allowed_only', $input)){

                //-1
                $response[]=array(
                        "id"=> -1,
                        "status"=> "My Activities",
                        "order_priority"=> 998,
                        "display_color"=> "#37A9E17A",
                        "activities_count"=> Activity::where('requested_by_id',$this->current_user()->id)->count()
                      );

                $response[]=array(
                        "id"=> -3,
                        "status"=> "Program Activities",
                        "order_priority"=> 999,
                        "display_color"=> "#49149c7a",
                        "activities_count"=> count(DB::table('activities')
                            ->rightJoin('program_teams', 'program_teams.program_id', '=', 'activities.program_id')
                            ->rightJoin('staff', 'staff.id', '=', 'program_teams.staff_id')
                            ->where('staff.id', '=', $this->current_user()->id)
                            ->where('activities.status_id', 3)
                            ->whereNotNull('activities.id')
                            ->groupBy('activities.id'))
                        );

                if ($user->hasRole([
                    'super-admin',
                    'admin',
                    'director',
                    'associate-director',
                    'financial-controller',
                    'accountant', 
                    'assistant-accountant'])){
                $response[]=array(
                        "id"=> -2,
                        "status"=> "All Activities",
                        "order_priority"=> 1000,
                        "display_color"=> "#092D50",
                        "activities_count"=> Activity::count()
                        );
                    }
                
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function append_relationships_objects($data = array()){
        foreach ($data as $key => $value) {
            $activity_statuses = ActivityStatus::find($data[$key]['id']);
            $data[$key]['next_status'] = $activity_statuses->next_status;
        }

        return $data;
    }
}
