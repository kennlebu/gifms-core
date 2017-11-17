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
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\MobilePaymentModels\MobilePaymentStatus;
use App\Models\MobilePaymentModels\MobilePaymentPayee;
use App\Models\ProjectsModels\Project;
use App\Models\AccountingModels\Account;
use Exception;
use PDF;
use Excel;
use App;
use JWTAuth;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMobilePayment;
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

class MobilePaymentApi extends Controller
{


    private $default_status = '';
    private $approvable_statuses = [];
    /**
     * Constructor
     */
    public function __construct()
    {
        $status = MobilePaymentStatus::where('default_status','1')->first();
        $this->approvable_statuses = MobilePaymentStatus::where('approvable','1')->get();
        $this->default_status = $status->id;
    }
















    /**
     * Operation addMobilePayment
     *
     * Add a new mobile_payment.
     *
     *
     * @return Http response
     */
    public function addMobilePayment()
    {
        $input = Request::all();

        try{


            $form = Request::only(
                'requested_by_id',
                'request_action_by_id',
                'project_id',
                'account_id',
                'mobile_payment_type_id',
                'expense_desc',
                'expense_purpose',
                'payment_document',
                'status_id',
                'project_manager_id',
                'region_id',
                'county_id',
                'attendance_sheet',
                'payees_upload_mode_id',
                'rejection_reason',
                'file',
                'rejected_by_id'
                );


            $ftp = FTP::connection()->getDirListing();


            $file = $form['file'];


            $mobile_payment = new MobilePayment;

            $mobile_payment->requested_by_id                =   (int)   $form['requested_by_id'];
            $mobile_payment->request_action_by_id           =   (int)   $form['request_action_by_id'];
            $mobile_payment->project_id                     =   (int)   $form['project_id'];
            // $mobile_payment->account_id                     =   (int)   $form['account_id'];
            $mobile_payment->mobile_payment_type_id         =   (int)   $form['mobile_payment_type_id'];
            $mobile_payment->expense_desc                   =           $form['expense_desc'];
            $mobile_payment->expense_purpose                =           $form['expense_purpose'];
            $mobile_payment->payment_document               =   (int)   $form['payment_document'];
            $mobile_payment->status_id                      =   (int)   $form['status_id'];
            $mobile_payment->project_manager_id             =   (int)   $form['project_manager_id'];
            $mobile_payment->region_id                      =   (int)   $form['region_id'];
            $mobile_payment->county_id                      =   (int)   $form['county_id'];
            $mobile_payment->attendance_sheet               =           $form['attendance_sheet'];
            $mobile_payment->payees_upload_mode_id          =   (int)   $form['payees_upload_mode_id'];
            $mobile_payment->rejection_reason               =           $form['rejection_reason'];
            $mobile_payment->rejected_by_id                 =   (int)   $form['rejected_by_id'];


            $mobile_payment->status_id                      =   $this->default_status;

            
            $user = JWTAuth::parseToken()->authenticate();
            $mobile_payment->request_action_by_id            =   (int)   $user->id;


            if($mobile_payment->save()) {

                FTP::connection()->makeDir('/mobile_payments');
                FTP::connection()->makeDir('/mobile_payments/'.$mobile_payment->id);
                FTP::connection()->makeDir('/mobile_payments/'.$mobile_payment->id.'/signsheet');
                FTP::connection()->uploadFile($file->getPathname(), '/mobile_payments/'.$mobile_payment->id.'/signsheet/'.$mobile_payment->id.'.'.$file->getClientOriginalExtension());

                $mobile_payment->attendance_sheet           =   $mobile_payment->id.'.'.$file->getClientOriginalExtension();

                $mobile_payment->ref = "CHAI/MPYMT/#$mobile_payment->id/".date_format($mobile_payment->created_at,"Y/m/d");
                $mobile_payment->save();

                return Response()->json(array('msg' => 'Success: mobile payment added','mobile_payment' => $mobile_payment), 200);
            }

        }catch (JWTException $e){

            return response()->json(['error'=>'something went wrong'], 500);

        }
    }























    /**
     * Operation updateMobilePayment
     *
     * Update an existing mobile_payment.
     *
     *
     * @return Http response
     */
    public function updateMobilePayment()
    {
        $form = Request::only(
            'id',
            'requested_by_id',
            'request_action_by_id',
            'project_id',
            'account_id',
            'mobile_payment_type_id',
            'expense_desc',
            'expense_purpose',
            'payment_document',
            'status_id',
            'project_manager_id',
            'region_id',
            'county_id',
            'attendance_sheet',
            'payees_upload_mode_id',
            'rejection_reason',
            'rejected_by_id'
        );

        // if (!isset($input['body'])) {
        //     throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePayment');
        // }
        
        // $body = $input['body'];

        $mobile_payment = MobilePayment::find($form['id']);



       
            $mobile_payment->requested_by_id                =   (int)   $form['requested_by_id'];
            $mobile_payment->request_action_by_id           =   (int)   $form['request_action_by_id'];
            $mobile_payment->project_id                     =   (int)   $form['project_id'];
            // $mobile_payment->account_id                     =   (int)   $form['account_id'];
            $mobile_payment->mobile_payment_type_id         =   (int)   $form['mobile_payment_type_id'];
            $mobile_payment->expense_desc                   =           $form['expense_desc'];
            $mobile_payment->expense_purpose                =           $form['expense_purpose'];
            $mobile_payment->payment_document               =   (int)   $form['payment_document'];
            $mobile_payment->status_id                      =   (int)   $form['status_id'];
            $mobile_payment->project_manager_id             =   (int)   $form['project_manager_id'];
            $mobile_payment->region_id                      =   (int)   $form['region_id'];
            $mobile_payment->county_id                      =   (int)   $form['county_id'];
            $mobile_payment->attendance_sheet               =           $form['attendance_sheet'];
            $mobile_payment->payees_upload_mode_id          =   (int)   $form['payees_upload_mode_id'];
            $mobile_payment->rejection_reason               =           $form['rejection_reason'];
            $mobile_payment->rejected_by_id                 =   (int)   $form['rejected_by_id'];



        if($mobile_payment->save()) {

            return Response()->json(array('msg' => 'Success: Mobile payment updated','mobile_payment' => $mobile_payment), 200);
        }
    }


















    /**
     * Operation getMobilePaymentById
     *
     * Find mobile_payment by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentById($mobile_payment_id)
    {
        $response = [];

        $user = JWTAuth::parseToken()->authenticate();

        try{
            $response   = MobilePayment::with(
                                    'requested_by',
                                    'requested_action_by',
                                    'project',
                                    'account',
                                    'mobile_payment_type',
                                    'invoice',
                                    'status',
                                    'project_manager',
                                    'region',
                                    'county',
                                    'currency',
                                    'rejected_by',
                                    'payees_upload_mode',
                                    'payees',
                                    'approvals',
                                    'logs',
                                    'vouchers',
                                    'payments',
                                    'allocations'
                                )->findOrFail($mobile_payment_id);


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

            $response =  ["error"=>"Mobile Payment could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }




















    /**
     * Operation approve
     *
     * Submit/Approve mobile_payment by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function approve($mobile_payment_id)
    {

        $response = [];

        $user = JWTAuth::parseToken()->authenticate();

        try{
            $mobile_payment   = MobilePayment::with(
                                    'requested_by',
                                    'requested_action_by',
                                    'project',
                                    'account',
                                    'mobile_payment_type',
                                    'invoice',
                                    'status',
                                    'project_manager',
                                    'region',
                                    'county',
                                    'currency',
                                    'rejected_by',
                                    'payees_upload_mode',
                                    'payees',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($mobile_payment_id);

            if (!$user->can("APPROVE_MOBILE_PAYMENT_".$mobile_payment->status_id)){
                throw new ApprovalException("No approval permission");             
            }
           
            $mobile_payment->status_id = $mobile_payment->status->next_status_id;


            if($mobile_payment->save()) {

                $mobile_payment   = MobilePayment::with(
                                    'requested_by',
                                    'requested_action_by',
                                    'project',
                                    'account',
                                    'mobile_payment_type',
                                    'invoice',
                                    'status',
                                    'project_manager',
                                    'region',
                                    'county',
                                    'currency',
                                    'rejected_by',
                                    'payees_upload_mode',
                                    'payees',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($mobile_payment_id);

                $approval = new Approval;


                $approval->approvable_id            =   (int)   $mobile_payment->id;
                $approval->approvable_type          =   "mobile_payments";
                $approval->approval_level_id        =   $mobile_payment->status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;

                $approval->save();
                if($mobile_payment->status_id!=4){                        
                    Mail::send(new NotifyMobilePayment($mobile_payment));
                }

                return Response()->json(array('msg' => 'Success: mobile_payment approved','mobile_payment' => $mobile_payment), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Mobile Payment could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }




















    /**
     * Operation reject
     *
     * Submit/Approve mobile_payment by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function reject($mobile_payment_id)
    {

        $form = Request::only(
            'rejection_reason'
            );

        $response = [];
        $user = JWTAuth::parseToken()->authenticate();

        try{
            $mobile_payment   = MobilePayment::with(
                                    'requested_by',
                                    'requested_action_by',
                                    'project',
                                    'account',
                                    'mobile_payment_type',
                                    'invoice',
                                    'status',
                                    'project_manager',
                                    'region',
                                    'county',
                                    'currency',
                                    'rejected_by',
                                    'payees_upload_mode',
                                    'payees',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($mobile_payment_id);

            if (!$user->can("APPROVE_MOBILE_PAYMENT_".$mobile_payment->status_id)){
                throw new ApprovalException("No approval permission");             
            }
           
            $mobile_payment->status_id = 7;
            $mobile_payment->rejected_by_id            =   (int)   $user->id;
            $mobile_payment->rejected_at              =   date('Y-m-d H:i:s');
            $mobile_payment->rejection_reason             =   $form['rejection_reason'];

            if($mobile_payment->save()) {

                Mail::send(new NotifyMobilePayment($mobile_payment));

                return Response()->json(array('msg' => 'Success: mobile_payment approved','mobile_payment' => $mobile_payment), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Mobile Payment could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


































    /**
     * Operation postPayees
     *
     * post mobile_payment payees in acsv by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function postPayees($mobile_payment_id)
    {
        // $input = Request::all();

        //path params validation


        //not path params validation

        try{

            $form = Request::only('file');

            $file = $form['file'];

            $ftp = FTP::connection()->getDirListing();


            $mobile_payment = MobilePayment::find($mobile_payment_id);

            // $contents = File::get($file->getPathname());
            // $data = str_getcsv ($contents,",");

            // print_r($data);


            $data = Excel::load($file->getPathname(), function($reader) {

            })->get()->toArray();


            // print_r($data);
        

            foreach ($data as $key => $value) {
                $payee = new MobilePaymentPayee();

                $payee->mobile_payment_id   = $mobile_payment_id;
                $payee->full_name           = $value['name'];
                $payee->registered_name     = $value['name'];
                $payee->amount              = $value['amount'];
                $payee->mobile_number       = $value['phone'];
                $payee->withdrawal_charges  = $payee->calculated_withdrawal_charges;
                $payee->total               = $payee->calculated_total;

                $payee->save();
            }



            return Response()->json(array('msg' => 'Success: mobile_payment payees uploaded','mobile_payment' => $mobile_payment), 200);
        

        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }


    }







































    /**
     * Operation allocateMobilePayment
     *
     * Allocate mobile_payment by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function allocateMobilePayment($mobile_payment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing allocateMobilePayment as a PATCH method ?');
    }
























    /**
     * Operation getDocumentById
     *
     * get mobile_payment document by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($mobile_payment_id)
    {
        
        try{
            $mobile_payment   = MobilePayment::findOrFail($mobile_payment_id);

            $data = array(
                'mobile_payment'   => $mobile_payment
                );

        // return view('pdf/mobile_payment',$data);

            $pdf = PDF::loadView('pdf/mobile_payment', $data);

            $file_contents  = $pdf->stream();

            Storage::put('mobile_payment/'.$mobile_payment_id.'.temp', $file_contents);

            $url       = storage_path("app/mobile_payment/".$mobile_payment_id.'.temp');

            $file = File::get($url);

            $response = Response::make($file, 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;
        }catch (Exception $e ){            

            $response       = Response::make("", 500);

            $response->header('Content-Type', 'application/pdf');

            return $response;  

        }
    }



















    /**
     * Operation getAttendanceSheetById
     *
     * get mobile_payment attendance sheet by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function getAttendanceSheetById($mobile_payment_id)
    {

        try{


            $mobile_payment      = MobilePayment::findOrFail($mobile_payment_id);

            $path           = '/mobile_payments/'.$mobile_payment->id.'/signsheet/'.$mobile_payment->attendance_sheet;

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('signsheets/'.$mobile_payment->id.'.temp', $file_contents);

            $url            = storage_path("app/signsheets/".$mobile_payment->id.'.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make(array('error'=>$e), 500);

            // $response->header('Content-Type', 'application/pdf');

            return $response;  

        }
    }




















    /**
     * Operation deleteMobilePayment
     *
     * Deletes an mobile_payment.
     *
     * @param int $mobile_payment_id mobile_payment id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePayment($mobile_payment_id)
    {
        $input = Request::all();

        $deleted = MobilePayment::destroy($mobile_payment_id);

        if($deleted){
            return response()->json(['msg'=>"Mobile Payment deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Mobile Payment not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }























    /**
     * Operation submitForApproval
     *
     * Submit mobile_payment by ID.
     *
     * @param int $mobile_payment_id ID of mobile_payment to return object (required)
     *
     * @return Http response
     */
    public function submitForApproval($mobile_payment_id)
    {


        $response = [];

        try{
            $mobile_payment   = MobilePayment::with(
                                    'requested_by',
                                    'requested_action_by',
                                    'project',
                                    'account',
                                    'mobile_payment_type',
                                    'invoice',
                                    'status',
                                    'project_manager',
                                    'region',
                                    'county',
                                    'currency',
                                    'rejected_by',
                                    'payees_upload_mode',
                                    'payees',
                                    'approvals',
                                    'allocations'
                                )->findOrFail($mobile_payment_id);

           

           if (($mobile_payment->total - $mobile_payment->amount_allocated) > 1 ){ //allowance of 1
             throw new NotFullyAllocatedException("This mobile payment has not been fully allocated");
             
           }
            $mobile_payment->status_id = $mobile_payment->status->next_status_id;
            $mobile_payment->requested_at = date('Y-m-d H:i:s');

            if($mobile_payment->save()) {

                Mail::send(new NotifyMobilePayment($mobile_payment));

                return Response()->json(array('msg' => 'Success: mobile_payment submitted','mobile_payment' => $mobile_payment), 200);
            }

        }catch(NotFullyAllocatedException $ae){

            $response =  ["error"=>"Mobile Payment not fully allocated"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Mobile Payment could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


































    /**
     * Operation approveSeveralMobilePayments
     *
     * Approve several Mobile Payments.
     *
     *
     * @return Http response
     */
    public function approveSeveralMobilePayments()
    {
        try {
            $form = Request::only("mobile_payments");
            $mobile_payment_ids = $form['mobile_payments'];

            foreach ($mobile_payment_ids as $key => $mobile_payment_id) {
                $this->approve($mobile_payment_id);
            }

            return response()->json(['mobile_payments'=>$form['mobile_payments']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
            
        }
    }























    /**
     * Operation getTemplate
     *
     * Mobile Payments Template.
     *
     *
     * @return Http response
     */
    public function getTemplate()
    {



        try{


            $invoice        = Invoice::findOrFail($invoice_id);

            $path           = '/templates/MPESA_TEMPLATE.xlsx';

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];


            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put($path , $file_contents);

            $url            = storage_path("app".$path);

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make("", 500);

            $response->header('Content-Type', 'application/vnd.ms-excel');

            return $response;  

        }
    }















    /**
     * Operation mobilePaymentsGet
     *
     * mobile_payments List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentsGet()
    {


        $input = Request::all();
        //query builder
        $qb = DB::table('mobile_payments');

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
                        $permission = 'APPROVE_MOBILE_PAYMENT_'.$value['id'];
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

            $sql = MobilePayment::bind_presql($qb->toSql(),$qb->getBindings());
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




            $sql = MobilePayment::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = MobilePayment::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = MobilePayment::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = MobilePayment::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }
























    public function append_relationships_objects($data = array()){

        // print_r($data);

        foreach ($data as $key => $value) {

            $mobile_payment =MobilePayment::find($data[$key]['id']);

            $data[$key]['requested_by']                = $mobile_payment->requested_by;
            $data[$key]['requested_action_by']         = $mobile_payment->requested_action_by;
            $data[$key]['project']                     = $mobile_payment->project;
            $data[$key]['account']                     = $mobile_payment->account;
            $data[$key]['mobile_payment_type']         = $mobile_payment->mobile_payment_type;
            $data[$key]['invoice']                     = $mobile_payment->invoice;
            $data[$key]['status']                      = $mobile_payment->status;
            $data[$key]['project_manager']             = $mobile_payment->project_manager;
            $data[$key]['region']                      = $mobile_payment->region;
            $data[$key]['county']                      = $mobile_payment->county;
            $data[$key]['currency']                    = $mobile_payment->currency;
            $data[$key]['rejected_by']                 = $mobile_payment->rejected_by;
            $data[$key]['payees_upload_mode']          = $mobile_payment->payees_upload_mode;
            $data[$key]['payees']                      = $mobile_payment->payees;
            $data[$key]['approvals']                   = $mobile_payment->approvals;
            $data[$key]['allocations']                 = $mobile_payment->allocations;
            $data[$key]['totals']                      = $mobile_payment->totals;

            foreach ($mobile_payment->allocations as $key1 => $value1) {
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
            if($value["requested_action_by"]==null){
                $data[$key]['requested_action_by'] = array("full_name"=>"N/A");
                
            }
            if($value["project"]==null){
                $data[$key]['project'] = array("project_name"=>"N/A");
                
            }
            if($value["account"]==null){
                $data[$key]['account'] = array("account_name"=>"N/A");
                
            }
            if($value["mobile_payment_type"]==null){
                $data[$key]['mobile_payment_type'] = array("desc"=>"N/A");
                
            }
            if($value["invoice"]==null){
                $data[$key]['invoice'] = array("invoice_title"=>"N/A");
                
            }
            if($value["status"]==null){
                $data[$key]['status'] = array("mobile_payment_status"=>"N/A");
                
            }
            if($value["project_manager"]==null){
                $data[$key]['project_manager'] = array("full_name"=>"N/A");
                
            }
            if($value["region"]==null){
                $data[$key]['region'] = array("region_name"=>"N/A");
                
            }
            if($value["county"]==null){
                $data[$key]['county'] = array("county_name"=>"N/A");
                
            }
            if($value["rejected_by"]==null){
                $data[$key]['rejected_by'] = array("full_name"=>"N/A");
                
            }
            if($value["payees_upload_mode"]==null){
                $data[$key]['payees_upload_mode'] = array("desc"=>"N/A");
                
            }
            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
        }

        return $data;


    }






}
