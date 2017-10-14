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
use App\Models\InvoicesModels\Invoice;
use App\Models\InvoicesModels\InvoiceStatus;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use App\Models\LPOModels\Lpo;
use Anchu\Ftp\Facades\Ftp;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyInvoice;
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

class InvoiceApi extends Controller
{


    private $default_status = '';
    private $default_log_status = '';
    private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {
        $status = InvoiceStatus::where('default_status','1')->first();
        $log_status = InvoiceStatus::where('default_log_status','1')->first();
        $this->approvable_statuses = InvoiceStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
        $this->default_log_status = $log_status->id;
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
                'id',
                'raised_by_id',
                'received_by_id',
                'external_ref',
                'expense_desc',
                'expense_purpose',
                'invoice_date',
                'lpo_id',
                'supplier_id',
                'project_manager_id',
                'total',
                'currency_id',
                'file',
                'submission_type'
                );


            // FTP::connection()->changeDir('/lpos');

            $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            $file = $form['file'];




            $invoice_date = date('Y-m-d H:i:s', strtotime($form['invoice_date']));

            if($form['submission_type']=='full'){

                $invoice->received_by_id                    =   (int)       $form['raised_by_id'];//received_by_id must be =raised_by_id
                $invoice->raised_by_id                      =   (int)       $form['raised_by_id'];
                $invoice->external_ref                      =               $form['external_ref'];
                $invoice->expense_desc                      =               $form['expense_desc'];
                $invoice->expense_purpose                   =               $form['expense_purpose'];
                // $invoice->invoice_date                      =               $form['invoice_date'];
                $invoice->invoice_date                      =               $invoice_date;
                $invoice->lpo_id                            =   (int)       $form['lpo_id'];
                $invoice->supplier_id                       =   (int)       $form['supplier_id'];
                $invoice->project_manager_id                =   (int)       $form['project_manager_id'];
                $invoice->total                             =   (double)    $form['total'];
                $invoice->currency_id                       =   (int)       $form['currency_id'];
                $invoice->received_at                       =   date('Y-m-d H:i:s');
                $invoice->raised_at                         =   date('Y-m-d H:i:s');

                $invoice->status_id                         =   $this->default_status;

            }else if($form['submission_type']=='log'){

                $invoice->received_by_id                    =   (int)       $form['received_by_id'];
                $invoice->raised_by_id                      =   (int)       $form['raised_by_id'];
                $invoice->external_ref                      =               $form['external_ref'];
                // $invoice->invoice_date                      =               $form['invoice_date'];                
                $invoice->invoice_date                      =               $invoice_date;
                $invoice->lpo_id                            =   (int)       $form['lpo_id'];
                $invoice->supplier_id                       =   (int)       $form['supplier_id'];
                $invoice->total                             =   (double)    $form['total'];
                $invoice->currency_id                       =   (int)       $form['currency_id'];
                $invoice->received_at                       =   date('Y-m-d H:i:s');


                $invoice->status_id                         =   $this->default_log_status;
            }else if($form['submission_type']=='upload_logged'){


                $invoice = Invoice::with( 
                                        'raised_by',
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->find((int) $form['id']);

                $invoice->raised_by_id                      =   (int)       $form['raised_by_id'];
                $invoice->external_ref                      =               $form['external_ref'];
                $invoice->expense_desc                      =               $form['expense_desc'];
                $invoice->expense_purpose                   =               $form['expense_purpose'];
                // $invoice->invoice_date                      =               $form['invoice_date'];
                // $invoice->invoice_date                      =               $invoice_date;
                $invoice->lpo_id                            =   (int)       $form['lpo_id'];
                $invoice->supplier_id                       =   (int)       $form['supplier_id'];
                $invoice->project_manager_id                =   (int)       $form['project_manager_id'];
                $invoice->total                             =   (double)    $form['total'];
                $invoice->currency_id                       =   (int)       $form['currency_id'];
                $invoice->received_at                       =   date('Y-m-d H:i:s');
                $invoice->raised_at                         =   date('Y-m-d H:i:s');

                
                $invoice->status_id = $invoice->status->next_status_id;

            }


            if($invoice->save()) {

                if($form['submission_type']=='full'||$form['submission_type']=='upload_logged'){

                    FTP::connection()->makeDir('/invoices');
                    FTP::connection()->makeDir('/invoices/'.$invoice->id);
                    FTP::connection()->uploadFile($file->getPathname(), '/invoices/'.$invoice->id.'/'.$invoice->id.'.'.$file->getClientOriginalExtension());

                    $invoice->invoice_document           =   $invoice->id.'.'.$file->getClientOriginalExtension();
                    $invoice->ref                        = "CHAI/INV/#$invoice->id/".date_format($invoice->created_at,"Y/m/d");
                    $invoice->save();

                }else if($form['submission_type']=='log'){
                    $invoice->ref                        = "CHAI/INV/#$invoice->id/".date_format($invoice->created_at,"Y/m/d");
                    $invoice->save();

                    Mail::send(new NotifyInvoice($invoice));

                }
                
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

        // $input = Request::all();



        try{


            $form = Request::only(
                'id',
                'raised_by_id',
                'received_by_id',
                'expense_desc',
                'expense_purpose',
                // 'invoice_date',
                'lpo_id',
                'supplier_id',
                'project_manager_id',
                'total',
                'currency_id'
                // 'file',
                // 'submission_type'
                );


            // FTP::connection()->changeDir('/lpos');

            // $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            // $file = $form['file'];




            // $invoice_date = date('Y-m-d H:i:s', strtotime($form['invoice_date']));

            // if($form['submission_type']=='full'){

                // print_r($form);

                $invoice = Invoice::findOrFail($form['id']);

                // $invoice->received_by_id                    =   (int)       $form['raised_by_id'];//received_by_id must be =raised_by_id
                $invoice->raised_by_id                      =   (int)       $form['raised_by_id'];
                $invoice->expense_desc                      =               $form['expense_desc'];
                $invoice->expense_purpose                   =               $form['expense_purpose'];
                // $invoice->invoice_date                      =               $form['invoice_date'];
                // $invoice->invoice_date                      =               $invoice_date;
                $invoice->lpo_id                            =   (int)       $form['lpo_id'];
                $invoice->supplier_id                       =   (int)       $form['supplier_id'];
                $invoice->project_manager_id                =   (int)       $form['project_manager_id'];
                $invoice->total                             =   (double)    $form['total'];
                $invoice->currency_id                       =   (int)       $form['currency_id'];
                // $invoice->received_at                       =   date('Y-m-d H:i:s');
                // $invoice->raised_at                         =   date('Y-m-d H:i:s');

                $invoice->status_id                         =   $this->default_status;

            // }


            if($invoice->save()) {

                // if($form['submission_type']=='full'||$form['submission_type']=='upload_logged'){

                //     FTP::connection()->makeDir('/invoices');
                //     FTP::connection()->makeDir('/invoices/'.$invoice->id);
                //     FTP::connection()->uploadFile($file->getPathname(), '/invoices/'.$invoice->id.'/'.$invoice->id.'.'.$file->getClientOriginalExtension());

                //     $invoice->invoice_document           =   $invoice->id.'.'.$file->getClientOriginalExtension();
                //     $invoice->ref                        = "CHAI/INV/#$invoice->id/".date_format($invoice->created_at,"Y/m/d");
                //     $invoice->save();

                // }else if($form['submission_type']=='log'){
                //     $invoice->ref                        = "CHAI/INV/#$invoice->id/".date_format($invoice->created_at,"Y/m/d");
                //     $invoice->save();

                //     Mail::send(new NotifyInvoice($invoice));

                // }
                
                return Response()->json(array('success' => 'Invoice Added','invoice' => $invoice), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

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
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'payments',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->findOrFail($invoice_id);


            foreach ($response->allocations as $key => $value) {
                $project = Project::find((int)$value['project_id']);
                $account = Account::find((int)$value['account_id']);
                
                $response['allocations'][$key]['project']  =   $project;
                $response['allocations'][$key]['account']  =   $account;
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
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->findOrFail($invoice_id);

           
            $invoice->status_id = $invoice->status->next_status_id;

            if($invoice->save()) {

                $invoice   = Invoice::with( 
                                        'raised_by',
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->findOrFail($invoice_id);

                $approval = new Approval;

                $user = JWTAuth::parseToken()->authenticate();

                $approval->approvable_id            =   (int)   $invoice->id;
                $approval->approvable_type          =   "invoices";
                $approval->approval_level_id        =   $invoice->status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();

                Mail::send(new NotifyInvoice($invoice));

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

        $form = Request::only(
            'rejection_reason'
            );
        
        $invoice = [];

        try{
            $invoice   = Invoice::with( 
                                        'raised_by',
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->findOrFail($invoice_id);

           

            $invoice->status_id = 9;
            $user = JWTAuth::parseToken()->authenticate();
            $invoice->rejected_by_id            =   (int)   $user->id;
            $invoice->rejected_at                 =   date('Y-m-d H:i:s');
            $invoice->rejection_reason             =   $form['rejection_reason'];

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

            $path           = '/invoices/'.$invoice->id.'/'.$invoice->invoice_document;

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
     * Operation getPaymentVoucherById
     *
     * get payment Voucher by ID.
     *
     * @param int $invoice_id ID of invoice to return object (required)
     *
     * @return Http response
     */
    public function getPaymentVoucherById($invoice_id)
    {

        try{
            $invoice        = Invoice::findOrFail($invoice_id);

            //signatures

            foreach ($invoice->approvals as $key => $value) {                

                $path           = '/staff/'.$value->approver_id.'/signature/signature.png';

                $file_contents  = FTP::connection()->readFile($path);

                Storage::put('staff/signature'.$value->approver_id.'.png', $file_contents);

                $url            = storage_path("app/staff/signature".$value->approver_id.'.png');

                $file           = File::get($url);
            }


            $data           = array(
                                'invoice'   => $invoice
                                );

            $pdf            = PDF::loadView('pdf/invoice_payment_voucher', $data);

            $file_contents  = $pdf->stream();

            Storage::put('invoices/'.$invoice_id.'.voucher.temp', $file_contents);

            $url            = storage_path("app/invoices/".$invoice_id.'.voucher.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', 'application/pdf');

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
                                        'received_by',
                                        'raise_action_by',
                                        'status',
                                        'project_manager',
                                        'supplier',
                                        'currency',
                                        'lpo',
                                        'rejected_by',
                                        'approvals',
                                        'allocations',
                                        'vouchers',
                                        'comments'
                                    )->findOrFail($invoice_id);

           if (($invoice->total - $invoice->amount_allocated) > 1 ){ //allows error of 1
             throw new NotFullyAllocatedException("This invoice has not been fully allocated");
             
           }

            $invoice->status_id = $invoice->status->next_status_id;
            $invoice->raised_at = date('Y-m-d H:i:s');

            if($invoice->save()) {

                Mail::send(new NotifyInvoice($invoice));

                return Response()->json(array('msg' => 'Success: invoice submitted','invoice' => $invoice), 200);
            }

        }catch(NotFullyAllocatedException $ae){

            $response =  ["error"=>"Invoice not fully allocated"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
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
            $data[$key]['received_by']                  = $invoice->received_by;
            $data[$key]['raise_action_by']              = $invoice->raise_action_by;
            // $data[$key]['project']                      = $invoice->project;
            $data[$key]['status']                       = $invoice->status;
            $data[$key]['project_manager']              = $invoice->project_manager;
            $data[$key]['supplier']                     = $invoice->supplier;
            $data[$key]['currency']                     = $invoice->currency;
            $data[$key]['lpo']                          = $invoice->lpo;
            $data[$key]['rejected_by']                  = $invoice->rejected_by;
            $data[$key]['approvals']                    = $invoice->approvals;
            $data[$key]['allocations']                  = $invoice->allocations;
            $data[$key]['vouchers']                     = $invoice->vouchers;
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
            if($value["received_by"]==null){
                $data[$key]['received_by'] = array("full_name"=>"N/A");
                
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
            if($value["supplier"]==null){
                $data[$key]['supplier'] = array("supplier_name"=>"N/A");
                
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
