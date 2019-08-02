<?php


namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Request;
use App\Models\FundsRequestModels\FundsRequest;
use App\Models\FundsRequestModels\FundsRequestStatus;
use App\Models\FundsRequestModels\FundsRequestItem;
use App\Models\FundsRequestModels\ConsolidatedFunds;
use App\Models\ApprovalsModels\Approval;
use Excel;

class FundsRequestApi extends Controller
{
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
        $user = JWTAuth::parseToken()->authenticate();

        if(array_key_exists('displayable_only',$input)){
            $funds_request_status = $funds_request_status->whereIn('id', [1,4]);
        }

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
            foreach ($response as $key => $value) {
                $response[$key]['count'] = FundsRequest::where('requested_by_id',$user->id)->where('status_id', $value['id'])->count();
            }

            if(array_key_exists('allowed_only', $input)){
                $response[] = array(
                    "id"=> -1,
                    "status"=> "All my funds requests",
                    "order_priority"=> 998,
                    "display_color"=> "#37A9E17A",
                    "count"=> FundsRequest::where('requested_by_id',$user->id)->count()
                );

                if($user->hasRole('financial-controller')){
                    $response[] = array(
                        "id"=> -2,
                        "status"=> "All Funds Requests",
                        "order_priority"=> 1000,
                        "display_color"=> "#092D50",
                        "count"=> FundsRequest::count()
                    );
                }
            }
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
        $funds_request = FundsRequest::with('funds_request_items.program_activity', 'status','requested_by','funds_request_items.currency','funds_request_items.project');

        // Status
        if(array_key_exists('status', $input)){

            $status_ = (int) $input['status'];

            if($status_ >-1){
                $funds_request = $funds_request->where('status_id', $input['status']);
                $funds_request = $funds_request->where('requested_by_id',$user->id);
            }elseif ($status_==-1) {
                $funds_request = $funds_request->where('requested_by_id',$user->id);
            }
        }   

        // Requested by
        if(array_key_exists('requested_by_id',$input)){
            $funds_request = $funds_request->where('requested_by_id', $input['requested_by_id']);
        }

        // My Requests
        if(array_key_exists('my_requests', $input)){
            $funds_request = $funds_request->where('requested_by_id', $user->id);
        }

        // My Approvables
        if(array_key_exists('my_approvables', $input)){
            $funds_request = $funds_request->where('status_id', 2); // Finance approval status
        }

        // Batch
        if(array_key_exists('batch', $input)){
            $funds_request = $funds_request->where('consolidated_funds_id', $input['batch']);
        }

        // Unconsolidated
        if(array_key_exists('unconsolidated', $input)){
            $funds_request = $funds_request->where('status_id', 3); // Approved status
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
            // $response_dt = $this->append_relationship_items($response_dt);

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
        if(empty($input['funds_request_id']) || (int) $input['funds_request_id']==0){
            $user = JWTAuth::parseToken()->authenticate();
            $request->requested_by_id = $user->id;
            $request->status_id = 1;
            if($request->save()){
                $request_id = $request->id;
            }
        }
        else {
            $request = FundsRequest::find($input['funds_request_id']);
        }

        $request_item = new FundsRequestItem;
        if($request_id != 0){
            $request_item->funds_request_id = $request_id;
        }
        else {
            $request_item->funds_request_id = $input['funds_request_id'];
        }
        if(!empty($input['activity_desc'])) $request_item->activity_desc = $input['activity_desc'];
        if(!empty($input['expense_item']))  $request_item->expense_item = $input['expense_item'];
        if(!empty($input['project_id']))    $request_item->project_id = $input['project_id'];
        if(!empty($input['amount']))        $request_item->amount = $input['amount'];
        if(!empty($input['currency_id']))   $request_item->currency_id = $input['currency_id'];
        if(!empty($input['program_activity_id']))   $request_item->program_activity_id = $input['program_activity_id'];

        if($request_item->save()) {
            $request_item = FundsRequestItem::with('project','currency','program_activity')->find($request_item->id);
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
        try{
            $response = FundsRequest::with('status','requested_by','funds_request_items.program_activity','funds_request_items.currency','funds_request_items.project', 'logs.causer', 'approvals')->findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"request could not be found", "msg"=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    public function submitFundsRequestForApproval($id)
    {
        try{
            $request = FundsRequest::findOrFail($id);

            if ($request->total_usd < 1 && $request->total_kes < 1){
                throw new Exception("This request has no items");             
            }           
           
            $request->status_id = $request->status->next_status_id;

            $request->disableLogging(); //! Do not log the update
            if($request->save()) {

                // Logging submission
                activity()
                   ->performedOn($request)
                   ->causedBy($this->current_user())
                   ->log('submitted for approval');
                
                // Mail::queue(new NotifyFundsRequest($request));

                return Response()->json(array('msg' => 'Success: Request submitted','funds_request' => $request), 200);
            }

        }catch(Exception $e){

            $response =  ["error"=>"something went wrong", 'msg'=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    public function approveFundsRequest($id, $several=null)
    {
        $request = [];

        $user = JWTAuth::parseToken()->authenticate();

        try{
            $request = FundsRequest::findOrFail($id);
            $request->status_id = $request->status->next_status_id;

            $request->disableLogging();
            if($request->save()) {

                $request = FundsRequest::findOrFail($id);

                $approval = new Approval;

                $approval->approvable_id            =   (int)   $request->id;
                $approval->approvable_type          =   "funds_requests";
                $approval->approval_level_id        =   3;
                $approval->approver_id              =   (int)   $user->id;

                // Logging
                activity()
                   ->performedOn($request)
                   ->causedBy($user)
                   ->log('approved');

                $approval->save();

                // Mail::queue(new NotifyRequest($request));

                if($several!=true)
                return Response()->json(array('msg' => 'Success: Request approved','funds_request' => $request), 200);
            }

        }catch(ApprovalException $ae){

            $response =  ["error"=>"You do not have the permissions to perform this action at this point"];
            return response()->json($response, 403,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){

            $response =  ["error"=>"Something went wrong", 'msg'=>$e->getMessage()];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    public function approveSeveralFundsRequests()
    { 
        try {
            $form = Request::only("requests");
            $funds_request_ids = $form['requests'];

            foreach ($funds_request_ids as $key => $funds_request_id) {
                $this->approveFundsRequest($funds_request_id, true);
            }

            return response()->json(['requests'=>$form['requests']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing", 'msg'=>$e->getMessage()], 500,array(),JSON_PRETTY_PRINT);
            
        }
    }

    public function consolidateFunds()
    {
        $form = Request::only('requests');

        try{
            $consolidated_funds = new ConsolidatedFunds;

            $consolidated_funds->consolidated_by_id = (int) $this->current_user()->id;

            if($consolidated_funds->save()) {

                foreach ($form['requests'] as $key => $value) {                   

                    $funds_request = FundsRequest::find($value);
                    
                    $funds_request->status_id = $funds_request->status->next_status_id;
                    $funds_request->consolidated_funds_id = $consolidated_funds->id;

                    $funds_request->save();
                }
                // Mail::queue(new NotifyBatch($payment_batch->id));

                return Response()->json(array('msg' => 'Success: Funds consolidated','consolidated_funds' => $consolidated_funds), 200);
            }
        }
        catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
    }

    /**
     * Operation getConsolidatedFunds
     * consolidated funds List.
     * @return Http response
     */
    public function getConsolidatedFunds()
    {

        $input = Request::all();

        $consolidated_funds = ConsolidatedFunds::query();

        $response;
        $response_dt;

        $total_records          = $consolidated_funds->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $consolidated_funds = $consolidated_funds->where(function ($query) use ($input) {
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
            });

            $records_filtered = $consolidated_funds->count();
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $consolidated_funds->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $consolidated_funds = $consolidated_funds->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $consolidated_funds->limit($input['length'])->offset($input['start']);

            }else{
                $consolidated_funds = $consolidated_funds->limit($input['length']);
            }

            $response_dt = $consolidated_funds->get();

            
            foreach($response_dt as $key => $value){
                $total_kes = 0;
                $total_usd = 0;
                foreach($response_dt[$key]['funds_requests'] as $req){
                    $total_kes += $req['total_kes'];
                    $total_usd += $req['total_usd'];
                }
                $response_dt[$key]['total_kes'] = $total_kes;
                $response_dt[$key]['total_usd'] = $total_usd;
            }

            $response = ConsolidatedFunds::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $consolidated_funds->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }


    public function downloadConsolidatedFunds(){
        try{
            $input = Request::only('batch_id');

            $consolidated_funds = ConsolidatedFunds::find($input['batch_id']);
            $requests = $consolidated_funds->funds_requests;
            $excel_data = [];

            foreach($requests as $req){
                foreach($req->funds_request_items as $item){
                    $row = [];
                    $row['project_id'] = $item->project->project_code;
                    empty($item->project->grant_id) ? $row['grant_id'] = '' : $row['grant_id'] = $item->project->grant->grant_code;
                    $row['activity'] = $item->program_activity->title;
                    $row['expense_item'] = $item->expense_item;
                    $row['currency'] = $item->currency->currency_name;
                    $row['amount'] = $item->amount;
                }
                array_push($excel_data, $row);
            }

            $headers = [
                'Access-Control-Allow-Origin'      => '*',
                'Allow'                            => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true'
            ];
            // Build excel
            $file = Excel::create("Consolidated Funds Batch_".$input['batch_id'], function($excel) use ($excel_data, $input) {
                
                // Set the title
                $excel->setTitle("Consolidated Funds Batch_".$input['batch_id']);
    
                // Chain the setters
                $excel->setCreator('GIFMS')->setCompany('Clinton Health Access Initiative - Kenya');
    
                $excel->setDescription('A report of approved and consolidated funds for batch'.$input['batch_id']);
    
                $headings = array('Project ID', 'Grant ID', 'Activity', 'Expense Item', 'Currency', 'Amount');
    
                $excel->sheet('Consolidated funds', function ($sheet) use ($excel_data, $headings) {
                    $sheet->setStyle([
                        'borders' => [
                            'allborders' => [
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);
                    $i = 1;
                    $alternate = true;
                    foreach($excel_data as $data_row){

                        $sheet->appendRow($data_row);
                        $sheet->row($i, function($row) use ($data_row, $alternate){
                            
                        });
                        if($alternate){
                            $sheet->cells('A'.$i.':F'.$i, function($cells) {
                                $cells->setBackground('#edf1f3');  
                                $cells->setFontSize(11);                          
                            });
                        }
                        $i++;
                        $alternate = !$alternate;
                    }
                    
                    $sheet->prependRow(1, $headings);
                    $sheet->setFontSize(11);
                    $sheet->setHeight(1, 25);
                    $sheet->row(1, function($row){
                        $row->setFontSize(11);
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                        $row->setBorder('none', 'thin', 'none', 'thin');
                        $row->setBackground('#004080');                        
                        $row->setFontColor('#ffffff');
                    });
                    $sheet->setWidth(array(
                        'A' => 25,
                        'B' => 15,
                        'C' => 35,
                        'D' => 40,
                        'E' => 10,
                        'F' => 25
                    ));
                    $sheet->getStyle('D1')->getAlignment()->setWrapText(true);

                    $sheet->setFreeze('A2');
                });
    
            })->download('xlsx', $headers);
        }
        catch(Exception $e){
            return response()->json(['error'=>"An rerror occured during processing", 'msg'=>$e->getMessage()], 500,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Funds Request Items
     */
    public function updateFundsRequestItem()
    {
        $input = Request::all();

        $request_item = FundsRequestItem::find($input['id']);
        if(!empty($input['activity_desc'])) $request_item->activity_desc = $input['activity_desc'];
        if(!empty($input['expense_item']))  $request_item->expense_item = $input['expense_item'];
        if(!empty($input['project_id']))    $request_item->project_id = $input['project_id'];
        if(!empty($input['amount']))        $request_item->amount = $input['amount'];
        if(!empty($input['currency_id']))   $request_item->currency_id = $input['currency_id'];
        if(!empty($input['program_activity_id']))   $request_item->program_activity_id = $input['program_activity_id'];

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
        $funds_request = FundsRequestItem::with('program_activity','status','requested_by','funds_request_items');

        // Project
        if(array_key_exists('project_id',$input)){
            $funds_request = $funds_request->where('project_id', $input['project_id']);
        }

        // Currency
        if(array_key_exists('currency_id',$input)){
            $funds_request = $funds_request->where('currency_id', $input['currency_id']);
        }

        // Activity
        if(array_key_exists('program_activity_id', $input)){
            $funds_request = $funds_request->where('program_activity_id', $input['program_activity_id']);
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
        try{
            $response = FundsRequestItem::with('program_activity','project','currency')->findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"request item could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    public function deleteFundsRequestItem($id)
    {
        $deleted = FundsRequestItem::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"item deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
