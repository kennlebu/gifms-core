<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator project.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use App\Models\AccountingModels\Account;
use App\Models\AdvancesModels\Advance;
use App\Models\AllocationModels\Allocation;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\ProgramModels\ProgramManager;
use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ProjectsModels\Project;
use Exception;

class ProjectApi extends Controller
{
    /**
     * Operation addProject
     *
     * Add a new project.
     *
     *
     * @return Http response
     */
    public function addProject()
    {
        $form = Request::all();

        $project = new Project;

            $project->program_id                       =  (int)  $form['program_id'];
            $project->project_code                     =         $form['project_code'];
            $project->project_name                     =         $form['project_name'];
            $project->project_desc                     =         $form['project_desc'];
            $project->country_id                       =  (int)  $form['country_id'];
            $project->grant_id                         = (int) $form['grant_id'];
            $project->status_id                        = (int) $form['status_id'] || 1;
            if(!empty($form['budget_id']))
            $project->budget_id                        = (int) $form['budget_id'];

        if($project->save()) {
            return Response()->json(array('msg' => 'Success: project added','project' => $project), 200);
        }
    }































    
    /**
     * Operation updateProject
     *
     * Update an existing project.
     *
     *
     * @return Http response
     */
    public function updateProject()
    {
        $form = Request::all();

        $project = Project::find($form['id']);
        $project->program_id                       =  (int)  $form['program_id'];
        $project->project_code                     =         $form['project_code'];
        $project->project_name                     =         $form['project_name'];
        $project->project_desc                     =         $form['project_desc'];
        $project->status_id                        =  (int)  $form['status_id'];
        $project->country_id                       =  (int)  $form['country_id'];
        $project->grant_id                         = (int) $form['grant_id'];
        if(!empty($form['budget_id']))
        $project->budget_id                        = (int) $form['budget_id'];

        if($project->save()) {
            return Response()->json(array('msg' => 'Success: project updated','project' => $project), 200);
        }
    }































    
    /**
     * Operation deleteProject
     *
     * Deletes an project.
     *
     * @param int $project_id project id to delete (required)
     *
     * @return Http response
     */
    public function deleteProject($project_id)
    {
        $deleted = Project::destroy($project_id);
        if($deleted){
            return response()->json(['msg'=>"project deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"project not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }































    
    /**
     * Operation getProjectById
     *
     * Find project by ID.
     *
     * @param int $project_id ID of project to return object (required)
     *
     * @return Http response
     */
    public function getProjectById($project_id)
    {
        $input = Request::all();

        try{
            $response = Project::with(["budget","project_manager","staffs", "grant"])->findOrFail($project_id);

            //with_chart_data
            if(array_key_exists('with_chart_data', $input)&& $input['with_chart_data'] == "true"){
                // $project = Project::find($project_id);
                $response["budget_expenditure_by_accounts_data"]    =   $response->getBudgetExpenditureByAccountsDataAttribute();
                $response["budget_expenditure_by_objectives_data"]  =   $response->getBudgetExpenditureByObjectivesDataAttribute();
                $response["grant_amount_allocated"]                 =   empty($response->current_budget->totals) ? 0 : $response->current_budget->totals;
                $response["total_expenditure"]                      =   $response->getTotalExpenditureAttribute();
                $response["total_expenditure_perc"]                 =   $response->getTotalExpenditurePercAttribute();
                $response["current_budget"]                         =   $response->current_budget;
            }
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"project could not be found", "msg"=>$e->getMessage(), 'trace'=>$e->getTraceAsString()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


























    
    /**
     * Operation updateProjectTeamDef
     *
     * Update Project Team by ID.
     *
     * @param int $project_id ID of project to return object (required)
     *
     * @return Http response
     */
    public function updateProjectTeamDef($project_id)
    {
        $form = Request::only(
            'staffs'
            );

        try{
            $project  =   Project::findOrFail($project_id);
            $project->staffs()->sync($form->staffs);
            $response   = Project::with(["budget","project_manager","staffs"])->findOrFail($project_id);
           
            return response()->json(['msg'=>"Team Updated", 'staffs'=>$response], 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }
































    public function projectsGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('projects');
        $qb->select('projects.*');
        $qb->whereNull('projects.deleted_at');
        $current_user = JWTAuth::parseToken()->authenticate();

        $response;
        $response_dt;
        $total_records          = $qb->count();
        $records_filtered       = 0;
        
        // Show even ones without Program IDs
        if(!array_key_exists('no_programs', $input)){
            $qb->whereNotNull('projects.program_id');    // Showing even PIDs without an attached program id
        }

        // For reports
        // Only show the ones with a budget here
        if(array_key_exists('for_reports', $input)){
            $qb->rightJoin('budgets', 'projects.budget_id', 'budgets.id')
                ->whereNull('budgets.deleted_at');
        }
        
        //my_assigned
        if((array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true")&&($current_user->hasRole(['accountant','assistant-accountant','financial-controller','admin-manager']))){
            $qb->select(DB::raw('projects.*'))
            ->whereNotNull('project_code');
        }
        elseif ((array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true") && array_key_exists('staff_responsible', $input)) {
            
            if(array_key_exists('staff_responsible', $input)){
                $qb->select(DB::raw('projects.*'))
                 ->rightJoin('project_teams', 'project_teams.project_id', '=', 'projects.id')
                 ->rightJoin('staff', 'staff.id', '=', 'project_teams.staff_id')
                 ->where('staff.id', '=', $input['staff_responsible'])
                 ->whereNotNull('projects.id')
                 ->whereNotNull('projects.project_code')
                 ->groupBy('projects.id');
            }
        }
        elseif (array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true") {
            if($current_user->hasRole(['program-manager'])){
                $qb->select(DB::raw('projects.*'))
                 ->rightJoin('programs', 'programs.id', '=', 'projects.program_id')
                 ->rightJoin('program_managers', 'program_managers.program_id', '=', 'programs.id')
                 ->rightJoin('staff', 'staff.id', '=', 'program_managers.program_manager_id')
                 ->where('staff.id', '=', $current_user->id)
                 ->whereNotNull('projects.id')
                 ->groupBy('projects.id');
            }
            else {
                $qb->select(DB::raw('projects.*'))
                    ->rightJoin('project_teams', 'project_teams.project_id', '=', 'projects.id')
                    ->rightJoin('staff', 'staff.id', '=', 'project_teams.staff_id')
                    ->where('staff.id', '=', $current_user->id)
                    ->whereNotNull('projects.id')
                    ->whereNotNull('projects.project_code')
                    ->groupBy('projects.id');
            }
        }

        //my_pm_assigned
        if(array_key_exists('my_pm_assigned', $input)&& $input['my_pm_assigned'] = "true"){
            $qb->select(DB::raw('projects.*'))
                 ->rightJoin('programs', 'programs.id', '=', 'projects.program_id')
                 ->rightJoin('program_managers', 'program_managers.program_id', '=', 'programs.id')
                 ->rightJoin('staff', 'staff.id', '=', 'program_managers.program_manager_id')
                 ->where('staff.id', '=', $current_user->id)
                 ->whereNotNull('projects.id')
                 ->groupBy('projects.id');
        }

        //program_id
         if(array_key_exists('program_id', $input)){
            $program_id = (int) $input['program_id'];
            if($program_id==0){
            }else if($program_id==1){
                $qb->where('projects.program_id',$program_id);
            }
        }

        // Unallocated
        if(array_key_exists('project_type', $input) && $input['project_type']=='unallocated'){
            // $qb->where('project_code', 'like', '%' . Input::get('name') . '%');
            $qb->where('project_code', 'like', '"%CHKENYDINOH1%"');
            $qb->orWhere('project_code', 'like', '"%CHG&ADHQEM1%"');
            $qb->orWhere('project_code', 'like', '"%CHGADHQAC1%"');
            $qb->orWhere('project_code', 'like', '"%CHGADHQEM1%"');
            $qb->orWhere('project_code', 'like', '"%CHGADHQHR1%"');
            $qb->orWhere('project_code', 'like', '"%CHGADHQRC1%"');
            $qb->orWhere('project_code', 'like', '"%CHGMGTDGPM1%"');
            $qb->orWhere('project_code', 'like', '"%CHGMGTDSASE1%"');
        }


        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('projects.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('projects.project_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('projects.project_desc','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('projects.project_code','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("projects.*"," count(*) AS count ", $sql);
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
                $query->orWhere('projects.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_code','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("projects.*"," count(*) AS count ", $sql);
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

            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());
            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Project::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $qb->where('projects.status_id', 1);
            $sql            = Project::bind_presql($qb->toSql(),$qb->getBindings());
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

            $projects = Project::find($data[$key]['id']);
            $data[$key]['expenditure_perc']         = $projects->total_expenditure_perc;
            $data[$key]['total_expenditure']        = $projects->total_expenditure;
            $data[$key]['program']                  = $projects->program;
            $data[$key]['status']                   = $projects->status;
            $data[$key]['country']                  = $projects->country;
            $data[$key]['staffs']                   = $projects->staffs;
            $data[$key]['budget']                   = $projects->budget;
            $data[$key]['grant']                    = $projects->grant;
        }

        return $data;
    }










    



    public function append_relationships_nulls($data = array()){
        foreach ($data as $key => $value) {
            if($data[$key]["program"]==null){
                $data[$key]["program"] = array("program_name"=>"N/A");
            }
            if($data[$key]["status"]==null){
                $data[$key]["status"] = array("project_status"=>"N/A");
            }
            if($data[$key]["country"]==null){
                $data[$key]["country"] = array("country_name"=>"N/A");
            }
            if($data[$key]["budget"]==null){
                $data[$key]["budget"] = array("budget_desc"=>"N/A","totals"=>0);
            }
            if($data[$key]["grant"]==null){
                $data[$key]["grant"] = array("grant_name"=>"");
            }
        }

        return $data;
    }


    public function getTrackerList(){
        try{
            $result = [];
            // $pids = Project::with('budget','program');
            
            // Get pm pids
            $pm_programs = ProgramManager::where('program_manager_id', $this->current_user()->id)->pluck('program_id')->toArray();
            $pm_pids = Project::with('budget')->has('budget')->whereIn('program_id', $pm_programs)->get();

            // foreach($pids as $key => $value){
            //     // Get total expenditure
            //     $allocation_total = 0;
            //     $allocations = Allocation::where('project_id', $value->id)->whereYear('created_at', date('Y'))->get();
            //     foreach ($allocations as $ex) {
            //         $allocatable = null;
            //         if($ex->allocatable_type=='invoices' && !empty($ex->allocatable_id)){
            //             $allocatable = Invoice::has('payments')->where('id', $ex->allocatable_id)->first();
            //         }else if($ex->allocatable_type=='mobile_payments' && !empty($ex->allocatable_id)){
            //             $allocatable = MobilePayment::whereIn('status_id',[4,5,6,11,12,13,17])->where('id', $ex->allocatable_id)->first();
            //         }else if($ex->allocatable_type=='claims' && !empty($ex->allocatable_id)){
            //             $allocatable = Claim::has('payments')->where('id', $ex->allocatable_id)->first();
            //         }else if($ex->allocatable_type=='advances' && !empty($ex->allocatable_id)){
            //             $allocatable = Advance::has('payments')->where('id', $ex->allocatable_id)->first();
            //         }
    
            //         if($allocatable && $allocatable->currency_id){
            //             $allocation_total += (float) $ex->converted_usd;
            //         }
            //     }
            //     $pids[$key]['total_expenditure'] = $allocation_total;
            //     if(!empty($this->budget)) $budget_amount = (int) $this->budget->totals;
            //     else $budget_amount = 0;
            //     $pids[$key]['expenditure_perc'] = $budget_amount==0 ? 0 : ($allocation_total/$budget_amount)*100;
            // }

            $batches = PaymentBatch::whereYear('created_at', date('Y'))->pluck('id')->toArray();
            $payments = Payment::whereIn('payment_batch_id', $batches)->get();
            $payables = [];
            $allocations = [];

            $res = [];
            $c = 0;
            $total_expenditure = 0;
            foreach($pm_pids as $pid){
                // file_put_contents ( "C://Users//kennl//Documents//debug.txt" , PHP_EOL.json_encode($pid) , FILE_APPEND);
                foreach($payments as $p){
                    // $allocs = [];
                    // $payable = null;
                    // if($p->payable_type=='invoices' && !empty($p->payable_id)){
                    //     $payable = Invoice::with('allocations')->whereIn('status_id',[5,6,7,8])->where('id', $p->payable_id)->first();
                    // }else if($p->payable_type=='mobile_payments' && !empty($p->payable_id)){
                    //     $payable = MobilePayment::with('allocations')->whereIn('status_id',[4,5,6,11,12,13,17])->where('id', $p->payable_id)->first();
                    // }else if($p->payable_type=='claims' && !empty($p->payable_id)){
                    //     $payable = Claim::with('allocations')->whereIn('status_id',[6,7,8])->where('id', $p->payable_id)->first();
                    // }else if($p->payable_type=='advances' && !empty($p->payable_id)){
                    //     $payable = Advance::with('allocations')->whereIn('status_id',[5,6,7,9,10])->where('id', $p->payable_id)->first();
                    // }
                    // // $payables[] = ['allocatable'=>$allocatable, 'allocatable_type'=>$p->allocatable_type];
                    //     // file_put_contents ( "C://Users//kennl//Documents//debug.txt" , PHP_EOL.json_encode($allocatable) , FILE_APPEND);
                        
                    // // if($c < 10){
                    // //     file_put_contents ( "C://Users//kennl//Documents//debug.txt" , PHP_EOL.json_encode($payable->allocations) , FILE_APPEND);
                    // //     $c += 1;
                    // // }
                    // if(!empty($payable)){
                    //     foreach ($payable->allocations as $item){
                    //         if ($item->project_id == $pid->id) {
                    //             $total_expenditure += $item->amount_allocated;
                    //         }
                    //     }
                    //     // array_merge($allocations, $this->pluck($payable->allocations, 'project_id', $pid->id));
                    // }
                    // file_put_contents ( "C://Users//kennl//Documents//debug.txt" , PHP_EOL.is_array($allocations) , FILE_APPEND);

                    if(!empty($p->payable_id)){
                        $a = Allocation::where('allocatable_id', $p->payable_id)->where('project_id', $pid)->where('allocatable_type',$p->payable_type)->get()->toArray(); 
                        array_merge($allocations, $a);
                    }
                    
                }
                foreach ($allocations as $item) {
                    $total_expenditure += $item['converted_usd'];
                }
                if(!empty($this->current_budget)) $budget_amount = (int) $this->current_budget->totals;
                else $budget_amount = 0;

                $res[] = [
                    'id'=>$pid->id,
                    'budget'=>$pid->budget,
                    'current_budget'=>$pid->current_budget,
                    'program'=>$pid->program,
                    'expenditure_perc'=> $budget_amount == 0 ? 0 : ($total_expenditure/$budget_amount)*100,
                    'project_code'=>$pid->project_code,
                    'project_name'=>$pid->project_name,
                    'total_expenditure'=>$total_expenditure
                ];
            }


            return response()->json($res, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"something went wrong", 'msg'=>$e->getMessage(), 'stack'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
