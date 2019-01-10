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

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ProjectsModels\Project;
use App\Models\ProjectsModels\ProjectActivity;
use App\Models\ProjectsModels\ProjectBudgetAccount;
use App\Models\ProjectsModels\ProjectCashNeed;
use App\Models\ProjectsModels\ProjectMasterList;
use App\Models\ProjectsModels\ProjectObjective;
use App\Models\ProjectsModels\ProjectTeam;

use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class ProjectApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }































    

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
        $input = Request::all();


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

            $response   = Project::with(["budget","project_manager","staffs", "grant"])
                                    ->findOrFail($project_id);


            //with_chart_data
            if(array_key_exists('with_chart_data', $input)&& $input['with_chart_data'] = "true"){


                $project = Project::find($project_id);
                $response["budget_expenditure_by_accounts_data"]    =   $project->getBudgetExpenditureByAccountsDataAttribute();
                $response["grant_amount_allocated"]                 =   $project->getGrantAmountAllocatedAttribute();
                $response["total_expenditure"]                      =   $project->getTotalExpenditureAttribute();
                $response["total_expenditure_perc"]                 =   $project->getTotalExpenditurePercAttribute();


            }
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"project could not be found"];
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

            $response =  ["error"=>"project could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
































    public function projectsGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('projects');

        $qb->whereNull('projects.deleted_at');
        $qb->whereNotNull('program_id');    // Not showing PIDs without an attached program id
        $current_user = JWTAuth::parseToken()->authenticate();

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        
        //my_assigned
        if((array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true")&&($current_user->hasRole(['accountant','assistant-accountant','financial-controller','admin-manager']))){

            $qb->whereNotNull('project_code');
        }elseif (array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true") {

            $qb->select(DB::raw('projects.*'))
                 ->rightJoin('project_teams', 'project_teams.project_id', '=', 'projects.id')
                 ->rightJoin('staff', 'staff.id', '=', 'project_teams.staff_id')
                 ->where('staff.id', '=', $current_user->id)
                 ->whereNotNull('projects.id')
                 ->whereNotNull('projects.project_code')
                 ->groupBy('projects.id');
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
                $qb->where('program_id',$program_id);
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

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb->orderBy($order_column_name, $order_direction);
        }else{
            // $qb->orderBy("project_code", "asc");
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
                
                $query->orWhere('projects.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('projects.project_code','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Project::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Project::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $qb->where('status_id', 1);

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

            $grant_amount_allocated     =   $data[$key]['grant_amount_allocated']   = (double) $projects->grant_amount_allocated;
            $total_expenditure          =   $data[$key]['total_expenditure']        = (double) $projects->total_expenditure;
            if($projects->grant_amount_allocated!=0){
                $data[$key]['expenditure_perc']         = ($total_expenditure/$grant_amount_allocated)*100;
            }else{
                $data[$key]['expenditure_perc']         = 0;
            }
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
}
