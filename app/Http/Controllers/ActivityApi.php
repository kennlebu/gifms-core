<?php
namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActivityModels\Activity;
use App\Models\ActivityModels\ActivityStatus;
use App\Models\ProgramModels\ProgramManager;
use App\Models\ApprovalsModels\Approval;
use App\Models\ProgramModels\ProgramStaff;
use App\Models\ProgramModels\Program;
use App\Models\ProjectsModels\Project;

use Exception;
use App;
use Illuminate\Support\Facades\Response;

class ActivityApi extends Controller
{
    private $default_status = '';
    private $approvable_statuses = [];
    
    public function __construct()
    {
        // $status = ActivityStatus::where('default_status','1')->first();
        // $this->default_status = $status->id;
        $this->default_status = 1; // Logged, pending submission
        $this->approvable_statuses = ActivityStatus::where('approvable','1')->get();
    }


    /**
     * Add activity
     */
    public function addActivity()
    {
        $form = Request::all();

        $activity = new Activity;

        $activity->requested_by_id = $form['requested_by_id'];
        $activity->title = $form['title'];
        $activity->description = $form['description'];
        $activity->project_id = $form['project_id'];
        $activity->start_date = date('Y-m-d', strtotime($form['start_date']));
        if(!empty($form['end_date'])){
            $activity->end_date = date('Y-m-d', strtotime($form['end_date']));
        }
        $activity->status_id = $this->default_status;

        $proj = Project::findOrFail($activity->project_id);
        $program = Program::find($proj->program_id);                                // Save the PM and Program of the Project
        $activity->program_id = $program->id;                                       // that has been selected. It's redundant
        $pm = ProgramManager::where('program_id', $program->id)->first();           // but much easier to deal with and the 
        $activity->program_manager_id = $program->program_manager_id;               // overhead isn't significant. Laziness 101.

        if($activity->save()) {
            return Response()->json(array('msg' => 'Success: activity added','activity' => $activity), 200);
        }
    }
    
    
    /**
     * Update activity
     */
    public function updateActivity()
    {
        $form = Request::all();

        $activity = Activity::find($form['id']);

        $activity->requested_by_id = $form['requested_by_id'];
        $activity->title = $form['title'];
        $activity->description = $form['description'];
        $activity->project_id = $form['project_id'];
        $activity->start_date = date('Y-m-d', strtotime($form['start_date']));
        if(!empty($form['end_date']))
            $activity->end_date = date('Y-m-d', strtotime($form['end_date']));
            
        $proj = Project::findOrFail($activity->project_id);
        $program = Program::find($proj->program_id);
        $activity->program_id = $program->id;
        $pm = ProgramManager::where('program_id', $program->id)->first();
        $activity->program_manager_id = $program->program_manager_id;
        
        if($activity->status_id==3) {               // If activity was already approved, send
            $activity->status_id = 2;               // it back for approval on editing.
        }

        if($activity->save()) {

            return Response()->json(array('msg' => 'Success: activity updated','activity' => $activity), 200);
        }
    }


    
    /**
     * Delete activity
     */
    public function deleteActivity($activity_id)
    {
        $deleted = Activity::destroy($activity_id);
        if($deleted){
            return response()->json(['msg'=>"activity deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"activity not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }

    
    /**
     * Get activity by id
     */
    public function getActivityById($activity_id)
    {
        try{
            $response = Activity::with('requested_by','program.managers.program_manager','project','status','rejected_by','logs')->findOrFail($activity_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"something went wrong", 'msg'=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    
    /**
     * Get activities
     */
    public function getActivities()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('activities');

        $qb->whereNull('activities.deleted_at');
        $current_user = JWTAuth::parseToken()->authenticate();

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //if status is set
        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $qb->where('activities.status_id', $input['status']);
                $qb->where('activities.requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb->where('activities.requested_by_id',$this->current_user()->id);
            }elseif ($status_==-2) {
                
            }elseif ($status_==-3) {
                $program_ids = ProgramStaff::select('program_id')->where('staff_id', $this->current_user()->id)->get();
                $qb->whereIn('activities.program_id', $program_ids)
                    ->where('activities.status_id', 3)
                    ->whereNotNull('activities.id')
                    ->groupBy('activities.id');
            }
        }
        
        //my program activities
        if (array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true"||(!$current_user->hasRole(['accountant','assistant-accountant','financial-controller','admin-manager']))) {

            $qb->select(DB::raw('activities.*'))
                 ->rightJoin('program_teams', 'program_teams.program_id', '=', 'activities.program_id')
                 ->rightJoin('staff', 'staff.id', '=', 'program_teams.staff_id')
                 ->where('staff.id', '=', $current_user->id)
                 ->where('activities.status_id', 3)
                 ->whereNotNull('activities.id')
                 ->groupBy('activities.id');

            if($current_user->hasRole('program-manager')){
                $qb->where('program_manager_id', $current_user->id);
            }
        }

        //my_pm_assigned
        if(array_key_exists('my_pm_assigned', $input)&& $input['my_pm_assigned'] = "true"){
            $qb->select(DB::raw('activities.*'))->where('program_manager_id', $current_user->id);
        }

        // my approvables
        if(array_key_exists('my_approvables', $input)){

            if($current_user->hasRole('program-manager')){               
                $qb->where('activities.status_id',2); 
                $qb->where('activities.program_manager_id', $current_user->id);
            }
            else{ 
                $qb->where('id',0);
            }
        }

        //program_id
         if(array_key_exists('program_id', $input)){

            $program_id = (int) $input['program_id'];

            if($program_id==0){
            }else if($program_id==1){
                $qb->where('program_id',$program_id);
            }
        }

        //project_id
         if(array_key_exists('project_id', $input)){

            $project_id = (int) $input['project_id'];

            if($project_id==0){
            }else if($project_id==1){
                $qb->where('project_id',$project_id);
            }
        }


        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('activities.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities.title','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities.description','like', '\'%' . $input['searchval']. '%\'');

            });

            $sql = Activity::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("activities.*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = 'activities.'.$input['order_by'];
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
                $query->orWhere('activities.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities.title','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities.description','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = Activity::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = 'activities.'.$input['columns'][$order_column_id]['order_by'];
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

            $sql = Activity::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);            
            $response_dt = $this->append_relationships_objects($response_dt);
            $response = Activity::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );

        }else{
            $sql            = Activity::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {
            $activity = Activity::with('program', 'project', 'program.managers', 'logs')->find($data[$key]['id']);
            
            if(!empty($activity->program_id) && $activity->program_id!=0){
                $data[$key]['program'] = $activity->program;
            }
            else $data[$key]['program'] = array("program_name"=>"N/A", array("managers"=>['program_manager'=>['name'=>'N/A']]));

            if(!empty($activity->project_id) && $activity->project_id!=0){
                $data[$key]['project'] = $activity->project;
            }
            else $data[$key]['project'] = array("project_code"=>"N/A", "project_name"=>"N/A");

            if(!empty($activity->status_id) && $activity->status_id!=0){
                $data[$key]['status'] = $activity->status;
            }
            else $data[$key]['status'] = array("status"=>"N/A");

            if(!empty($activity->requested_by_id) && $activity->requested_by_id!=0){
                $data[$key]['requested_by'] = $activity->requested_by;
            }
            else $data[$key]['requested_by'] = array("name"=>"N/A");

            // $data[$key]['logs'] = $activity->logs;
        }

        return $data;
    }


    /**
     * Approve activity
     */
    public function approveActivity($activity_id, $several=null)
    {

        $input = Request::all();

        $user = JWTAuth::parseToken()->authenticate();

        try{

            $activity   = Activity::findOrFail($activity_id);
           
            $approvable_status  = $activity->status;
            $activity->status_id = $activity->status->next_status_id;

            if($activity->save()) {

                $approval = new Approval;

                $approval->approvable_id            =   (int) $activity->id;
                $approval->approvable_type          =   "activities";
                $approval->approval_level_id        =   $approvable_status->approval_level_id;
                $approval->approver_id              =   (int) $user->id;

                $approval->save();
                
                // Mail::queue(new NotifyActivity($activity));

                if($several!=true)
                return Response()->json(array('msg' => 'Success: Activity approved','activity' => $activity), 200);
            }


        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"activity could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Approve several activities
     */
    public function approveSeveralActivities()
    {
        try {
            $form = Request::only("activities");
            $activity_ids = $form['activities'];

            foreach ($activity_ids as $key => $activity_id) {
                $this->approveActivity($activity_id, true);
            }

            return response()->json(['activities'=>$form['activities']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
            
        }
    }


    /**
     * Reject activity
     */
    public function rejectActivity($activity_id)
    {

        $form = Request::only(
            'rejection_reason'
            );

        $user = JWTAuth::parseToken()->authenticate();

        try{
            $activity   = Activity::findOrFail($advance_id);
           
            $activity->status_id = 4;    // Rejected status
            $activity->rejected_by_id = (int) $user->id;
            $activity->rejected_at = date('Y-m-d H:i:s');
            $activity->rejection_reason = $form['rejection_reason'];

            if($activity->save()) {
                // Mail::queue(new NotifyActivity($activity));

                return Response()->json(array('msg' => 'Success: activity approved','advance' => $activity), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Activity could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Submit activity for approval
     */
    public function submitActivityForApproval($activity_id)
    {
        try{
            $activity = Activity::findOrFail($activity_id);
           
            $activity->status_id = $activity->status->next_status_id;

            if($activity->save()) {
                // Mail::queue(new NotifyActivity($activity));

                return Response()->json(array('msg' => 'Success: Activity submitted','activity' => $activity), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Activity could not be found", "msg"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
}
