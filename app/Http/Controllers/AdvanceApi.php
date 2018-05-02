<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdvancesModels\Advance;
use App\Models\AdvancesModels\AdvanceStatus;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyAdvance;
use App\Models\AllocationModels\Allocation;
use App\Models\ApprovalsModels\Approval;
use App\Models\ApprovalsModels\ApprovalLevel;
use App\Models\StaffModels\Staff;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentMode;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\PaymentType;
use App\Models\LookupModels\Currency;
use App\Models\BankingModels\BankBranch;
use App\Exceptions\NotFullyAllocatedException;
use App\Exceptions\ApprovalException;

class AdvanceApi extends Controller
{


    private $default_status = '';
    private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {
        $status = AdvanceStatus::where('default_status','1')->first();
        $this->approvable_statuses = AdvanceStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
    }






















    

    /**
     * Operation addAdvance
     *
     * Add a new advance.
     *
     *
     * @return Http response
     */
    public function addAdvance()
    {
        


        // $input = Request::all();

        $advance = new Advance;


        try{


            $form = Request::only(
                'requested_by_id',
                'expense_desc',
                'expense_purpose',
                'project_manager_id',
                'total',
                'currency_id',
                'payment_mode_id',
                'file'
                );


            // FTP::connection()->changeDir('/lpos');

            $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            $file = $form['file'];


            $advance->requested_by_id                   =   (int)       $form['requested_by_id'];
            // $advance->request_action_by_id              =   (int)      $body['request_action_by_id'];
            $advance->expense_desc                      =               $form['expense_desc'];
            $advance->expense_purpose                   =               $form['expense_purpose'];
            $advance->project_manager_id                =   (int)       $form['project_manager_id'];
            $advance->payment_mode_id                   =   (int)       $form['payment_mode_id'];
            $advance->total                             =   (double)    $form['total'];
            $advance->currency_id                       =   (int)       $form['currency_id'];           

            $advance->status_id                         =   $this->default_status;


            if($advance->save()) {

                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->uploadFile($file->getPathname(), '/advances/'.$advance->id.'/'.$advance->id.'.'.$file->getClientOriginalExtension());

                $advance->advance_document           =   $advance->id.'.'.$file->getClientOriginalExtension();
                $advance->ref                        = "CHAI/ADV/#$advance->id/".date_format($advance->created_at,"Y/m/d");
                $advance->save();
                
                return Response()->json(array('success' => 'Advance Added','advance' => $advance), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }






















    
    /**
     * Operation updateAdvance
     *
     * Update an existing advance.
     *
     *
     * @return Http response
     */
    public function updateAdvance()
    {
        try{

        
         $form = Request::only(
            'id',
            'requested_by_id',
            'expense_desc',
            'expense_purpose',
            'project_manager_id',
            'payment_mode_id',
            'total',
            'currency_id',
            'file'
            );

            $ftp = FTP::connection()->getDirListing();
            $file = $form['file'];

        $advance = Advance::findOrFail($form['id']);





            $advance->requested_by_id                   =   (int)       $form['requested_by_id'];
            $advance->expense_desc                      =               $form['expense_desc'];
            $advance->expense_purpose                   =               $form['expense_purpose'];
            $advance->project_manager_id                =   (int)       $form['project_manager_id'];
            $advance->payment_mode_id                   =   (int)       $form['payment_mode_id'];
            $advance->total                             =   (double)    $form['total'];
            $advance->currency_id                       =   (int)       $form['currency_id'];  



        if($advance->save()) {

            if($file!=0){
                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->uploadFile($file->getPathname(), '/advances/'.$advance->id.'/'.$advance->id.'.'.$file->getClientOriginalExtension());

                $advance->advance_document           =   $advance->id.'.'.$file->getClientOriginalExtension();
            }

            return Response()->json(array('msg' => 'Success: Advance updated','advance' => $advance), 200);
        }
        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }
    }






















    
    /**
     * Operation deleteAdvance
     *
     * Deletes an advance.
     *
     * @param int $advance_id advance id to delete (required)
     *
     * @return Http response
     */
    public function deleteAdvance($advance_id)
    {
        $input = Request::all();

        $deleted = Advance::destroy($advance_id);

        if($deleted){
            return response()->json(['msg'=>"Advance deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Advance not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }






















    
    /**
     * Operation getAdvanceById
     *
     * Find advance by ID.
     *
     * @param int $advance_id ID of advance to return object (required)
     *
     * @return Http response
     */
    public function getAdvanceById($advance_id)
    {

        $input = Request::all();

        try{

            $response   = Advance::with(
                                    'requested_by',
                                    'request_action_by',
                                    'project',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'approvals',
                                    'allocations',
                                    'vouchers',
                                    'payments',
                                    'logs'
                                )->findOrFail($advance_id);


            foreach ($response->allocations as $key => $value) {
                $project = Project::find((int)$value['project_id']);
                $account = Account::find((int)$value['account_id']);
                $response['allocations'][$key]['project']  =   $project;
                $response['allocations'][$key]['account']  =   $account;
            }

            foreach ($response->logs as $key => $value) {
                
                $response['logs'][$key]['causer']   =   $value->causer;
                $response['logs'][$key]['subject']  =   $value->subject;
            }

            foreach ($response->approvals as $key => $value) {
                $approver = Staff::find((int)$value['approver_id']);
                $approval_level = ApprovalLevel::find((int)$value['approval_level_id']);

                $response['approvals'][$key]['approver']  =   $approver;
                $response['approvals'][$key]['approval_level']  =   $approval_level;
            }

            foreach ($response->payments as $key => $value) {
                $payment_mode           = PaymentMode::find((int)$value['payment_mode_id']);
                $currency               = Currency::find((int)$value['currency_id']);
                $payment_batch          = PaymentBatch::find((int)$value['payment_batch_id']);
                $paid_to_bank_branch    = BankBranch::with('bank')->find((int)$value['paid_to_bank_branch_id']);

                $response['payments'][$key]['payment_mode']   =   $payment_mode;
                $response['payments'][$key]['currency']       =   $currency;
                $response['payments'][$key]['payment_batch']  =   $payment_batch;
                $response['payments'][$key]['paid_to_bank_branch']   =   $paid_to_bank_branch;
            }
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"Advance could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }






















    
    /**
     * Operation approveAdvance
     *
     * Approve advance by ID.
     *
     * @param int $advance_id ID of advance to return object (required)
     *
     * @return Http response
     */
    public function approveAdvance($advance_id, $several=null)
    {

        $input = Request::all();

        $user = JWTAuth::parseToken()->authenticate();

        try{

            $advance   = Advance::with(
                                    'requested_by',
                                    'request_action_by',
                                    'project',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($advance_id);
           
            if (!$user->can("APPROVE_ADVANCE_".$advance->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $approvable_status  = $advance->status;
            $advance->status_id = $advance->status->next_status_id;

            if($advance->save()) {

                $advance   = Advance::with(
                                    'requested_by',
                                    'request_action_by',
                                    'project',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($advance_id);

                $approval = new Approval;

                $approval->approvable_id            =   (int)   $advance->id;
                $approval->approvable_type          =   "advances";
                $approval->approval_level_id        =   $approvable_status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();

                if($approval->approval_level_id ==4){

                    $payable    =   array(
                        'payable_type'                  =>  'advances', 
                        'payable_id'                    =>  $advance->id, 
                        'debit_bank_account_id'         =>  $advance->currency_id, 
                        'currency_id'                   =>  $advance->currency_id, 
                        'payment_desc'                  =>  $advance->expense_desc, 
                        'paid_to_name'                  =>  $advance->requested_by->full_name, 
                        'paid_to_mobile_phone_no'       =>  $advance->requested_by->mpesa_no, 
                        'paid_to_bank_account_no'       =>  $advance->requested_by->bank_account, 
                        'paid_to_bank_id'               =>  $advance->requested_by->bank_id, 
                        'paid_to_bank_branch_id'        =>  $advance->requested_by->bank_branch_id, 
                        'payment_mode_id'               =>  $advance->payment_mode->id, 
                        'amount'                        =>  $advance->total, 
                        'payment_batch_id'              =>  "", 
                        'bank_charges'                  =>  ""
                    );

                    $this->generate_payable_payment($payable);

                }
                
                Mail::queue(new NotifyAdvance($advance));

                if($several!=true)
                return Response()->json(array('msg' => 'Success: advance approved','advance' => $advance), 200);
            }


        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Advance could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
















    /**
     * Operation rejectAdvance
     *
     * Approve advance by ID.
     *
     * @param int $advance_id ID of advance to return object (required)
     *
     * @return Http response
     */
    public function rejectAdvance($advance_id)
    {

        $form = Request::only(
            'rejection_reason'
            );

        $user = JWTAuth::parseToken()->authenticate();

        try{

            $advance   = Advance::with(
                                    'requested_by',
                                    'request_action_by',
                                    'project',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($advance_id);
           
            if (!$user->can("APPROVE_ADVANCE_".$advance->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $advance->status_id = 11;
            $advance->rejected_by_id            =   (int)   $user->id;
            $advance->rejected_at              =   date('Y-m-d H:i:s');
            $advance->rejection_reason             =   $form['rejection_reason'];

            if($advance->save()) {

                Mail::queue(new NotifyAdvance($advance));

                return Response()->json(array('msg' => 'Success: advance approved','advance' => $advance), 200);
            }


        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Advance could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }











    










    
    /**
     * Operation submitAdvanceForApproval
     *
     * Submit advance by ID.
     *
     * @param int $advance_id ID of advance to return object (required)
     *
     * @return Http response
     */
    public function submitAdvanceForApproval($advance_id)
    {


        $input = Request::all();

        try{

            $advance   = Advance::with(
                                    'requested_by',
                                    'request_action_by',
                                    'project',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($advance_id);
           
            $advance->status_id = $advance->status->next_status_id;
            $advance->requested_at = date('Y-m-d H:i:s');

            if($advance->save()) {
                
                try{
                Mail::queue(new NotifyAdvance($advance));
                }
                catch(Exception $e){}

                return Response()->json(array('msg' => 'Success: advance submitted','advance' => $advance), 200);
            }


        }catch(Exception $e){

            $response =  ["error"=>"Advance could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
















/**
     * Operation approveSeveralAdvances
     *
     * Approve several Advances.
     *
     *
     * @return Http response
     */
    public function approveSeveralAdvances()
    {
        try {
            $form = Request::only("advances");
            $advance_ids = $form['advances'];

            foreach ($advance_ids as $key => $advance_id) {
                $this->approveAdvance($advance_id, true);
            }

            return response()->json(['advances'=>$form['advances']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
            
        }
    }



















    
    /**
     * Operation getAdvances
     *
     * advances List.
     *
     *
     * @return Http response
     */
    public function getAdvances()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('advances');

        $qb->whereNull('deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;






        //if status is set

        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $qb->where('status_id', $input['status']);
                $qb->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-2) {
                
            }elseif ($status_==-3) {
                $qb->where('project_manager_id',$this->current_user()->id);
            }




            // $total_records          = $qb->count();     //may need this
        }


        
        $app_stat = $this->approvable_statuses ;
        //if approvable is set

        if(array_key_exists('approvable', $input)){

            $qb->where(function ($query) use ($app_stat) {
                    
                foreach ($app_stat as $key => $value) {
                    $query->orWhere('status_id',$value['id']);
                }

            });
        }




        if(array_key_exists('my_approvables', $input)){


            $current_user =  JWTAuth::parseToken()->authenticate();
            if($current_user->hasRole([
                'super-admin',
                'admin',
                'director',
                'associate-director',
                'financial-controller',
                'program-manager', 
                'accountant', 
                'assistant-accountant']
            )){                   
                $qb->where(function ($query) use ($app_stat,$current_user) {
                    foreach ($app_stat as $key => $value) {
                        $permission = 'APPROVE_ADVANCE_'.$value['id'];
                        if($current_user->can($permission)&&$value['id']==2){
                            $query->orWhere(function ($query1) use ($value,$current_user) {
                                $query1->Where('status_id',$value['id']);
                                $query1->Where('project_manager_id',$current_user->id);
                            });
                        }
                        else if($current_user->can($permission)){
                            $query->orWhere('status_id',$value['id']); 
                        }
                    }

                });


            }else{
                $qb->where('id',0);
            }
            // echo $qb->toSql();die;
        }



        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_desc','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('expense_purpose','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Advance::bind_presql($qb->toSql(),$qb->getBindings());
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
            //$qb->orderBy("project_code", "asc");
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
                
                $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_purpose','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Advance::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];


            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            // if ($order_column_id == 0){
            //     $order_column_name = "created_at";
            // }
            // if ($order_column_id == 1){
            //     $order_column_name = "id";
            // }

            if($order_column_name!=''){

                $qb->orderBy($order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = Advance::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Advance::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Advance::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
























    /**
     * Operation getAdvanceDocumentById
     *
     * get advance document by ID.
     *
     * @param int $advance_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function getAdvanceDocumentById($advance_id)
    {


        try{


            $advance          = Advance::findOrFail($advance_id);

            $path           = '/advances/'.$advance->id.'/'.$advance->advance_document;

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('advances/'.$advance->id.'.temp', $file_contents);

            $url            = storage_path("app/advances/".$advance->id.'.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make("", 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;  

        }
    }









    













    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $advance = Advance::find($data[$key]['id']);

            $data[$key]['requested_by']                 = $advance->requested_by;
            $data[$key]['request_action_by']            = $advance->requested_action_by;
            $data[$key]['project']                      = $advance->project;
            $data[$key]['status']                       = $advance->status;
            $data[$key]['project_manager']              = $advance->project_manager;
            $data[$key]['currency']                     = $advance->currency;
            $data[$key]['rejected_by']                  = $advance->rejected_by;
            $data[$key]['approvals']                    = $advance->approvals;
            $data[$key]['allocations']                  = $advance->allocations;
            

            foreach ($advance->allocations as $key1 => $value1) {
                $project = Project::find((int)$value1['project_id']);
                $account = Account::find((int)$value1['account_id']);
                $data[$key]['allocations'][$key1]['project']  =   $project;
                $data[$key]['allocations'][$key1]['account']  =   $account;
            }

        }

        return $data;


    }
















    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($value["requested_by"]==null){
                $data[$key]['requested_by'] = array("full_name"=>"N/A");
                
            }
            if($value["request_action_by"]==null){
                $data[$key]['request_action_by'] = array("full_name"=>"N/A");
                
            }
            if($value["project"]==null){
                $data[$key]['project'] = array("project_name"=>"N/A");
                
            }
            if($value["status"]==null){
                $data[$key]['status'] = array("advance_status"=>"N/A");
                
            }
            if($value["project_manager"]==null){
                $data[$key]['project_manager'] = array("full_name"=>"N/A");
                
            }
            if($value["rejected_by"]==null){
                $data[$key]['rejected_by'] = array("full_name"=>"N/A");
                
            }
            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
        }

        return $data;


    }










}
