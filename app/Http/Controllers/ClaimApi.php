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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyClaim;
use App\Models\ApprovalsModels\Approval;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use App\Exceptions\NotFullyAllocatedException;
use App\Exceptions\ApprovalException;
use PDF;
use App\Models\AllocationModels\Allocation;
use App\Models\PaymentModels\VoucherNumber;
use App\Models\StaffModels\Staff;
use Excel;

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
        $claim = new Claim;
        try{
            $form = Request::only(
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

            $claim->requested_by_id                   =   (int)       $form['requested_by_id'];
            $claim->expense_desc                      =               $form['expense_desc'];
            $claim->expense_purpose                   =               $form['expense_purpose'];
            $claim->project_manager_id                =   (int)       $form['project_manager_id'];
            $claim->payment_mode_id                   =   (int)       $form['payment_mode_id'];
            $claim->total                             =   (double)    $form['total'];
            $claim->total                             =   (double) $claim->calculated_withdrawal_charges + $claim->total;
            $claim->currency_id                       =   (int)       $form['currency_id'];           

            $claim->status_id                         =   $this->default_status;

            if($claim->save()) {
                $claim->disableLogging();

                // Add activity notification
                $this->addActivityNotification('Claim '.$claim->ref.' created', null, $this->current_user()->id, $claim->requested_by_id, 'info', 'claims', false);

                FTP::connection()->makeDir('/claims');
                FTP::connection()->makeDir('/claims/'.$claim->id);
                FTP::connection()->uploadFile($file->getPathname(), '/claims/'.$claim->id.'/'.$claim->id.'.'.$file->getClientOriginalExtension());

                $claim->claim_document           =   $claim->id.'.'.$file->getClientOriginalExtension();
                $claim->ref                        = "CHAI/CLM/#$claim->id/".date_format($claim->created_at,"Y/m/d");
                $claim->save();
                
                return Response()->json(array('success' => 'Claim Added','claim' => $claim), 200);
            }
        }
        catch (JWTException $e){
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
        $form = Request::all();
        $file = $form['file'];

        $claim = Claim::find($form['id']);

            $claim->requested_by_id                   =   (int)       $form['requested_by_id'];
            $claim->expense_desc                      =               $form['expense_desc'];
            $claim->expense_purpose                   =               $form['expense_purpose'];
            $claim->project_manager_id                =   (int)       $form['project_manager_id'];
            $claim->payment_mode_id                   =   (int)       $form['payment_mode_id'];
            $claim->total                             =   (double)    $form['total'];
            $claim->currency_id                       =   (int)       $form['currency_id'];   

        if($claim->save()) {
            if($file != 0){
                FTP::connection()->makeDir('/claims');
                FTP::connection()->makeDir('/claims/'.$claim->id);
                FTP::connection()->uploadFile($file->getPathname(), '/claims/'.$claim->id.'/'.$claim->id.'.'.$file->getClientOriginalExtension());
            }

            return Response()->json(array('msg' => 'Success: Claim updated','claim' => $claim), 200);
        }
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
        $deleted = Claim::destroy($claim_id);

        if($deleted){
            // Delete the allocations too
            Allocation::where('allocatable_id', $claim_id)->where('allocatable_type', 'claims')->delete();
            return response()->json(['msg'=>"Claim deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Claim not found"], 404,array(),JSON_PRETTY_PRINT);
        }
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
                                        'payment_mode',
                                        'currency',
                                        'rejected_by',
                                        'approvals.approver','approvals.approval_level',
                                        'logs.causer',
                                        'vouchers',
                                        'payments.payment_mode','payments.currency','payments.payment_batch',
                                        'allocations.project','allocations.account','allocations.objective','allocations.program_activity'
                                    )->findOrFail($claim_id);

            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500, array(), JSON_PRETTY_PRINT);
        }
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
    public function approveClaim($claim_id, $several=null)
    {
        $claim = [];

        $user = JWTAuth::parseToken()->authenticate();

        try{
            $claim   = Claim::with( 
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
                                    )->findOrFail($claim_id);
            
            if (!$user->can("APPROVE_CLAIM_".$claim->status_id)){
                throw new ApprovalException("No approval permission");             
            }
            $approvable_status  = $claim->status;
            $claim->status_id = $this->getNextStatusId($claim->status_id);

            $claim->disableLogging();
            if($claim->save()) {
                $claim   = Claim::with( 
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
                                    )->findOrFail($claim_id);

                $approval = new Approval;

                $approval->approvable_id            =   (int)   $claim->id;
                $approval->approvable_type          =   "claims";
                $approval->approval_level_id        =   $approvable_status->approval_level_id;
                $approval->approver_id              =   (int)   $user->id;
                $approval->disableLogging();

                // Logging
                activity()
                   ->performedOn($claim)
                   ->causedBy($user)
                   ->log('approved');
                   
                // Add activity notification
                $this->addActivityNotification('Claim '.$claim->ref.' approved', null, $this->current_user()->id, $claim->requested_by_id, 'success', 'claims', false);

                $approval->save();


                if($approval->approval_level_id==4){

                    $payable    =   array(
                        'payable_type'                  =>  'claims', 
                        'payable_id'                    =>  $claim->id, 
                        'debit_bank_account_id'         =>  $claim->currency_id, 
                        'currency_id'                   =>  $claim->currency_id, 
                        'payment_desc'                  =>  $claim->expense_desc, 
                        'paid_to_name'                  =>  $claim->requested_by->full_name, 
                        'paid_to_mobile_phone_no'       =>  $claim->requested_by->mpesa_no, 
                        'paid_to_bank_account_no'       =>  $claim->requested_by->bank_account, 
                        'paid_to_bank_id'               =>  $claim->requested_by->bank_id, 
                        'paid_to_bank_branch_id'        =>  $claim->requested_by->bank_branch_id, 
                        'payment_mode_id'               =>  $claim->payment_mode->id, 
                        'amount'                        =>  $claim->total, 
                        'payment_batch_id'              =>  "", 
                        'bank_charges'                  =>  ""
                    );
                    
                    $this->generate_payable_payment($payable);
                }                

                Mail::queue(new NotifyClaim($claim));

                if($several!=true)
                return Response()->json(array('msg' => 'Success: claim approved','claim' => $claim), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500, array(), JSON_PRETTY_PRINT);
        }
    }






    public function getNextStatusId($current_status_id){
        $current_status = ClaimStatus::find($current_status_id);

        if($current_status->next_status->skippable == 1){
            if($current_status->next_status_id == 3){    // FM
                $fm_exists = Staff::whereHas('roles', function($query){
                    $query->where('role_id', 5);  
                })->exists();

                if(!$fm_exists){
                    return $this->getNextStatusId($current_status->next_status_id);
                }
                else {
                    return $current_status->next_status_id;
                }
            }
            elseif($current_status->next_status_id == 10){    // Accountant
                $acc_exists = Staff::whereHas('roles', function($query){
                    $query->where('role_id', 8);  
                })->exists();

                if(!$acc_exists){
                    return $this->getNextStatusId($current_status->next_status_id);
                }
                else {
                    return $current_status->next_status_id;
                }
            }
            elseif($current_status->next_status_id == 12){    // FR
                $acc_exists = Staff::whereHas('roles', function($query){
                    $query->where('role_id', 13);  
                })->exists();

                if(!$acc_exists){
                    return $this->getNextStatusId($current_status->next_status_id);
                }
                else {
                    return $current_status->next_status_id;
                }
            }
            else {
                return $current_status->next_status_id;
            }
        }
        else {
            return $current_status->next_status_id;
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
        $user = JWTAuth::parseToken()->authenticate();

        try{
            $claim   = Claim::with( 
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
                                    )->findOrFail($claim_id);

            if (!$user->can("APPROVE_CLAIM_".$claim->status_id)){
                throw new ApprovalException("No approval permission");             
            }
           
            $claim->status_id = 9;
            $claim->rejected_by_id            =   (int)   $user->id;
            $claim->rejected_at              =   date('Y-m-d H:i:s');
            $claim->rejection_reason             =   $form['rejection_reason'];

            $claim->disableLogging();
            if($claim->save()) {

                // Logging
                $claim->enableLogging();
                activity()
                   ->performedOn($claim)
                   ->causedBy($user)
                   ->log('rejected');
                   
                // Add activity notification
                $this->addActivityNotification('Claim '.$claim->ref.' returned', null, $this->current_user()->id, $claim->requested_by_id, 'danger', 'claims', false);

                Mail::queue(new NotifyClaim($claim));

                return Response()->json(array('msg' => 'Success: claim approved','claim' => $claim), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
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
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }
        catch (Exception $e ){
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
    public function getPaymentVoucherById($claim_id)
    {

        try{
            $claim = Claim::with('requested_by')->findOrFail($claim_id);
            $payment = Payment::with('voucher_number')->where('payable_id', $claim->id)->where('payable_type', 'claims')->first();
            $voucher_date = '-';
            $vendor = '-';
            $voucher_no = '-';

            if(!empty($payment->voucher_number)){
                $voucher = VoucherNumber::where('payable_id', $payment->id)->first();
                $voucher_no = $voucher->voucher_number;
            }
            else {
                if(empty($claim->migration_id)) $voucher_no = '-';
                else $voucher_no = 'CHAI'.$this->pad_zeros(5, $claim->migration_id);
            }

            if(!empty($payment->payment_batch_id) && $payment->payment_batch_id > 0){
                $batch = PaymentBatch::find($payment->payment_batch_id);
                $voucher_date = $batch->created_at;
            }

            $vendor = $claim->requested_by->full_name;
            $unique_approvals = $this->unique_multidim_array($claim->approvals, 'approval_level_id');
            $data = [
                    'payable'   => $claim,
                    'voucher_date' => $voucher_date,
                    'vendor'=>$vendor,
                    'voucher_no'=>$voucher_no,
                    'payable_type'=>'Claim',
                    'unique_approvals' => $unique_approvals,
                    'bank_transactions' => $claim->bank_transactions,
                    'payment' => $payment
                ];

            $pdf            = PDF::loadView('pdf/payment_voucher', $data);
            $file_contents  = $pdf->stream();
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
        catch (Exception $e ){
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
        $user = JWTAuth::parseToken()->authenticate();

        try{
            $claim   = Claim::with( 
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
                                    )->findOrFail($claim_id);

           

           if (($claim->total - $claim->amount_allocated) > 1 ){ //allowance of 1
                throw new NotFullyAllocatedException("This claim has not been fully allocated");
           }

            if($claim->status_id  == 1){ // Only set request time if its not after corrections
                
                $claim->requested_at = date('Y-m-d H:i:s');

                // Logging submission
                activity()
                   ->performedOn($claim)
                   ->causedBy($user)
                   ->log('Submitted for approval');
            }
            else{                
                // Logging resubmission
                activity()
                   ->performedOn($claim)
                   ->causedBy($user)
                   ->log('Re-submitted for approval');
            }
            $claim->status_id = $this->getNextStatusId($claim->status_id);
            $claim->disableLogging(); //! Do not log the update

            if($claim->save()) {
                
                // Add activity notification
                $this->addActivityNotification('Claim '.$claim->ref.' submitted', null, $this->current_user()->id, $claim->requested_by_id, 'info', 'claims', false);

                Mail::queue(new NotifyClaim($claim));
                return Response()->json(array('msg' => 'Success: claim submitted','claim' => $claim), 200);
            }

        }catch(NotFullyAllocatedException $ae){

            $response =  ["error"=>"Claim not fully allocated"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500, array(), JSON_PRETTY_PRINT);
        }
    }
















    
    /**
     * Operation approveSeveralClaims
     *
     * Approve several Claims.
     *
     *
     * @return Http response
     */
    public function approveSeveralClaims()
    {
        try {
            $form = Request::only("claims");
            $claim_ids = $form['claims'];

            foreach ($claim_ids as $key => $claim_id) {
                $this->approveClaim($claim_id, true);
            }

            return response()->json(['claims'=>$form['claims']], 201, array(), JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
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
        $qb = Claim::query();
        if(!array_key_exists('lean', $input)){
            $qb = Claim::with('requested_by','project','status','project_manager','currency');
        }

        $qb = $qb->where(function($query){
            $query->whereNull('archived')->orWhere('archived', '!=', 1);
        });

        $total_records          = $qb->count();
        $records_filtered       = 0;

        if(array_key_exists('status', $input)){
            $status_ = (int) $input['status'];

            if($status_ >-1){
                $qb =  $qb->where('status_id', $input['status'])->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-1) {
                $qb = $qb->where('requested_by_id',$this->current_user()->id);
            }elseif ($status_==-2) {
                
            }elseif ($status_==-3) {
                $qb = $qb->where('project_manager_id',$this->current_user()->id);
            }
        }

        $app_stat = $this->approvable_statuses ;
        if(array_key_exists('approvable', $input)){
            $qb = $qb->where(function ($query) use ($app_stat) {                    
                foreach ($app_stat as $key => $value) {
                    $query->orWhere('status_id',$value['id']);
                }
            });
        }

        if(array_key_exists('my_approvables', $input)){
            $current_user = $this->current_user();
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
            }
        }

        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                $query->orWhere('expense_desc','like', '%' . $input['searchval']. '%');
                $query->orWhere('expense_purpose','like', '%' . $input['searchval']. '%');
            });
            $records_filtered = $qb->count();
        }

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
            if(!empty($input['search']['value'])){
                $qb = $qb->where(function ($query) use ($input) {
                    $query->orWhere('ref','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('expense_desc','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('expense_purpose','like', '%' . $input['search']['value']. '%');
                    $query->orWhereHas('requested_by', function ($query) use ($input) {
                        $query->where('f_name','like','%' .$input['search']['value']. '%');
                        $query->orWhere('l_name','like','%' .$input['search']['value']. '%');
                    });
                });
            }

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

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $qb = $qb->limit($input['length'])->offset($input['start']);
            }
            else{
                $qb = $qb->limit($input['length']);
            }

            $response = Claim::arr_to_dt_response( 
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


    public function markAsPaid(){
        try{
            $input = Request::all();

            if(empty($input['bank_ref']) && empty($input['amount'])){
                return response()->json(['error'=>'All fields are required'], 422);
            }

            $claim = Claim::findOrFail($input['claim_id']);
            $payment = Payment::where('payable_type', 'claims')->where('payable_id',$claim->id)->firstOrFail();
            $payment->disableLogging();
            $payment->status_id = 4;
            $payment->save();
            $voucher_no = $payment->voucher_number->voucher_number ?? '';

            $bank_trans = $claim->bank_transactions;
            $already_saved = false;
            foreach($bank_trans as $tran){
                if(trim($input['bank_ref']) == $tran->bank_ref) $already_saved = true;
            }
            if(!$already_saved){
                // Save transaction details
                $bank_transaction = array();
                $bank_transaction['bank_ref'] = trim($input['bank_ref']);
                $bank_transaction['chai_ref'] = $voucher_no;
                $bank_transaction['inputter'] = $this->current_user()->name;
                $bank_transaction['approver'] = 'N/A';
                $bank_transaction['amount'] = trim($input['amount']);
                $bank_transaction['txn_date'] =  date('Y-m-d');
                $bank_transaction['txn_time'] = date('H:m').'Hrs';
                $bank_transaction['narrative'] = substr($claim->expense_desc, 0, 300).'...';
                DB::table('bank_transactions')->insert($bank_transaction);
            }

            if($claim->status_id != 8){   // It was already marked as paid
                $claim->disableLogging();
                $claim->status_id = 8; //Paid
                $claim->save();
                activity()
                    ->performedOn($claim)
                    ->causedBy($this->current_user())
                    ->log('Paid');
                    
                return Response()->json(array('success' => 'Claim already marked as paid'), 200);
            }
            
            return Response()->json(array('success' => 'Claim marked as paid'), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Something went wrong during processing', 'msg'=>$e->getMessage()], 500);
        }
    }


    /**
     * Operation uploadAllocations
     * CSV file with allocations
     * @return Http response
     */
    public function uploadAllocations(){
        $form = Request::all();

        try{
            $file = $form['file'];
            $payable_type = $form['payable_type'];
            $payable_id = $form['payable_id'];            
            $user = JWTAuth::parseToken()->authenticate();
            $payable = null;
            $total = 0;

            $payable = Claim::find($payable_id);
            $total = $payable->total;
            $allocations_array = array();

            $handle = fopen($file->getRealPath(), "r");
            $skip_rows = 3;
            $skipped = 1;

            while ($csvLine = fgetcsv($handle, 1000, ",")) {
                if($csvLine[0] == 'update_data' && $csvLine[9] == 'KE'){
                    try{
                        $project = Project::where('project_code','like', '%'.trim($csvLine[1]).'%')->firstOrFail();
                        $account = Account::where('account_code', 'like', '%'.trim($csvLine[5]).'%')->firstOrFail();

                        $allocation = new Allocation();
                        $allocation->allocatable_id = $payable_id;
                        $allocation->allocatable_type = $payable_type;
                        $allocation->amount_allocated = trim($csvLine[8]);
                        $allocation->allocation_purpose = trim($csvLine[6]);
                        $allocation->percentage_allocated = (string) $this->getPercentage(trim($csvLine[8]), $total);
                        $allocation->allocated_by_id =  (int) $user->id;
                        $allocation->account_id =  $account->id;
                        $allocation->project_id = $project->id;
                        array_push($allocations_array, $allocation);

                    }
                    catch(\Exception $e){
                        $response =  ["error"=>'Account or Project not found. Please use form to allocate.',
                                        "msg"=>$e->getMessage()];
                        fclose($handle);
                        return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
                    }
                }
            }
            fclose($handle);

            foreach($allocations_array as $allocation){
                $allocation->save();
            }

            // Logging
            activity()
                ->performedOn($payable)
                ->causedBy($user)
                ->withProperties(['detail' => 'Claim allocations uploaded using CSV/Excel'])
                ->log('Uploaded allocations');
            return Response()->json(array('success' => 'allocations added','payable' => $payable), 200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()], 500);
        }
    }

    public function getPercentage($amount, $total){
        return ($amount / $total) * 100;
    }



    /**
     * Operation recallClaim
     * 
     * Recalls a claim.
     * 
     * @param int $claim_id Claim id to recall (required)
     * 
     * @return Http response
     */
    public function recallClaim($claim_id)
    {
        $claim = Claim::find($claim_id);        

        // Ensure claim is in the recallable statuses
        if(!in_array($claim->status_id, [2,3,4,10,12])){
            return response()->json(['msg'=>"you do not have permission to do this"], 403, array(), JSON_PRETTY_PRINT);
        }

        $claim->status_id = 11;     // Recalled

        // Logging recall
        activity()
            ->performedOn($claim)
            ->causedBy($this->current_user())
            ->log('Claim recalled');

        $claim->disableLogging(); //! Do not log the update        
        if($claim->save()){
            return response()->json(['msg'=>"claim recalled"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"could not recall claim"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
}
