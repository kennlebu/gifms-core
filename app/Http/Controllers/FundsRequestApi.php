<?php


namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use App\Models\FundsRequestModels\FundsRequest;
use App\Models\FundsRequestModels\FundsRequestStatus;
use App\Models\FundsRequestModels\FundsRequestItem;

class FundsRequestApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }



    /**
     * Funds Request Statuses
     */
    public function getFundsRequestStatuses()
    {
        $response;
        $response_dt;

        $total_records          = FundsRequestStatus::count();
        $records_filtered       = 0;
        $input = Request::all();
        $funds_request_status = FundsRequestStatus::query();

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $funds_request_status = $funds_request_status->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $funds_request_status = $funds_request_status->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $funds_request_status->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $funds_request_status = $funds_request_status->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $funds_request_status->limit($input['length'])->offset($input['start']);
            }
            else{
                $funds_request_status = $funds_request_status->limit($input['length']);
            }

            $response_dt = $funds_request_status->get();

            $response = FundsRequestStatus::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $funds_request_status->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addFundsRequestStatus()
    {
        $input = Request::all();

        $status = new FundsRequestStatus;
        $status->status = $input['status'];
        if(!empty($input['next_status_id'])) $status->next_status_id = $input['next_status_id'];
        if(!empty($input['short_name'])) $status->short_name = $input['short_name'];
        if(!empty($input['display_color'])) $status->display_color = $input['display_color'];
        if(!empty($input['approvable'])) $status->approvable = $input['approvable'];

        if($status->save()) {
            return Response()->json(array('msg' => 'Success: status added','status' => $status), 200);
        }
    }   
    
    public function updateFundsRequestStatus()
    {
        $input = Request::all();

        $status = FundsRequestStatus::find($input['id']);
        $status->status = $input['status'];
        if(!empty($input['next_status_id'])) $status->next_status_id = $input['next_status_id'];
        if(!empty($input['short_name'])) $status->short_name = $input['short_name'];
        if(!empty($input['display_color'])) $status->display_color = $input['display_color'];
        if(!empty($input['approvable'])) $status->approvable = $input['approvable'];

        if($status->save()) {
            return Response()->json(array('msg' => 'Success: status updated','status' => $status), 200);
        }
    }  
    
    public function deleteFundsRequestStatus($id)
    {
        $deleted = FundsRequestStatus::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"status deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"status not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getFundsRequestStatusById($id)
    {
        $input = Request::all();

        try{
            $response = FundsRequestStatus::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"status could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

















    /**
     * Funds Request
     */
    public function getFundsRequests()
    {
        $response;
        $response_dt;

        $total_records          = FundsRequest::count();
        $records_filtered       = 0;
        $user = JWTAuth::parseToken()->authenticate();

        $input = Request::all();        
        $funds_request = FundsRequest::with('status','requested_by','funds_request_items');

        // Status
        if(array_key_exists('status',$input)){
            $funds_request = $funds_request->where('status_id', $input['status']);
        }

        // Requested by
        if(array_key_exists('requested_by_id',$input)){
            $funds_request = $funds_request->where('requested_by_id', $input['requested_by_id']);
        }

        // My Requests
        if(array_key_exists('requested_by_id', $input)){
            $funds_request = $funds_request->where('requested_by_id', $user->id);
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $funds_request = $funds_request->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $funds_request = $funds_request->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            //searching
            // $funds_request = $funds_request->where(function ($query) use ($input) {                
            //     $query->orWhere('asset_name','like', '\'%' . $input['search']['value']. '%\'');
            //     $query->orWhere('cost','like', '\'%' . $input['search']['value']. '%\'');
            // });

            $records_filtered = $funds_request->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $funds_request = $funds_request->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $funds_request->limit($input['length'])->offset($input['start']);
            }
            else{
                $funds_request = $funds_request->limit($input['length']);
            }

            $response_dt = $funds_request->get();

            $response = FundsRequest::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $funds_request->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addFundsRequest()
    {
        $input = Request::all();
        $request_id = 0;
        $request = new FundsRequest;
        if(empty($input['funds_requests_id']) || (int) $input['funds_requests_id']==0){
            $user = JWTAuth::parseToken()->authenticate();
            $request->requested_by_id = $user->id;
            $request->status_id = 1;
            if($request->save()){
                $request_id = $request->id;
            }
        }
        else {
            $request = FundsRequest::find($input['funds_requests_id']);
        }

        $request_item = new FundsRequestItem;
        if($request_id != 0){
            $request_item->funds_requests_id = $request_id;
        }
        else {
            $request_item->funds_requests_id = $input['funds_requests_id'];
        }
        if(!empty($input['activity']))      $request_item->activity_desc = $input['activity'];
        if(!empty($input['expense_item']))  $request_item->expense_item = $input['expense_item'];
        if(!empty($input['project_id']))    $request_item->project_id = $input['project_id'];
        if(!empty($input['amount']))        $request_item->amount = $input['amount'];
        if(!empty($input['currency_id']))   $request_item->currency_id = $input['currency_id'];

        if($request_item->save()) {
            return Response()->json(array('msg' => 'Success: request added','request_item' => $request_item, 'request'=>$request), 200);
        }
    }   
    
    public function updateFundsRequest()
    {
        $input = Request::all();

        $request = FundsRequest::find($input['id']);
        if(!empty($input['status_id'])) $request->status_id = $input['status_id'];

        if($request->save()) {
            return Response()->json(array('msg' => 'Success: request updated','funds_request' => $request), 200);
        }
    }  
    
    public function deleteFundsRequest($id)
    {
        $deleted = FundsRequest::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"request deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"request not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getFundsRequestById($id)
    {
        $input = Request::all();

        try{
            $response = FundsRequest::with('funds_request_items')->findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"request could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Funds Request Items
     */
    public function updateFundsRequestItem()
    {
        $input = Request::all();

        $request_item = FundsRequestItem::find($input['id']);
        if(!empty($input['activity']))      $request_item->activity_desc = $input['activity'];
        if(!empty($input['expense_item']))  $request_item->expense_item = $input['expense_item'];
        if(!empty($input['project_id']))    $request_item->project_id = $input['project_id'];
        if(!empty($input['amount']))        $request_item->amount = $input['amount'];
        if(!empty($input['currency_id']))   $request_item->currency_id = $input['currency_id'];

        if($request_item->save()) {
            return Response()->json(array('msg' => 'Success: funds request item updated','funds_request_item' => $request_item), 200);
        }
    } 

    public function getFundsRequestItems()
    {
        $response;
        $response_dt;

        $total_records          = FundsRequestItem::count();
        $records_filtered       = 0;
        $user = JWTAuth::parseToken()->authenticate();

        $input = Request::all();        
        $funds_request = FundsRequestItem::with('status','requested_by','funds_request_items');

        // Project
        if(array_key_exists('project_id',$input)){
            $funds_request = $funds_request->where('project_id', $input['project_id']);
        }

        // Currency
        if(array_key_exists('currency_id',$input)){
            $funds_request = $funds_request->where('currency_id', $input['currency_id']);
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $funds_request = $funds_request->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $funds_request = $funds_request->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            // searching
            $funds_request = $funds_request->where(function ($query) use ($input) {                
                $query->orWhere('activity_desc','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('expense_item','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('amount','like', '\'%' . $input['search']['value']. '%\'');
            });

            $records_filtered = $funds_request->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $funds_request = $funds_request->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $funds_request->limit($input['length'])->offset($input['start']);
            }
            else{
                $funds_request = $funds_request->limit($input['length']);
            }

            $response_dt = $funds_request->get();

            $response = FundsRequestItem::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $funds_request->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function getFundsRequestItemById($id)
    {
        $input = Request::all();

        try{
            $response = FundsRequestItem::with('project','currency')->findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"request item could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
}
