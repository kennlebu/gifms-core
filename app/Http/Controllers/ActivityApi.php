<?php
namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\ActivityModels\Activity;
use App\Models\ActivityModels\ActivityObjective;
use App\Models\ApprovalsModels\Approval;
use App\Models\ProgramModels\ProgramStaff;
use App\Models\ProgramModels\Program;
use App\Models\AllocationModels\Allocation;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\LPOModels\Lpo;
use App\Models\ReportModels\ReportingObjective;
use App\Mail\NotifyActivityCreation;

use Exception;

class ActivityApi extends Controller
{
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
        $activity->start_date = date('Y-m-d', strtotime($form['start_date']));
        if(!empty($form['end_date'])){
            $activity->end_date = date('Y-m-d', strtotime($form['end_date']));
        }
        $activity->status_id = 1;

        if($activity->save()) {
            foreach($form['objectives'] as $objective){
                $obj = new ActivityObjective;
                $obj->activity_id = $activity->id;
                $obj->objective_id = $objective['id'];
                $obj->disableLogging();
                $obj->save();

                $r_obj = ReportingObjective::findOrFail($obj->objective_id);
                $program = Program::find($r_obj->program_id);                               // Save the PM and Program of the Project
                $activity->program_id = $program->id;                                       // that has been selected. It's redundant
                $activity->program_manager_id = $program->managers->program_manager_id;     // but much easier to deal with and the 
                $activity->disableLogging();                                                // overhead isn't significant. Laziness 101.
                $activity->save();
            }
    
            $act = Activity::with('program','program_manager')->find($activity->id);
            Mail::queue(new NotifyActivityCreation($act));                                  //Notify program staff and PM
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
        $activity->objective_id = $form['objective_id'];
        $activity->start_date = date('Y-m-d', strtotime($form['start_date']));
        if(!empty($form['end_date']))
            $activity->end_date = date('Y-m-d', strtotime($form['end_date']));

        $db_objectives =  ActivityObjective::where('activity_id', $form['id'])->pluck('objective_id')->toArray();
        $form_obj_ids = [];
        foreach($form['objectives'] as $obj){
            array_push($form_obj_ids, $obj['id']);
            if(!in_array($obj['id'], $db_objectives)){
                $n_obj = new ActivityObjective;
                $n_obj->activity_id = $activity->id;
                $n_obj->objective_id = $obj['id'];
                $n_obj->disableLogging();
                $n_obj->save();
            }
        }
        foreach($db_objectives as $obj_id){
            if(!in_array($obj_id, $form_obj_ids)){
                $delete = ActivityObjective::where('objective_id', $obj_id)->where('activity_id', $form['id'])->delete();
            }   

            $r_obj = ReportingObjective::findOrFail($obj_id);
            $program = Program::find($r_obj->program_id);
            $activity->program_id = $program->id;
            $activity->program_manager_id = $program->managers->program_manager_id;
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
            $response = Activity::with('requested_by','objectives.objective','objective.program.managers.program_manager','logs.causer', 'program_manager')->findOrFail($activity_id);           
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
        $activities = Activity::query();
        if(!array_key_exists('lean', $input)){
            $activities = Activity::with('requested_by','program','program_manager','status','objectives');
        }

        $current_user = $this->current_user();

        $response;
        $response_dt;

        $total_records = $activities->count();
        $records_filtered = 0;

        //if status is set
        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $activities = $activities->where('status_id', $input['status'])
                                        ->where('requested_by_id',$current_user->id);
            }elseif ($status_==-1) {
                $activities = $activities->where('requested_by_id',$current_user->id);
            }elseif ($status_==-2) {
                
            }elseif ($status_==-3) {
                $program_ids = ProgramStaff::select('program_id')->where('staff_id', $current_user->id)->get();
                $objective_ids = ReportingObjective::select('id')->whereIn('program_id', $program_ids)->get();
                $activities = $activities->whereIn('objective_id', $objective_ids)
                                        ->whereNotNull('id')
                                        ->groupBy('id');
            }
        }
        
        //my program activities
        if (array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true"||(!$current_user->hasRole(['accountant','assistant-accountant','financial-controller','admin-manager','financial-reviewer']))) {

            if($current_user->hasRole('program-manager')){
                $activities = $activities->where('program_manager_id', $current_user->id);
            }
            else{
                $staff_programs = ProgramStaff::where('staff_id', $current_user->id)->pluck('program_id')->toArray();
                $activities = $activities->whereIn('program_id', $staff_programs);
            }
        }

        //my_pm_assigned
        if(array_key_exists('my_pm_assigned', $input)&& $input['my_pm_assigned'] = "true"){
            $activities = $activities->where('program_manager_id', $current_user->id);
        }

        // my approvables
        if(array_key_exists('my_approvables', $input)){
            if($current_user->hasRole('program-manager')){               
                $activities = $activities->where('activities.status_id',2)
                                        ->where('activities.program_manager_id', $current_user->id);
            }
            else{ 
                $activities = $activities->where('id',0);
            }
        }

        //objective_id
         if(array_key_exists('objective_id', $input)){
            $objective_id = (int) $input['objective_id'];

            if($objective_id==0){
            }else if($objective_id==1){
                $activities = $activities->where('objective_id',$objective_id);
            }
        }

        // For funds requests (More than a month from ending)
        if(array_key_exists('funds_requests', $input)){
            if( date('d') == 31 || (date('m') == 1 && date('d') > 28)){
                $date = strtotime('last day of next month');
            } else {
                $date = strtotime('+1 months');
            }
            $date = date('Y-m-d', $date);
            $activities = $activities->whereRaw('CAST(end_date as datetime) >= '.$date.'');
        }

        //project_id
         if(array_key_exists('project_id', $input)){
            $project_id = (int) $input['project_id'];

            if($project_id==0){
            }else if($project_id==1){
                $activities = $activities->where('project_id',$project_id);
            }
        }

        //searching
        if(array_key_exists('searchval', $input)){
            $activities = $activities->where(function ($query) use ($input) {                
                $query->orWhere('activities.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities.title','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('activities.description','like', '\'%' . $input['searchval']. '%\'');
            });

            $records_filtered = (int) $activities->count();
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction = "asc";
            $order_column_name = 'activities.'.$input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $activities = $activities->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $activities = $activities->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            //searching
            $activities = $activities->where(function ($query) use ($input) {                
                $query->orWhere('activities.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities.title','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('activities.description','like', '\'%' . $input['search']['value']. '%\'');
            });

            $records_filtered = (int) $activities->count();

            //ordering
            $order_column_id = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $activities = $activities->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $activities = $activities->limit($input['length'])->offset($input['start']);
            }else{
                $activities = $activities->limit($input['length']);
            }

            $activities = $activities->get();
            $response = Activity::arr_to_dt_response( 
                $activities, $input['draw'],
                $total_records,
                $records_filtered
                );

        }else{
            $response = $activities->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {
            $activity = Activity::with('objective.program.managers', 'logs','objectives.objective')->find($data[$key]['id']);
            
            if(!empty($activity->objective_id) && $activity->objective_id!=0){
                $data[$key]['objective'] = $activity->objective;
            }
            else $data[$key]['objective'] = array("ojective"=>"N/A", "program"=>array("managers"=>['program_manager'=>['name'=>'N/A']]));

            if(!empty($activity->program_id) && $activity->program_id!=0){
                $data[$key]['program'] = $activity->program;
            }
            else $data[$key]['program'] = array("program_desc"=>"N/A", "program_name"=>"N/A");

            if(!empty($activity->status_id) && $activity->status_id!=0){
                $data[$key]['status'] = $activity->status;
            }
            else $data[$key]['status'] = array("status"=>"N/A");

            if(!empty($activity->requested_by_id) && $activity->requested_by_id!=0){
                $data[$key]['requested_by'] = $activity->requested_by;
            }
            else $data[$key]['requested_by'] = array("name"=>"N/A");

            if(!empty($activity->program_manager_id) && $activity->program_manager_id!=0){
                $data[$key]['program_manager'] = $activity->program_manager;
            }
            else $data[$key]['program_manager'] = array("name"=>"N/A", "full_name"=>"N/A");

            if(!empty($activity->objectives)){
                $data[$key]['objectives'] = $activity->objectives;
            }
            else $data[$key]['program_manager'] = array("name"=>"N/A", "full_name"=>"N/A");

            $data[$key]['has_transactions'] = $activity->has_transactions;
        }

        return $data;
    }


    /**
     * Approve activity
     */
    public function approveActivity($activity_id, $several=null)
    {
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
                $approval-disableLogging();

                $approval->save();

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
                return Response()->json(array('msg' => 'Success: Activity submitted','activity' => $activity), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Activity could not be found", "msg"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }



    /**
     * Get activity transactions
     */
    public function getActivityTransactions(){

        try{
            $input = Request::all();
            $activity_id = $input['activity_id'];
            $transactions = [];
            
            $allocations = Allocation::where('activity_id', $activity_id)->get();
            foreach($allocations as $alloc){
                $alloc['tran_type'] = 'allocation';
                array_push($transactions, $alloc);
            }

            $invoices = Invoice::where('program_activity_id', $activity_id)->get();
            foreach($invoices as $inv){
                $inv['tran_type'] = 'invoice';
                array_push($transactions, $inv);
            }

            $mobile_payments = MobilePayment::where('program_activity_id', $activity_id)->get();
            foreach($mobile_payments as $mp){
                $mp['tran_type'] = 'mobile_payment';
                array_push($transactions, $mp);
            }

            $lpos = Lpo::where('program_activity_id', $activity_id)->get();
            foreach($lpos as $lpo){
                $lpo['tran_type'] = 'lpo';
                array_push($transactions, $lpo);
            }


            $sort = $transactions;
            foreach ($transactions as $key => $part) {
                $sort[$key] = strtotime($part['created_at']);
            }
            array_multisort($sort, SORT_DESC, $transactions);

            return Response()->json($transactions, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"Activity could not be found", "msg"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
}
