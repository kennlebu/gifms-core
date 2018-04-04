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
use App\Models\LPOModels\Lpo;
use App\Models\LPOModels\LpoStatus;
use App\Models\LPOModels\LpoQuotation;
use App\Models\LPOModels\LpoQuoteExemptReason;
use Exception;
use PDF;
use App;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyLpo;
use App\Mail\NotifyLpoDispatch;
use App\Models\AllocationModels\Allocation;
use App\Models\ApprovalsModels\Approval;
use App\Models\ApprovalsModels\ApprovalLevel;
use App\Models\StaffModels\Staff;
use App\Models\SuppliesModels\Supplier;
use App\Exceptions\NoLpoItemsException;
use App\Exceptions\LpoQuotationAmountMismatchException;
use App\Exceptions\ApprovalException;


class LPOApi extends Controller
{


    private $default_status = '';
    private $approvable_statuses = [];
    /**
    * Constructor
    */
    public function __construct()
    {
        $status = LpoStatus::where('default_status','1')->first();
        $this->approvable_statuses = LpoStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
    }






























    

    /**
    * Operation add
    *
    * Add a new lpo request to the store.
    *
    *
    * @return Http response
    */
    public function add()
    {
        $input = Request::all();



        $form = Request::only(
            'requested_by_id',
            'expense_desc',
            'expense_purpose',
            'project_id',
            'account_id',
            'currency_id',
            'project_manager_id',
            'quote_exempt_explanation'
            );

        try{

            $lpo = new Lpo;

            $lpo->requested_by_id                   =   (int)   $form['requested_by_id'];
            $lpo->expense_desc                      =           $form['expense_desc'];
            $lpo->expense_purpose                   =           $form['expense_purpose'];
            $lpo->project_id                        =   (int)   $form['project_id'];
            $lpo->account_id                        =   (int)   $form['account_id'];
            $lpo->currency_id                       =   (int)   $form['currency_id'];
            $lpo->project_manager_id                =   (int)   $form['project_manager_id'];
            $lpo->status_id                         =   $this->default_status;
            $lpo->quote_exempt_explanation          = $form['quote_exempt_explanation'];

            $user = JWTAuth::parseToken()->authenticate();
            $lpo->request_action_by_id            =   (int)   $user->id;



            if($lpo->save()) {

                $lpo->ref = "CHAI/LPO/#$lpo->id/".date_format($lpo->created_at,"Y/m/d");
                $lpo->save();

                return Response()->json(array('msg' => 'Success: lpo added','lpo' => Lpo::find((int)$lpo->id)), 200);
            }

        }catch (JWTException $e){

            return response()->json(['error'=>'something went wrong'], 500);

        }

    }

































    /**
    * Operation updateLpo
    *
    * Update an existing LPO.
    *
    *
    * @return Http response
    */
    public function updateLpo()
    {
        // $input = Request::all();

        // if (!isset($input['body'])) {
        //     throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpo');
        // }

        $form = Request::only(
            'id',
            'requested_by_id',
            'expense_desc',
            'expense_purpose',
            'project_id',
            'account_id',
            'currency_id',
            'project_manager_id',
            'preffered_quotation_id',
            'quote_exempt_explanation'
            );

        $lpo = Lpo::find($form['id']);




            $lpo->requested_by_id                   =   (int)   $form['requested_by_id'];
            $lpo->expense_desc                      =           $form['expense_desc'];
            $lpo->expense_purpose                   =           $form['expense_purpose'];
            $lpo->project_id                        =   (int)   $form['project_id'];
            $lpo->account_id                        =   (int)   $form['account_id'];
            $lpo->currency_id                       =   (int)   $form['currency_id'];
            $lpo->project_manager_id                =   (int)   $form['project_manager_id'];
            $lpo->preffered_quotation_id            =   (int)   $form['preffered_quotation_id'];
            $lpo->quote_exempt_explanation = $form['quote_exempt_explanation'];



        if($lpo->save()) {

            return Response()->json(array('msg' => 'Success: lpo updated','lpo' => $lpo), 200);
        }
    }















    /**
    * Operation deleteLpo
    *
    * Deletes an lpo.
    *
    * @param int $lpo_id lpo id to delete (required)
    *
    * @return Http response
    */
    public function deleteLpo($lpo_id)
    {
        $input = Request::all();

        $deleted = Lpo::destroy($lpo_id);

        if($deleted){
            return response()->json(['msg'=>"lpo deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"lpo not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }












    /**
     * Operation recallLpo
     * 
     * Recalls an LPO.
     * 
     * @param int $lpo_id LPO id to recall (required)
     * 
     * @return Http response
     */
    public function recallLpo($lpo_id)
    {
        $input = Request::all();
        
        $lpo = Lpo::find($lpo_id);        

        // Ensure LPO is in the recallable statuses
        if(!in_array($lpo->status_id, [13,3,4,5])){
            return response()->json(['msg'=>"you do not have permission to do this"], 403, array(), JSON_PRETTY_PRINT);
        }

        $lpo->status_id = 11;
        
        if($lpo->save()){
            return response()->json(['msg'=>"lpo recalled"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"could not recall lpo"], 404,array(),JSON_PRETTY_PRINT);
        }

    }
















    /**
     * Operation allocateLpo
     *
     * Allocate lpo by ID.
     *
     * @param int $lpo_id ID of lpo to return object (required)
     *
     * @return Http response
     */
    public function allocateLpo($lpo_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing allocateLpo as a PATCH method ?');
    }
















    /**
     * Operation approveLpo
     *
     * Approve lpo by ID.
     *
     * @param int $lpo_id ID of lpo to return object (required)
     *
     * @return Http response
     */
    public function approveLpo($lpo_id)
    {
        $input = Request::all();

        $user = JWTAuth::parseToken()->authenticate();

        try{

            $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoices',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail($lpo_id);
           
            if (!$user->can("APPROVE_LPO_".$lpo->status_id)){
                throw new ApprovalException("No approval permission");             
            }
           
            $approvable_status  = $lpo->status;
            $lpo->status_id = $lpo->status->next_status_id;

            if($lpo->save()) {

                $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoices',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail($lpo_id);

                $approval = new Approval;

                $approval->approvable_id            =   (int)   $lpo->id;
                $approval->approvable_type          =   "lpos";
                $approval->approval_level_id        =   $approvable_status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();

                if($lpo->status_id!=7){
                    try{
                    Mail::queue(new NotifyLpo($lpo));
                    }catch(Exception $e){

                    }
                }
                elseif($lpo->status_id==7){
                    try{ 
                        Mail::queue(new NotifyLpoDispatch($lpo));
                    }catch(Exception $e){
                    }
                }

                return Response()->json(array('msg' => 'Success: lpo approved','lpo' => $lpo), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"lpo could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


















    /**
     * Operation rejectLpo
     *
     * Approve lpo by ID.
     *
     * @param int $lpo_id ID of lpo to return object (required)
     *
     * @return Http response
     */
    public function rejectLpo($lpo_id)
    {

        $form = Request::only(
            'rejection_reason'
            );

        $user = JWTAuth::parseToken()->authenticate();

        try{

            $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoices',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail($lpo_id);
           
           
            if (!$user->can("APPROVE_LPO_".$lpo->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $lpo->status_id = 12;
            $lpo->rejected_by_id            =   (int)   $user->id;
            $lpo->rejected_at              =   date('Y-m-d H:i:s');
            $lpo->rejection_reason             =   $form['rejection_reason'];

            if($lpo->save()) {

                try{
                Mail::queue(new NotifyLpo($lpo));
                }catch(Exception $e){}

                return Response()->json(array('msg' => 'Success: lpo rejected','lpo' => $lpo), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"lpo could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }



















    /**
     * Operation submitLpoForApproval
     *
     * Submit lpo by ID.
     *
     * @param int $lpo_id ID of lpo to return object (required)
     *
     * @return Http response
     */
    public function submitLpoForApproval($lpo_id)
    {
        
        $input = Request::all();

        try{

            $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoices',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail($lpo_id);

            if ($lpo->preffered_quotation->amount != $lpo->totals ){
                throw new LpoQuotationAmountMismatchException("Total amount does not match with quotation amount");             
            }

            if ($lpo->totals < 1 ){
                throw new NoLpoItemsException("This lpo has no items");             
            }


           
           
            $lpo->status_id = $lpo->status->next_status_id;
            $lpo->requested_at = date('Y-m-d H:i:s');

            if($lpo->save()) {

                try{
                Mail::queue(new NotifyLpo($lpo));
                }catch(Exception $e){
            //$response =  ["error"=>"lpo could not be found"];
            //return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }

                return Response()->json(array('msg' => 'Success: lpo submitted','lpo' => $lpo), 200);
            }

        }catch(LpoQuotationAmountMismatchException $me){

            $response =  ["error"=>$me->getMessage()];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(NoLpoItemsException $le){

            $response =  ["error"=>$le->getMessage()];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"lpo could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }






















    /**
    * Operation getLpoById
    *
    * Find lpo by ID.
    *
    * @param int $lpo_id ID of lpo to return lpo object (required)
    *
    * @return Http response
    */
    public function getLpoById($lpo_id)
    {
        $input = Request::all();

        try{

            $response   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoices',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'logs',
                                            'deliveries'
                                )->findOrFail($lpo_id);



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
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"lpo could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }



















        /**
         * Operation submitOrApprove
         *
         * Submits or Approves Lpo.
         *
         * @param int $lpo_id ID of lpo to return lpo object (required)
         *
         * @return Http response
         * @deprecated 
         */
        public function submitOrApprove($lpo_id)
        {


            try{
                $response;
                $lpo            = Lpo::findOrFail($lpo_id);
                $lpo_status     = Lpo::findOrFail($lpo->status_id);
                $next_status    = $lpo_status->next_status;
                $lpo->status_id = $next_status;


                return Response()->json(array('result' => 'Success','lpo' => $lpo), 200);

            }catch(Exception $e){

                $response =  ["error"=>"lpo could not be found"];
                return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
            }
            
        }




        /**
         * Operation lpoExemptReasons
         * 
         * Gets a list of LPO exempt resaons
         * 
         * @return Http respons
         */
        public function getLpoExemptReasons(){
            try{
                $response = LpoQuoteExemptReason::all();
                return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
            }
            catch (Exception $e){
                file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , 'EXCEPTION:: '.$e.getMessage() , FILE_APPEND);
                $response = ['error'=>'Failed to retrieve records'];
                return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
            }
        }












    /**
     * Operation getDocumentById
     *
     * preview LPO document ID.
     *
     * @param int $lpo_id ID of lpo to return lpo object (required)
     *
     * @return Http response
     */
    public function getDocumentById($lpo_id)
    {   
        try{
            $lpo   = Lpo::findOrFail($lpo_id);
            $unique_approvals = $this->unique_multidim_array($lpo->approvals, 'approver_id');

            $data = array(
                'lpo'   => $lpo,
                'unique_approvals' => $unique_approvals
                );

        // return view('pdf/lpo',$data);

            $pdf = PDF::loadView('pdf/lpo', $data);

            $file_contents  = $pdf->stream();

            Storage::put('lpo/'.$lpo_id.'.temp', $file_contents);

            $url       = storage_path("app/lpo/".$lpo_id.'.temp');

            $file = File::get($url);

            $response = Response::make($file, 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;
        }catch (Exception $e ){            

            $response       = Response::make("", 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;  

        }

    }

    /**
     * Returns an array with unique array elements for a
     * multi-dimensional array
     * $array is the array to be parsed
     * $key is the field that you need to be unique
     */
    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }












    /**
    * Operation updateLpoWithForm
    *
    * Updates a lpo with form data.
    *
    * @param int $lpo_id ID of lpo that needs to be updated (required)
    *
    * @return Http response
    */
    public function updateLpoWithForm($lpo_id)
    {
        $input = Request::all();

        return response('How about implementing updateLpoWithForm as a POST method ?');
    }
























    /**
     * Operation approveSeveralLpos
     *
     * Approve several LPOs.
     *
     *
     * @return Http response
     */
    public function approveSeveralLpos()
    { 
        try {
            $form = Request::only("lpos");
            $lpo_ids = $form['lpos'];

            foreach ($lpo_ids as $key => $lpo_id) {
                $this->approveLpo($lpo_id);
            }

            return response()->json(['lpos'=>$form['lpos']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
            
        }
    }














    /**
    * Operation lposGet
    *
    * lpo List.
    *
    *
    * @return Http response
    */
    public function lposGet()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('lpos');

        $qb->whereNull('lpos.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;


        // if for delivery
        if(array_key_exists('for_delivery',$input)&&$input['for_delivery']==true){
            $qb->where(function ($query){
                $query->whereNull('lpos.delivery_made');
                $query->orWhere('lpos.delivery_made', 'not like', '\'%full%\'');
            });
        }


         // if for invoice
         if(array_key_exists('for_invoice',$input)&&$input['for_invoice']==true){
            $qb->where(function ($query){
                $query->whereNull('lpos.invoice_paid');
                $query->orWhere('lpos.invoice_paid', 'not like', '\'%full%\'');
            });
        }



        //if status is set

        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $qb->where('lpos.status_id', $input['status']);
                $qb->where('lpos.requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb->where('lpos.requested_by_id',$this->current_user()->id);
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
                        $permission = 'APPROVE_LPO_'.$value['id'];
                        if($current_user->can($permission)&&$value['id']==3){
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
                
                $query->orWhere('lpos.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('lpos.ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.expense_desc','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('lpos.expense_purpose','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Lpo::bind_presql($qb->toSql(),$qb->getBindings());
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

        //with_no_deliveries
        if(array_key_exists('with_no_deliveries', $input)){

            $del = (int) $input['with_no_deliveries'];

            if($del==1){
                $qb->leftJoin('deliveries', 'lpos.id', '=', 'deliveries.lpo_id');
                $qb->whereNull('deliveries.lpo_id');
                $qb->orderBy('lpos.id', 'desc');
                $qb->select('lpos.*');
            }

        }

        //with_no_invoices
        if(array_key_exists('with_no_invoices', $input)){

            $inv = (int) $input['with_no_invoices'];

            if($inv==1){
                $qb->leftJoin('invoices', 'lpos.id', '=', 'invoices.lpo_id');
                $qb->whereNull('invoices.lpo_id');
                $qb->orderBy('lpos.id', 'desc');
                $qb->select('lpos.*');
            }


        }

        //supplier
        if(array_key_exists('supplier', $input)){

            $sup = (int) $input['supplier'];

            if($sup>-1){
                $qb->leftJoin('lpo_quotations', 'lpos.id', '=', 'lpo_quotations.lpo_id');
                $qb->leftJoin('suppliers', 'lpo_quotations.supplier_id', '=', 'suppliers.id');
                $qb->where('lpo_quotations.supplier_id', $sup);
                $qb->orderBy('lpos.id', 'desc');
                $qb->select('lpos.*');
            }


        }



        if(array_key_exists('datatables', $input)){

            //searching
            //$qb->join('lpo_quotations', 'lpos.id', '=', 'lpo_quotations.lpo_id');
            //$qb->join('suppliers', 'lpo_quotations.supplier_id', '=', 'suppliers.id');
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('lpos.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.expense_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.expense_purpose','like', '\'%' . $input['search']['value']. '%\'');
                //$query->orWhere('suppliers.supplier_name','like','\'%' .$input['search']['value']. '%\'');

            });
            
            //    $qb->select(DB::raw('lpos.*, count(*) AS count', 'suppliers.supplier_name'));




            $sql = Lpo::bind_presql($qb->toSql(),$qb->getBindings());
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

                $qb->orderBy('lpos.'.$order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = Lpo::bind_presql($qb->toSql(),$qb->getBindings());
           // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , '\nSQL:: '.$sql , FILE_APPEND);
            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Lpo::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Lpo::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $lpo = Lpo::find($data[$key]['id']);

            $data[$key]['requested_by']             = $lpo->requested_by;
            $data[$key]['request_action_by']        = $lpo->request_action_by;
            $data[$key]['project']                  = $lpo->project;
            $data[$key]['account']                  = $lpo->account;
            $data[$key]['invoice']                  = $lpo->invoice;
            $data[$key]['status']                   = $lpo->status;
            $data[$key]['project_manager']          = $lpo->project_manager;
            $data[$key]['rejected_by']              = $lpo->rejected_by;
            $data[$key]['cancelled_by']             = $lpo->cancelled_by;
            $data[$key]['received_by']              = $lpo->received_by;
            $data[$key]['supplier']                 = $lpo->supplier;
            $data[$key]['currency']                 = $lpo->currency;
            $data[$key]['quotations']               = $lpo->quotations;
            $data[$key]['preffered_quotation']      = $lpo->preffered_quotation;
            $data[$key]['items']                    = $lpo->items;
            $data[$key]['terms']                    = $lpo->terms;
            $data[$key]['approvals']                = $lpo->approvals;
            $data[$key]['deliveries']               = $lpo->deliveries;
            $data[$key]['totals']                   = $lpo->totals;

            if($lpo->preffered_quotation_id > 0 && $lpo->preffered_quotation->supplier_id > 0 ){

                $data[$key]['preffered_quotation']['supplier']      = $lpo->preffered_quotation->supplier;

            }

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["account"]==null){
                $data[$key]["account"] = array("account_name"=>"N/A");
            }

            if($data[$key]["project_manager"]==null){
                $data[$key]["project_manager"] = array("full_name"=>"N/A");
            }

            if($data[$key]["received_by"]==null){
                $data[$key]["received_by"] = array("full_name"=>"N/A");
            }

            if($data[$key]["project"]==null){
                $data[$key]["project"] = array("Project_name"=>"N/A");
            }

            if($data[$key]["requested_by"]==null){
                $data[$key]["requested_by"] = array("full_name"=>"N/A");
            }


            if($data[$key]["cancelled_by"]==null){
                $data[$key]["cancelled_by"] = array("full_name"=>"N/A");
            }

            if($data[$key]["supplier"]==null){
                $data[$key]["supplier"] = array("supplier_name"=>"N/A");
            }

            if($data[$key]["status"]==null){
                $data[$key]["status"] = array("lpo_status"=>"N/A");
            }

            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
            if($data[$key]["preffered_quotation"]==null){
                $data[$key]["preffered_quotation"] = array("supplier"=>array("supplier_name"=>"N/A"));
            }
        }

        return $data;


    }









    public function next_status(){
        
    }
}
