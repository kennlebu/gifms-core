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

class MobilePaymentApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
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
            'attentendance_sheet',
            'payees_upload_mode_id',
            'rejection_reason',
            'rejected_by_id'
            );

        try{

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
            $mobile_payment->attentendance_sheet            =           $form['attentendance_sheet'];
            $mobile_payment->payees_upload_mode_id          =   (int)   $form['payees_upload_mode_id'];
            $mobile_payment->rejection_reason               =           $form['rejection_reason'];
            $mobile_payment->rejected_by_id                 =   (int)   $form['rejected_by_id'];


            $mobile_payment->status_id                      =   1 ;


            if($mobile_payment->save()) {

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
        $input = Request::all();

        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePayment');
        }
        
        $body = $input['body'];

        $mobile_payment = MobilePayment::find($body['id']);



        // $mobile_payment->meeting                            =   (int)   $body['meeting'];
        $mobile_payment->requested_date                     =           $body['requested_date'];
        $mobile_payment->requested_by_id                    =   (int)   $body['requested_by_id'];
        $mobile_payment->payment_desc                       =           $body['payment_desc'];
        $mobile_payment->payment_purpose                    =           $body['payment_purpose'];
        $mobile_payment->project_id                         =   (int)   $body['project_id'];
        // $mobile_payment->account_id                         =   (int)   $body['account_id'];
        $mobile_payment->mobile_payment_id                  =   (int)   $body['mobile_payment_id'];
        $mobile_payment->invoice_id                         =   (int)   $body['invoice_id'];
        $mobile_payment->expense_desc                       =           $body['expense_desc'];
        $mobile_payment->expense_purpose                    =           $body['expense_purpose'];
        $mobile_payment->payment_document                   =           $body['payment_document'];
        $mobile_payment->status_id                          =   (int)   $body['status_id'];
        $mobile_payment->project_manager_id                 =   (int)   $body['project_manager_id'];
        $mobile_payment->region_id                          =   (int)   $body['region_id'];
        $mobile_payment->county_id                          =   (int)   $body['county_id'];
        $mobile_payment->attendance_sheet                   =           $body['attendance_sheet'];
        $mobile_payment->rejection_reason                   =           $body['rejection_reason'];
        $mobile_payment->rejected_by_id                     =   (int)   $body['rejected_by_id'];



        if($mobile_payment->save()) {

            return Response()->json(array('msg' => 'Success: Mobile payment updated','mobile_payment' => $lpo), 200);
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
                                    'allocations'
                                )->findOrFail($mobile_payment_id);

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
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing approve as a PATCH method ?');
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
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing submitForApproval as a PATCH method ?');
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
                
            }




            // $total_records          = $qb->count();     //may need this
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
