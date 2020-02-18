<?php

namespace App\Http\Controllers;

use App\Models\Requisitions\Requisition;
use App\Models\Requisitions\RequisitionStatus;
use App\Models\Requisitions\RequisitionAllocation;
use App\Models\Requisitions\RequisitionItem;
use Exception;
use PDF;
use Illuminate\Http\Request;
use Anchu\Ftp\Facades\Ftp;
use App\Mail\NotifyRequisition;
use App\Models\ApprovalsModels\Approval;
use App\Models\Requisitions\RequisitionDocument;
use App\Models\SuppliesModels\SupplierService;
use Illuminate\Support\Facades\Request as IlluminateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;

class RequisitionApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = IlluminateRequest::all();
        $requisitions = Requisition::with('items.supplier_service','items.county','allocations','requested_by','status','program_manager');
        $user = $this->current_user();

        $response_dt;
        $total_records = $requisitions->count();
        $records_filtered = 0;

        if(array_key_exists('my_assigned',$input)){
            $requisitions = $requisitions->where('requested_by_id', $user->id);
        }
        if(array_key_exists('my_pm_assigned',$input)){
            $requisitions = $requisitions->where('program_manager_id', $user->id);
        }
        if(array_key_exists('project_id',$input)){
            $requisitions = $requisitions->whereHas('allocations', function($query) use ($input){
                $query->where('project_id', $input['project_id']);  
            });
        }
        if(array_key_exists('account_id',$input)){
            $requisitions = $requisitions->whereHas('allocations', function($query) use ($input){
                $query->where('account_id', $input['account_id']);  
            });
        }
        if(array_key_exists('status',$input)){
            $status = (int) $input['status'];
            if($status >= 1){
                $requisitions = $requisitions->where('status_id', $status)->where('requested_by_id',$user->id);
            }
            elseif($status == -1){
                $requisitions = $requisitions->where('requested_by_id',$user->id);
            }
            elseif($status == -2){
                $requisitions = $requisitions->where('program_manager_id',$user->id);
            }
        }
        if(array_key_exists('my_approvables',$input)){
            $requisitions = $requisitions->where('program_manager_id',$user->id)->where('status_id',2);
        }
        if(array_key_exists('service_id',$input)){
            $requisitions = $requisitions->whereHas('items', function($query) use ($input){
                $query->where('service_id', $input['service_id']);  
            });
        }
        if(array_key_exists('service_item_status',$input)){
            $requisitions = $requisitions->whereHas('items', function($query) use ($input){
                $query->where('status_id', $input['service_item_status']);  
            });
        }

        if(array_key_exists('searchval', $input)){
            $requisitions = $requisitions->where(function ($query) use ($input) {                
                $query->orWhere('id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('purpose','like', '\'%' . $input['searchval']. '%\'');
            });
            
            $records_filtered = $requisitions->count();
        }

        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction = "asc";
            $order_column_name = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $requisitions = $requisitions->orderBy($order_column_name, $order_direction);
        }
    
        //limit
        if(array_key_exists('limit', $input)){
            $requisitions = $requisitions->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            // $requisitions = $requisitions->where(function ($query) use ($input) {                
            //     $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
            //     $query->orWhere('ref','like', '\'%' . $input['search']['value']. '%\'');
            //     $query->orWhere('purpose','like', '\'%' . $input['search']['value']. '%\'');
            // });

            $records_filtered = $requisitions->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $requisitions = $requisitions->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $requisitions->limit($input['length'])->offset($input['start']);
            }
            else{
                $requisitions = $requisitions->limit($input['length']);
            }

            $response_dt = $requisitions->get();
            $response = Requisition::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $requisitions->get();
        }
    
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $requisition = new Requisition();
            $requisition->requested_by_id = $request->requested_by_id;
            $requisition->purpose = $request->purpose;
            $requisition->program_manager_id = $request->program_manager_id;
            $requisition->status_id = 1;
            $requisition->submitted_at = date("Y-m-d H:i:s");
            $requisition->objective_id = $request->objective_id ?? null;
            $requisition->disableLogging();
            $requisition->save();
            $requisition->ref = $requisition->generated_ref;
            $requisition->save();

            $allocations = json_decode($request->allocations);
            foreach($allocations as $alloc){
                $allocation = new RequisitionAllocation();
                $allocation->requisition_id = $requisition->id;
                $allocation->percentage_allocated = $alloc->rate;
                $allocation->purpose = $alloc->purpose ?? '';
                $allocation->allocated_by_id = $requisition->requested_by_id;
                $allocation->project_id = $alloc->project_id;
                $allocation->account_id = $alloc->account_id;
                $allocation->objective_id = $alloc->objective_id ?? null;
                $allocation->disableLogging();
                $allocation->save();
            }

            $items = json_decode($request->items);
            foreach($items as $i){
                $item = new RequisitionItem();
                $item->requisition_id = $requisition->id;
                $item->type = $i->type ?? 'extra';
                $item->service = $i->service;
                $item->service_id = $i->service_id ?? null;
                $item->qty_description = $i->qty_description ?? null;
                $item->county_id = $i->county_id ?? null;
                $item->module = $i->module ?? null;
                if(empty($i->no_of_days) && !empty($i->service_id)){
                    $s = SupplierService::findOrFail($i->service_id);
                    $item->qty_description = $i->qty . ' ' . $s->unit;
                }
                $item->qty = $i->qty;
                $item->no_of_days = $i->no_of_days ?? null;
                $item->start_date = date('Y-m-d', strtotime($i->dates[0]));
                $item->end_date = date('Y-m-d', strtotime($i->dates[1]));
                $item->status_id = 1;
                $item->disableLogging();
                $item->save();
            }

            // Files
            $no_of_files = (int) $request->no_of_files;
            $files = [];
            $titles = [];
            for($i = 0; $i < $no_of_files; $i++) {
                $name = 'file'.$i;
                $file_title = 'file'.$i.'_title';
                $files[] = $request->$name;
                $titles[] = $request->$file_title;
            }



            $counter = 0;
            for($f = 0; $f < count($files); $f++) {
                if($files[$f] == 0){
                    continue;
                }
                $requisition_doc = new RequisitionDocument();
                $requisition_doc->title = $titles[$f];
                $requisition_doc->requisition_id = $requisition->id;
                $requisition_doc->uploaded_by_id = $this->current_user()->id;
                $requisition_doc->filename = $requisition->ref.'doc_'.$counter;
                $requisition_doc->type = $files[$f]->getClientOriginalExtension();
                $requisition_doc->save();
                
                FTP::connection()->makeDir('/requisitions');
                FTP::connection()->makeDir('/requisitions/'.$requisition->ref);
                FTP::connection()->uploadFile($files[$f]->getPathname(), '/requisitions/'.$requisition->ref.'/'.$requisition_doc->filename.'.'.$requisition_doc->type);
                $counter += 1;
            }

            // Airfare document
            $airfare_doc = $request->airfare_doc;
            if($airfare_doc != 0){
                $requisition_doc = new RequisitionDocument();
                $requisition_doc->title = "Airfare support document";
                $requisition_doc->requisition_id = $requisition->id;
                $requisition_doc->uploaded_by_id = $this->current_user()->id;
                $requisition_doc->filename = $requisition->ref.'doc_'.$counter;
                $requisition_doc->type = $airfare_doc->getClientOriginalExtension();
                $requisition_doc->save();

                FTP::connection()->makeDir('/requisitions');
                FTP::connection()->makeDir('/requisitions/'.$requisition->ref);
                FTP::connection()->uploadFile($airfare_doc->getPathname(), '/requisitions/'.$requisition->ref.'/'.$requisition_doc->filename.'.'.$requisition_doc->type);
            }

            // Logging
            activity()
                ->performedOn($requisition)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Created new requisition '. $requisition->ref,
                                'summary'=> true])
                ->log('Created');

            return Response()->json(array('msg' => 'Success: requisition added','requisition' => $requisition), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $requisition = Requisition::with('status','allocations.objective','requested_by','returned_by','program_manager','items.supplier_service','items.status','allocations.allocated_by',
                                            'allocations.project','allocations.account','logs.causer','approvals.approver','logs.causer','lpos.status','items.county','invoices','documents')
                                        ->find($id);
            return response()->json($requisition, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $requisition = Requisition::findOrFail($id);
            $requisition->purpose = $request->purpose;
            $requisition->program_manager_id = $request->program_manager_id;
            $requisition->objective_id = $request->objective_id ?? null;
            $requisition->disableLogging();
            $requisition->save();

            $allocations = $request->allocations;
            $new_allocations = [];
            $old_allocations = RequisitionAllocation::where('requisition_id', $id)->pluck('id')->toArray();
            foreach($allocations as $alloc){
                if(empty($alloc['id'])) {
                    $allocation = new RequisitionAllocation();
                    $allocation->requisition_id = $requisition->id;
                }
                else {
                    $allocation = RequisitionAllocation::find($alloc['id']);
                }
                $allocation->percentage_allocated = $alloc['percentage_allocated'];
                $allocation->purpose = $alloc['purpose'] ?? '';
                $allocation->allocated_by_id = $requisition['requested_by_id'];
                $allocation->project_id = $alloc['project_id'];
                $allocation->account_id = $alloc['account_id'];
                if(!empty($alloc['objective_id'])) $allocation->objective_id = $alloc['objective_id'];
                $allocation->disableLogging();
                $allocation->save();
                $new_allocations[] = $allocation->id;
            }
            foreach($old_allocations as $old_allocation){
                if(!in_array($old_allocation, $new_allocations)){
                    RequisitionAllocation::destroy($old_allocation);
                }
            }

            $items = $request->items;
            $new_items = [];
            $old_items = RequisitionItem::where('requisition_id', $id)->pluck('id')->toArray();
            foreach($items as $i){
                if(empty($i['id'])){
                    $item = new RequisitionItem();
                    $item->requisition_id = $requisition->id;
                    $item->status_id = 1;
                }
                else {
                    $item = RequisitionItem::find($i['id']);
                }
                $item->type = $i['type'] ?? 'extra';
                $item->service = $i['service'];
                $item->qty_description = $i['qty_description'] ?? null;
                $item->qty = $i['qty'];
                $item->no_of_days = $i->no_of_days ?? null;   
                $item->service_id = $i['service_id'] ?? null;                
                $item->county_id = $i['county_id'] ?? null;
                $item->module = $i->module ?? null;      
                if(empty($i->no_of_days) && !empty($i->service_id)){
                    $s = SupplierService::findOrFail($i->service_id);
                    $item->qty_description = $i->qty . ' ' . $s->unit;
                }
                $item->start_date = date('Y-m-d', strtotime($i['dates'][0]));
                $item->end_date = date('Y-m-d', strtotime($i['dates'][1]));
                $item->disableLogging();
                $item->save();
                $new_items[] = $item->id;
            }
            foreach($old_items as $old_item){
                if(!in_array($old_item, $new_items)){
                    RequisitionItem::destroy($old_item);
                }
            }

            // // Files
            // $no_of_files = (int) $request->no_of_files;
            // $files = [];
            // for($i = 0; $i < $no_of_files; $i++) {
            //     $name = 'file'.$i;
            //     $files[] = $request->$name;
            // }

            // foreach($files as $file) {
            //     if($file != 0){
            //         FTP::connection()->makeDir('/requisitions');
            //         FTP::connection()->makeDir('/requisitions/'.$requisition->ref);
            //         FTP::connection()->uploadFile($file->getPathname(), '/requisitions/'.$requisition->ref.'/'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension());
            //     }
            // }
            // Logging
            $activity = activity()
                ->performedOn($requisition)
                ->causedBy($this->current_user())
                ->log('Updated');

            return Response()->json(array('msg' => 'Success: requisition updated','requisition' => $requisition), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Requisition::destroy($id);
            if(!empty($id)){
                RequisitionItem::where('requisition_id',$id)->delete();
                RequisitionAllocation::where('requisition_id',$id)->delete();
                RequisitionDocument::where('requisition_id',$id)->delete();
            }
            return response()->json(['msg'=>"Requisition removed"], 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage(), 'stack'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Submit requisition for approval
     */
    public function submit($id){
        try{
            $requisition = Requisition::findOrFail($id);
            $user = $this->current_user();

            if($requisition->status_id == 1){
                $requisition->status_id = 2;
                $requisition->disableLogging();
                $requisition->save();

                // Logging
                activity()
                    ->performedOn($requisition)
                    ->causedBy($user)
                    ->log('Submitted for approval');

                Mail::queue(new NotifyRequisition($requisition->id));

            return Response()->json(array('msg' => 'Success: requisition submitted for approval','data' => $requisition), 200);
            }
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Approvals and rejections
     */
    public function approve($id, $multiple=false){
        $requisition = Requisition::findOrFail($id);
        $user = $this->current_user();
        if(!$user->hasRole(['program-manager']) || $user->id != $requisition->program_manager_id){
            return response()->json(['error'=>"You do not have permission for that action"], 403,array(),JSON_PRETTY_PRINT); 
        }
        if($requisition->status_id == 2) {    // Approve
            $requisition->status_id = 3;
            $requisition->disableLogging();
            $requisition->save();

            // Logging
            activity()
                ->performedOn($requisition)
                ->causedBy($user)
                ->withProperties(['detail' => 'Requisition '.$requisition->ref.' approved', 'summary'=> true])
                ->log('Requisition approved');

            $approval = new Approval();

            $approval->approvable_id = (int) $requisition->id;
            $approval->approvable_type = "requisitions";
            $approval->approval_level_id = 2;
            $approval->approver_id = (int) $user->id;
            $approval->disableLogging();
            $approval->save();

            Mail::queue(new NotifyRequisition($requisition->id));
        
            if(!$multiple)
            return Response()->json(array('msg' => 'Success: requisitions approved','data' => $requisition), 200);
        }
    }

    public function approveMultiple(){
        try {
            $form = IlluminateRequest::only("requisitions");
            $requisition_ids = $form['requisitions'];

            foreach ($requisition_ids as $key => $id) {
                $this->approve($id, true);
            }

            return response()->json(['requisitions'=>$form['requisitions']], 201, array(), JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An error occured during processing",'msg'=>$e->getMessage()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    public function reject($id){
        $input = IlluminateRequest::only('rejection_reason');
        $requisition = Requisition::findOrFail($id);
        if($requisition->status_id == 2) {
            $requisition->status_id = 4;
            $requisition->return_reason = $input['rejection_reason'];
            $requisition->returned_by_id = $this->current_user()->id;
            $requisition->disableLogging();
            $requisition->save();

            Mail::queue(new NotifyRequisition($requisition->id));
            
            // Logging
            activity()
                ->performedOn($requisition)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Requisition '.$requisition->ref.' returned. REASON: '.$input['rejection_reason'], 'summary'=> true])
                ->log('Requisition returned');
        
            return Response()->json(array('msg' => 'Success: requisition returned','data' => $requisition), 200);
        }
    }

    public function deleteAllocation($id){
        $allocation = RequisitionAllocation::findOrFail($id);
        if($allocation->delete()){
            $requisition = Requisition::find($allocation->requisition_id);
            return response()->json(['msg'=>"Requisition removed",'requisition'=>$requisition], 200,array(),JSON_PRETTY_PRINT);
        }
        else {
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get documents
     */
    public function getDocument($name){
        try{
            $ref = explode('doc_', $name)[0];
            $requisition = Requisition::where('ref', $ref)->firstOrFail();
            $path           = '/requisitions/'.$requisition->ref.'/'.$name.'.pdf';
            $path_info      = pathinfo($path);
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }
        catch (Exception $e){
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
    }

    public function getRequisitionDocument($id){
        try{
            $requisition = Requisition::with('status','allocations.objective','requested_by','program_manager','items.supplier_service','items.status','allocations.allocated_by',
                                        'allocations.project','allocations.account','logs.causer','approvals.approver','logs.causer','lpos.status','items.county')
                                        ->findOrFail($id);
            $unique_approvals = $this->unique_multidim_array($requisition->approvals, 'approver_id');

            $data = array(
                'requisition' => $requisition,
                'unique_approvals' => $unique_approvals
                );

            $pdf = PDF::loadView('pdf/requisition', $data);
            $file_contents  = $pdf->stream();
            $response = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
        catch (Exception $e){
            $response = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
    }



    /**
     * Return requisition statuses
     */
    public function statuses(){
        $input = IlluminateRequest::all();
        $statuses = RequisitionStatus::query();
        if(array_key_exists('displayable_only',$input)){
            $statuses = $statuses->whereIn('id',[1,4]);
        }

        $statuses = $statuses->get();
        // Attach the other statuses
        if(array_key_exists('displayable_only',$input)){
            $user = $this->current_user();
            $statuses[] = [
                'id'=>-1,
                'status'=>'My requisitions',
                'display_color'=>'#075b23a1',
                'count'=>Requisition::where('requested_by_id',$user->id)->count()
            ];

            if ($user->hasRole('program-manager')){
                $statuses[] = [
                    'id'=>-2,
                    'status'=>'My PM-assigned requisitions',
                    'display_color'=>'#075b23a1',
                    'count'=>Requisition::where('program_manager_id',$user->id)->count()
                ];
            }

            if ($user->hasRole(['admin','super-admin','accountant','assistant-accountant','director','associate-director','admin-manager'])){
                $statuses[] = [
                    'id'=>-3,
                    'status'=>'All requisitions',
                    'display_color'=>'#075b23a1',
                    'count'=>Requisition::count()
                ];
            }
        }
        
        return response()->json($statuses, 200,array(),JSON_PRETTY_PRINT);
    }
}
