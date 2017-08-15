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

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicesModels\Invoice;
use App\Models\InvoicesModels\InvoiceStatus;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyInvoice;

class InvoiceApi extends Controller
{


    private $default_status = '';
    private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {
        $status = InvoiceStatus::where('default_status','1')->first();
        $this->approvable_statuses = InvoiceStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
    }

























    /**
     * Operation addInvoice
     *
     * Add a new invoice.
     *
     *
     * @return Http response
     */
    public function addInvoice()
    {

        // $input = Request::all();

        $invoice = new Invoice;


        try{


            $form = Request::only(
                'raised_by_id',
                'expense_desc',
                'expense_purpose',
                'invoice_date',
                'lpo_id',
                'supplier_id',
                'project_manager_id',
                'total',
                'currency_id',
                'file'
                );


            // FTP::connection()->changeDir('./lpos');

            $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            $file = $form['file'];


            $invoice->raised_by_id                      =   (int)       $form['raised_by_id'];
            $invoice->expense_desc                      =               $form['expense_desc'];
            $invoice->expense_purpose                   =               $form['expense_purpose'];
            $invoice->invoice_date                      =               $form['invoice_date'];
            $invoice->lpo_id                            =   (int)       $form['lpo_id'];
            $invoice->supplier_id                       =   (int)       $form['supplier_id'];
            $invoice->project_manager_id                =   (int)       $form['project_manager_id'];
            $invoice->total                             =   (double)    $form['total'];
            $invoice->currency_id                       =   (int)       $form['currency_id'];

            $invoice->status_id                         =   $this->default_status;


            if($invoice->save()) {

                FTP::connection()->makeDir('./invoices/'.$invoice->id);
                FTP::connection()->makeDir('./invoices/'.$invoice->id);
                FTP::connection()->uploadFile($file->getPathname(), './invoices/'.$invoice->id.'/'.$invoice->id.'.'.$file->getClientOriginalExtension());

                $invoice->invoice_document           =   $invoice->id.'.'.$file->getClientOriginalExtension();
                $invoice->ref                        = "CHAI/INV/#$invoice->id/".date_format($invoice->created_at,"Y/m/d");
                $invoice->save();
                
                return Response()->json(array('success' => 'Invoice Added','invoice' => $invoice), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }
























    /**
     * Operation updateInvoice
     *
     * Update an existing invoice.
     *
     *
     * @return Http response
     */
    public function updateInvoice()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateInvoice');
        }
        $body = $input['body'];


        return response('How about implementing updateInvoice as a PUT method ?');
    }
























    /**
     * Operation deleteInvoice
     *
     * Deletes an invoice.
     *
     * @param int $invoice_id invoice id to delete (required)
     *
     * @return Http response
     */
    public function deleteInvoice($invoice_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteInvoice as a DELETE method ?');
    }
























    /**
     * Operation getInvoiceById
     *
     * Find invoice by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function getInvoiceById($invoice_id)
    {
        $response = [];

        try{
            $response   = Invoice::with( 
                                        'raised_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'comments'
                                    )->findOrFail($invoice_id);


            foreach ($response->allocations as $key => $value) {
                $project = Project::find((int)$value['project_id']);
                $account = Account::find((int)$value['account_id']);
                $response['allocations'][$key]['project']  =   $project;
                $response['allocations'][$key]['account']  =   $account;
            }

            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"Invoice could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
























    /**
     * Operation allocateInvoice
     *
     * Allocate invoice by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function allocateInvoice($invoice_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing allocateInvoice as a PATCH method ?');
    }
























    /**
     * Operation approveInvoice
     *
     * Approve invoice by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function approveInvoice($invoice_id)
    {
        $invoice = [];

        try{
            $invoice   = Invoice::with( 
                                        'raised_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'comments'
                                    )->findOrFail($invoice_id);

           
            $invoice->status_id = $invoice->status->next_status_id;

            if($invoice->save()) {

                return Response()->json(array('msg' => 'Success: invoice approved','invoice' => $invoice), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Invoice could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }



















    /**
     * Operation rejectInvoice
     *
     * Reject invoice by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function rejectInvoice($invoice_id)
    {
        $invoice = [];

        try{
            $invoice   = Invoice::with( 
                                        'raised_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'comments'
                                    )->findOrFail($invoice_id);

           

            $invoice->status_id = 9;
            $user = JWTAuth::parseToken()->authenticate();
            $invoice->rejected_by_id            =   (int)   $user->id;

            if($invoice->save()) {

                Mail::send(new NotifyInvoice($invoice));

                return Response()->json(array('msg' => 'Success: invoice approved','invoice' => $invoice), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Invoice could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }






















    /**
     * Operation getDocumentById
     *
     * get invoice document by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($invoice_id)
    {

        try{


            $invoice        = Invoice::findOrFail($invoice_id);

            $path           = './invoices/'.$invoice->id.'/'.$invoice->invoice_document;

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('invoices/'.$invoice->id.'.temp', $file_contents);

            $url            = storage_path("app/invoices/".$invoice->id.'.temp');

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
     * Operation submitInvoiceForApproval
     *
     * Submit invoice by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function submitInvoiceForApproval($invoice_id)
    {

        $invoice = [];

        try{
            $invoice   = Invoice::with( 
                                        'raised_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'comments'
                                    )->findOrFail($invoice_id);

           
            $invoice->status_id = $invoice->status->next_status_id;

            if($invoice->save()) {

                return Response()->json(array('msg' => 'Success: invoice submitted','invoice' => $invoice), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"Invoice could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
























    /**
     * Operation getInvoices
     *
     * invoices List.
     *
     *
     * @return Http response
     */
    public function getInvoices()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('invoices');

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
                $qb->where('raised_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb->where('raised_by_id',$this->current_user()->id);
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
                
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_desc','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('expense_purpose','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Invoice::bind_presql($qb->toSql(),$qb->getBindings());
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




            $sql = Invoice::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Invoice::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Invoice::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Invoice::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


























    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $invoice = Invoice::find($data[$key]['id']);

            $data[$key]['raised_by']                    = $invoice->raised_by;
            $data[$key]['raise_action_by']              = $invoice->raise_action_by;
            // $data[$key]['project']                      = $invoice->project;
            $data[$key]['status']                       = $invoice->status;
            $data[$key]['project_manager']              = $invoice->project_manager;
            $data[$key]['currency']                     = $invoice->currency;
            $data[$key]['lpo']                          = $invoice->lpo;
            $data[$key]['rejected_by']                  = $invoice->rejected_by;
            $data[$key]['approvals']                    = $invoice->approvals;
            $data[$key]['allocations']                  = $invoice->allocations;
            $data[$key]['comments']                     = $invoice->comments;

            foreach ($invoice->allocations as $key1 => $value1) {
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


            if($value["raised_by"]==null){
                $data[$key]['raised_by'] = array("full_name"=>"N/A");
                
            }
            if($value["raise_action_by"]==null){
                $data[$key]['raise_action_by'] = array("full_name"=>"N/A");
                
            }
            // if($value["project"]==null){
            //     $data[$key]['project'] = array("project_name"=>"N/A");
                
            // }
            if($value["status"]==null){
                $data[$key]['status'] = array("invoice_status"=>"N/A");
                
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
