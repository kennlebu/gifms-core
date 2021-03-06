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
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyAdvance;
use App\Models\ApprovalsModels\Approval;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use App\Exceptions\ApprovalException;
use PDF;
use App\Models\PaymentModels\VoucherNumber;

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

            $file = $form['file'];

            $advance->requested_by_id                   =   (int)       $form['requested_by_id'];
            $advance->expense_desc                      =               $form['expense_desc'];
            $advance->expense_purpose                   =               $form['expense_purpose'];
            $advance->project_manager_id                =   (int)       $form['project_manager_id'];
            $advance->payment_mode_id                   =   (int)       $form['payment_mode_id'];
            $advance->total                             =   (double)    $form['total'];
            $advance->currency_id                       =   (int)       $form['currency_id'];           

            $advance->status_id                         =   $this->default_status;


            if($advance->save()) {
  
                // Add activity notification
                $this->addActivityNotification('Advance '.$advance->ref.' created', null, $this->current_user()->id, $advance->requested_by_id, 'info', 'advances', false);

                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->makeDir('/advances/'.$advance->id);
                FTP::connection()->uploadFile($file->getPathname(), '/advances/'.$advance->id.'/'.$advance->id.'.'.$file->getClientOriginalExtension());

                $advance->advance_document           =   $advance->id.'.'.$file->getClientOriginalExtension();
                $advance->ref                        = "CHAI/ADV/#$advance->id/".date_format($advance->created_at,"Y/m/d");
                $advance->disableLogging();
                $advance->save();
                
                return Response()->json(array('success' => 'Advance Added','advance' => $advance), 200);
            }
        }
        catch (JWTException $e){
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
                    $advance->advance_document = $advance->id.'.'.$file->getClientOriginalExtension();
                }

                return Response()->json(array('msg' => 'Success: Advance updated','advance' => $advance), 200);
            }
        }
        catch (JWTException $e){
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
        try{
            $response   = Advance::with(
                                    'requested_by',
                                    'status',
                                    'project_manager',
                                    'payment_mode',
                                    'currency',
                                    'rejected_by',
                                    'vouchers',
                                    'payments',
                                    'logs.causer',
                                    'logs.subject'
                                )->findOrFail($advance_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }
        catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500, array(), JSON_PRETTY_PRINT);
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
        try{
            $advance = Advance::findOrFail($advance_id);
           
            if (!$this->current_user()->can("APPROVE_ADVANCE_".$advance->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $approvable_status  = $advance->status;
            $advance->status_id = $advance->status->next_status_id;
            $advance->disableLogging();

            if($advance->save()) {
                $approval = new Approval;
                $approval->approvable_id            =   (int)   $advance->id;
                $approval->approvable_type          =   "advances";
                $approval->approval_level_id        =   $approvable_status->approval_level_id;
                $approval->approver_id              =   (int)   $this->current_user()->id;
                $approval->disableLogging();
                $approval->save();

                if($approval->approval_level_id ==4){

                    $payable    =   [
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
                    ];

                    $this->generate_payable_payment($payable);

                }
                
                // Add activity notification
                $this->addActivityNotification('Advance '.$advance->ref.' approved', null, $this->current_user()->id, $advance->requested_by_id, 'success', 'advances', false);

                Mail::queue(new NotifyAdvance($advance));

                if($several!=true)
                return Response()->json(array('msg' => 'Success: advance approved','advance' => $advance), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500);
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
        $form = Request::only('rejection_reason');

        $user = $this->current_user();

        try{
            $advance = Advance::findOrFail($advance_id);
           
            if (!$user->can("APPROVE_ADVANCE_".$advance->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $advance->status_id = 11;
            $advance->rejected_by_id            =   (int)   $user->id;
            $advance->rejected_at              =   date('Y-m-d H:i:s');
            $advance->rejection_reason             =   $form['rejection_reason'];
            $advance->disableLogging();
            if($advance->save()) {                
                // Add activity notification
                $this->addActivityNotification('Advance '.$advance->ref.' deleted', null, $this->current_user()->id, $advance->requested_by_id, 'info', 'accounts', false);

                Mail::queue(new NotifyAdvance($advance));
                return Response()->json(array('msg' => 'Success: advance approved','advance' => $advance), 200);
            }

        }catch(ApprovalException $ae){
            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403);
        }catch(Exception $e){
            $response =  ["error"=>"Advance could not be found"];
            return response()->json($response, 500);
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
    public function getPaymentVoucherById($advance_id)
    {

        try{
            $advance = Advance::with('requested_by')->findOrFail($advance_id);
            $payment = Payment::with('voucher_number')->where('payable_id', $advance->id)->where('payable_type', 'advances')->first();
            $voucher_date = '-';
            $vendor = '-';
            $voucher_no = '-';

            if(!empty($payment->voucher_number)){
                $voucher = VoucherNumber::where('payable_id', $payment->id)->first();
                $voucher_no = $voucher->voucher_number;
            }
            else {
                if(empty($advance->migration_id)) $voucher_no = '-';
                else $voucher_no = 'CHAI'.$this->pad_with_zeros(5, $advance->migration_id);
            }

            if(!empty($payment->payment_batch_id) && $payment->payment_batch_id > 0){
                $batch = PaymentBatch::find($payment->payment_batch_id);
                $voucher_date = $batch->created_at;
            }

            $vendor = $advance->requested_by->full_name;
            $unique_approvals = $this->unique_multidim_array($advance->approvals, 'approval_level_id');
            $data = [
                    'payable'   => $advance,
                    'voucher_date' => $voucher_date,
                    'vendor'=>$vendor,
                    'voucher_no'=>$voucher_no,
                    'payable_type'=>'Advance',
                    'unique_approvals' => $unique_approvals
                ];

            $pdf            = PDF::loadView('pdf/payment_voucher', $data);
            $file_contents  = $pdf->stream();
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
        catch (Exception $e ){
            $response = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
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
        try{
            $advance = Advance::findOrFail($advance_id);
           
            $advance->status_id = $advance->status->next_status_id;
            if($advance->status_id  != 11){ // Only set request time if its not after corrections
                $advance->requested_at = date('Y-m-d H:i:s');
            }

            if($advance->save()) {                
                Mail::queue(new NotifyAdvance($advance));
                return Response()->json(array('msg' => 'Success: advance submitted','advance' => $advance), 200);
            }
        }
        catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500);
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

            return response()->json(['advances'=>$form['advances']], 201);
            
        } catch (Exception $e) {
            return response()->json(['error'=>"An rerror occured during processing"], 500);
            
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
        $qb = Advance::query();
        if(!array_key_exists('lean', $input)){
            $qb = Advance::with('requested_by','status','project_manager','currency');
        }

        $qb = $qb->where(function($query){
            $query->whereNull('archived')->orWhere('archived', '!=', 1);
        });

        $total_records          = $qb->count();
        $records_filtered       = 0;

        if(array_key_exists('status', $input)){
            $status_ = (int) $input['status'];
            if($status_ >-1){
                $qb = $qb->where('status_id', $input['status'])->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb = $qb->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-3) {
                $qb = $qb->where('project_manager_id',$this->current_user()->id);
            }
        }


        
        $app_stat = $this->approvable_statuses;
        if(array_key_exists('approvable', $input)){
            $qb = $qb->where(function ($query) use ($app_stat) {
                foreach ($app_stat as $key => $value) {
                    $query->orWhere('status_id',$value['id']);
                }
            });
        }

        if(array_key_exists('my_approvables', $input)){
            $current_user =  $this->current_user();
            if($current_user->hasRole([
                'super-admin',
                'admin',
                'director',
                'associate-director',
                'financial-controller',
                'program-manager', 
                'accountant', 
                'assistant-accountant', 
                'financial-reviewer']
            )){                   
                $qb = $qb->where(function ($query) use ($app_stat,$current_user) {
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
            }
        }

        //searching
        if(array_key_exists('searchval', $input)){
            if(!empty($input['search']['value'])){
                $qb = $qb->where(function ($query) use ($input) {
                    $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('expense_desc','like', '%' . $input['searchval']. '%');
                    $query->orWhere('expense_purpose','like', '%' . $input['searchval']. '%');
                    $query->orWhereHas('requested_by', function ($query) use ($input) {
                        $query->where('f_name','like','%' .$input['searchval']. '%');
                        $query->orWhere('l_name','like','%' .$input['searchval']. '%');
                    });
                });
            }

            $records_filtered = $qb->count();
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb = $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb = $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb = $qb->where(function ($query) use ($input) {                
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                $query->orWhere('expense_desc','like', '%' . $input['search']['value']. '%');
                $query->orWhere('expense_purpose','like', '%' . $input['search']['value']. '%');
                $query->orWhereHas('requested_by', function ($query) use ($input) {
                    $query->where('f_name','like','%' .$input['search']['value']. '%');
                    $query->orWhere('l_name','like','%' .$input['search']['value']. '%');
                });
            });

            foreach($input['columns'] as $column){
                if(!empty($column['search']['value']) && !empty($column['name'])){

                    if($column['name'] == 'requested_by' || $column['name'] == 'project_manager'){
                        $qb = $qb->where(function ($query) use ($column) {                
                            $query->whereHas($column['name'], function ($query) use ($column) {
                                $query->where('f_name','like','%' .$column['search']['value']. '%');
                                $query->orWhere('l_name','like','%' .$column['search']['value']. '%');
                            });
                        });
                    }
                    else {
                        $qb = $qb->where(function ($query) use ($column) {      
                            $like = (!empty($column['filter']) && $column['filter'] == 'absolute') ? '' : '%';        
                            $query->where($column['name'],'like', $like . $column['search']['value']. $like);
                        });
                    }
                    
                }
            }

            $records_filtered = $qb->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb = $qb->orderBy($order_column_name, $order_direction);
            }

            //limit offset
            if((int)$input['start']!= 0 ){
                $qb = $qb->limit($input['length'])->offset($input['start']);
            }
            else{
                $qb = $qb->limit($input['length']);
            }
            $response = Advance::arr_to_dt_response( 
                $qb->get(), $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $qb->get();
        }

        return response()->json($response, 200);
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
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }
        catch (Exception $e ){
            $response = Response::make("", 500);
            $response->header('Content-Type', 'application/pdf');

            return $response;
        }
    }
}
