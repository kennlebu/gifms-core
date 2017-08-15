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
use Exception;
use PDF;
use App;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyLpo;
use App\Models\AllocationModels\Allocation;
use App\Models\ApprovalsModels\Approval;

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
            'project_manager_id'
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
        $input = Request::all();

        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpo');
        }

        $body = $input['body'];

        $lpo = Lpo::find($body['id']);




        $lpo->ref                                =           $body['ref'];
        // $lpo->lpo_date                           =           $body['lpo_date'];
        $lpo->supplier_id                        =   (int)   $body['supplier_id'];
        // $lpo->addressee                          =           $body['addressee'];
        $lpo->expense_desc                       =           $body['expense_desc'];
        $lpo->expense_purpose                    =           $body['expense_purpose'];
        $lpo->requested_by_id                    =   (int)   $body['requested_by_id'];
        $lpo->request_action_by_id               =   (int)   $body['request_action_by_id'];
        // $lpo->request_date                       =           $body['request_date'];
        $lpo->status_id                          =   (int)   $body['status_id'];
        $lpo->currency_id                        =   (int)   $body['currency_id'];
        $lpo->preffered_quotation_id             =   (int)   $body['preffered_quotation_id'];
        // $lpo->supply_category                    =   (int)   $body['supply_category'];
        $lpo->delivery_document                  =           $body['delivery_document'];
        $lpo->date_delivered                     =           $body['date_delivered'];
        $lpo->received_by_id                     =   (int)   $body['received_by_id'];
        // $lpo->meeting                            =   (int)   $body['meeting'];
        // $lpo->comments                           =           $body['comments'];
        // $lpo->preffered_supplier                 =   (int)   $body['preffered_supplier'];
        $lpo->preffered_supplier_id              =   (int)   $body['preffered_supplier_id'];
        $lpo->project_id                         =   (int)   $body['project_id'];
        $lpo->account_id                         =   (int)   $body['account_id'];
        // $lpo->attention                          =           $body['attention'];
        // $lpo->lpo_email                          =   (int)   $body['lpo_email'];
        $lpo->project_manager_id                 =   (int)   $body['project_manager_id'];
        $lpo->rejection_reason                   =           $body['rejection_reason'];
        $lpo->rejected_by_id                     =   (int)   $body['rejected_by_id'];
        $lpo->quote_exempt                       =   (int)   $body['quote_exempt'];
        $lpo->quote_exempt_explanation           =           $body['quote_exempt_explanation'];



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

        $deleted = lpo::destroy($lpo_id);

        if($deleted){
            return response()->json(['msg'=>"lpo deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"lpo not found"], 404,array(),JSON_PRETTY_PRINT);
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

        try{

            $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoice',
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
           
           
            $lpo->status_id = $lpo->status->next_status_id;

            if($lpo->save()) {

                $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoice',
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

                $user = JWTAuth::parseToken()->authenticate();

                $approval->approvable_id            =   (int)   $lpo->id;
                $approval->approvable_type          =   "lpos";
                $approval->approval_level_id        =   $lpo->status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();


                Mail::send(new NotifyLpo($lpo));

                return Response()->json(array('msg' => 'Success: lpo approved','lpo' => $lpo), 200);
            }

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

        try{

            $lpo   = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoice',
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
           
           
            $lpo->status_id = 12;
            $user = JWTAuth::parseToken()->authenticate();
            $lpo->rejected_by_id            =   (int)   $user->id;
            $lpo->requested_at              =   date('Y-m-d H:i:s');
            $lpo->rejection_reason             =   $form['rejection_reason'];

            if($lpo->save()) {

                
                Mail::send(new NotifyLpo($lpo));

                return Response()->json(array('msg' => 'Success: lpo rejected','lpo' => $lpo), 200);
            }

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
                                            'invoice',
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
           
           
            $lpo->status_id = $lpo->status->next_status_id;
            $lpo->request_date = date('Y-m-d H:i:s');

            if($lpo->save()) {


                Mail::send(new NotifyLpo($lpo));

                return Response()->json(array('msg' => 'Success: lpo submitted','lpo' => $lpo), 200);
            }

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
                                            'invoice',
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


                return Response()->json(array('msg' => 'Success','lpo' => $lpo), 200);

            }catch(Exception $e){

                $response =  ["error"=>"lpo could not be found"];
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

            $data = array(
                'lpo'   => $lpo
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






        //if status is set

        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $qb->where('lpos.status_id', $input['status']);
                $qb->where('lpos.requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb->where('lpos.requested_by_id',$this->current_user()->id);
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



        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('lpos.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.expense_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('lpos.expense_purpose','like', '\'%' . $input['search']['value']. '%\'');

            });




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

                $qb->orderBy($order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = Lpo::bind_presql($qb->toSql(),$qb->getBindings());

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
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
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
        }

        return $data;


    }









    public function next_status(){
        
    }
}
