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
use App\Models\ClaimsModels\Claim;
use App\Models\ClaimsModels\ClaimStatus;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyClaim;
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

class ClaimApi extends Controller
{


    private $default_status = '';
    private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {
        $status = ClaimStatus::where('default_status','1')->first();
        $this->approvable_statuses = ClaimStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
    }

    /**
     * Operation addClaim
     *
     * Add a new claim.
     *
     *
     * @return Http response
     */
    public function addClaim()
    {


        // $input = Request::all();

        $claim = new Claim;


        try{


            $form = Request::only(
                'requested_by_id',
                'expense_desc',
                'expense_purpose',
                'project_manager_id',
                'total',
                'currency_id',
                'file'
                );


            // FTP::connection()->changeDir('/lpos');

            $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            $file = $form['file'];


            $claim->requested_by_id                   =   (int)       $form['requested_by_id'];
            $claim->expense_desc                      =               $form['expense_desc'];
            $claim->expense_purpose                   =               $form['expense_purpose'];
            $claim->project_manager_id                =   (int)       $form['project_manager_id'];
            $claim->total                             =   (double)    $form['total'];
            $claim->currency_id                       =   (int)       $form['currency_id'];           

            $claim->status_id                         =   $this->default_status;


            if($claim->save()) {

                FTP::connection()->makeDir('/claims');
                FTP::connection()->makeDir('/claims/'.$claim->id);
                FTP::connection()->uploadFile($file->getPathname(), '/claims/'.$claim->id.'/'.$claim->id.'.'.$file->getClientOriginalExtension());

                $claim->claim_document           =   $claim->id.'.'.$file->getClientOriginalExtension();
                $claim->ref                        = "CHAI/CLM/#$claim->id/".date_format($claim->created_at,"Y/m/d");
                $claim->save();
                
                return Response()->json(array('success' => 'Claim Added','claim' => $claim), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }


















    /**
     * Operation updateClaim
     *
     * Update an existing claim.
     *
     *
     * @return Http response
     */
    public function updateClaim()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateClaim');
        }
        $body = $input['body'];


        return response('How about implementing updateClaim as a PUT method ?');
    }


















    /**
     * Operation deleteClaim
     *
     * Deletes an claim.
     *
     * @param int $claim_id claim id to delete (required)
     *
     * @return Http response
     */
    public function deleteClaim($claim_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteClaim as a DELETE method ?');
    }


















    /**
     * Operation getClaimById
     *
     * Find claim by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function getClaimById($claim_id)
    {
        $response = [];

        try{
            $response   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'logs',
                                        'vouchers',
                                        'payments',
                                        'allocations'
                                    )->findOrFail($claim_id);


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
                $appoval_level = ApprovalLevel::find((int)$value['approval_level_id']);

                $response['approvals'][$key]['approver']  =   $approver;
                $response['approvals'][$key]['approval_level']  =   $appoval_level;
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

            $response =  ["error"=>"Claim could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


















    /**
     * Operation allocateClaim
     *
     * Allocate claim by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function allocateClaim($claim_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing allocateClaim as a PATCH method ?');
    }


















    /**
     * Operation approveClaim
     *
     * Approve claim by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function approveClaim($claim_id)
    {
        $claim = [];

        try{
            $claim   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'allocations'
                                    )->findOrFail($claim_id);

           
            $claim->status_id = $claim->status->next_status_id;

            if($claim->save()) {

                $claim   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'allocations'
                                    )->findOrFail($claim_id);

                $approval = new Approval;

                $user = JWTAuth::parseToken()->authenticate();

                $approval->approvable_id            =   (int)   $claim->id;
                $approval->approvable_type          =   "claims";
                $approval->approval_level_id        =   $claim->status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();

                Mail::send(new NotifyClaim($claim));

                return Response()->json(array('msg' => 'Success: claim approved','claim' => $claim), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Claim could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


























    
    /**
     * Operation rejectClaim
     *
     * Reject claim by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function rejectClaim($claim_id)
    {

        $form = Request::only(
            'rejection_reason'
            );
        
        $claim = [];

        try{
            $claim   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'allocations'
                                    )->findOrFail($claim_id);

           
            $claim->status_id = 9;
            $user = JWTAuth::parseToken()->authenticate();
            $claim->rejected_by_id            =   (int)   $user->id;
            $claim->rejected_at              =   date('Y-m-d H:i:s');
            $claim->rejection_reason             =   $form['rejection_reason'];

            if($claim->save()) {

                Mail::send(new NotifyClaim($claim));

                return Response()->json(array('msg' => 'Success: claim approved','claim' => $claim), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Claim could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }




















    /**
     * Operation getDocumentById
     *
     * get claim document by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($claim_id)
    {


        try{


            $claim          = Claim::findOrFail($claim_id);

            $path           = '/claims/'.$claim->id.'/'.$claim->claim_document;

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('claims/'.$claim->id.'.temp', $file_contents);

            $url            = storage_path("app/claims/".$claim->id.'.temp');

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


















    /**
     * Operation submitClaimForApproval
     *
     * Submit claim by ID.
     *
     * @param int $claim_id ID of claim to return object (required)
     *
     * @return Http response
     */
    public function submitClaimForApproval($claim_id)
    {
        
        $claim = [];

        try{
            $claim   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'allocations'
                                    )->findOrFail($claim_id);

           
            $claim->status_id = $claim->status->next_status_id;
            $claim->requested_at = date('Y-m-d H:i:s');

            if($claim->save()) {

                Mail::send(new NotifyClaim($claim));

                return Response()->json(array('msg' => 'Success: claim submitted','claim' => $claim), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Claim could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


















    /**
     * Operation getClaims
     *
     * claims List.
     *
     *
     * @return Http response
     */
    public function getClaims()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('claims');

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
                        $permission = 'APPROVE_CLAIM_'.$value['id'];
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

            $sql = Claim::bind_presql($qb->toSql(),$qb->getBindings());
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
                
                $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_purpose','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Claim::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Claim::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Claim::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Claim::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }




























    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $claim = Claim::find($data[$key]['id']);

            $data[$key]['requested_by']                 = $claim->requested_by;
            $data[$key]['request_action_by']            = $claim->requested_action_by;
            $data[$key]['project']                      = $claim->project;
            $data[$key]['status']                       = $claim->status;
            $data[$key]['project_manager']              = $claim->project_manager;
            $data[$key]['currency']                     = $claim->currency;
            $data[$key]['rejected_by']                  = $claim->rejected_by;
            $data[$key]['approvals']                    = $claim->approvals;
            $data[$key]['allocations']                  = $claim->allocations;

            foreach ($claim->allocations as $key1 => $value1) {
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
                $data[$key]['status'] = array("claim_status"=>"N/A");
                
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
