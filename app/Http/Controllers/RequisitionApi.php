<?php

namespace App\Http\Controllers;

use App\Models\Requisitions\Requisition;
use App\Models\Requisitions\RequisitionStatus;
use App\Models\Requisitions\RequisitionAllocation;
use App\Models\Requisitions\RequisitionItem;
use Exception;
use Illuminate\Http\Request;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Request as IlluminateRequest;

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
        $requisitions = Requisition::with('items','allocations','requested_by','status');

        $response_dt;
        $total_records = $requisitions->count();
        $records_filtered = 0;

        if(array_key_exists('my_assigned',$input)){
            $requisitions = $requisitions->where('requested_by_id', $this->current_user()->id);
        }
        if(array_key_exists('my_pm_assigned',$input)){
            $requisitions = $requisitions->where('program_manager_id', $this->current_user()->id);
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
            if($status > -1){
                $requisitions = $requisitions->where('status_id', $input['status'])->where('requested_by_id',$this->current_user()->id);
            }
            elseif($status == -1){
                $requisitions = $requisitions->where('requested_by_id',$this->current_user()->id);
            }
            elseif($status == -2){
                $requisitions = $requisitions->where('project_manager_id',$this->current_user()->id);
            }
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
            $requisition->save();
            $requisition->disableLogging();
            $requisition->ref = 'R-'.$this->pad_with_zeros(5,$requisition->id);
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
                $allocation->disableLogging();
                $allocation->save();
            }

            $items = json_decode($request->items);
            foreach($items as $i){
                $item = new RequisitionItem();
                $item->requisition_id = $requisition->id;
                $item->type = $i->type ?? 'extra';
                $item->service = $i->name;
                $item->description = $i->description;
                $item->qty = $i->qty;
                $item->start_date = $i->dates;
                $item->disableLogging();
                $item->save();
            }

            // Files
            $no_of_files = (int) $request->no_of_files;
            $files = [];
            for($i = 0; $i < $no_of_files; $i++) {
                $name = 'file'.$i;
                $files[] = $request->$name;
            }

            foreach($files as $file) {
                FTP::connection()->makeDir('/requisitions');
                FTP::connection()->makeDir('/requisitions/'.$requisition->ref);
                FTP::connection()->uploadFile($file->getPathname(), '/requisitions/'.$requisition->ref.'/'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension());
            }

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
            $requisition = Requisition::with('items.service','items.service','allocations.allocated_by','allocations.project','allocations.account')
                                        ->find($id);
            return response()->json($requisition, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
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
            $requisition->requested_by_id = $request->requested_by_id;
            $requisition->purpose = $request->purpose;
            $requisition->program_manager_id = $request->program_manager_id;
            $requisition->save();
            $requisition->disableLogging();

            $allocations = $requisition->allocations;
            foreach($allocations as $alloc){
                $allocation = RequisitionAllocation::where('requisition_id', $id)->first();
                $allocation->percentage_allocated = $alloc->rate;
                $allocation->purpose = $alloc->purpose ?? '';
                $allocation->allocated_by_id = $requisition->requested_by_id;
                $allocation->project_id = $alloc->project_id;
                $allocation->account_id = $alloc->account_id;
                $allocation->disableLogging();
                $allocation->save();
            }

            $items = $requisition->items;
            foreach($items as $i){
                $item = RequisitionItem::where('requisition_id', $id)->first();
                // $item->requisition_id = $requisition->id;
                $item->type = $i->type;
                $item->service = $i->name;
                $item->description = $i->description;
                $item->qty = $i->qty;
                $item->start_date = $i->dates;
                $item->disableLogging();
                $item->save();
            }

            // Files
            $no_of_files = (int) $request->no_of_files;
            $files = [];
            for($i = 0; $i < $no_of_files; $i++) {
                $name = 'file'.$i;
                $files[] = $request->$name;
            }

            foreach($files as $file) {
                if($file != 0){
                    FTP::connection()->makeDir('/requisitions');
                    FTP::connection()->makeDir('/requisitions/'.$requisition->ref);
                    FTP::connection()->uploadFile($file->getPathname(), '/requisitions/'.$requisition->ref.'/'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension());
                }
            }

            return Response()->json(array('msg' => 'Success: requisition added','requisition' => $requisition), 200);
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
            }
            return response()->json(['msg'=>"Requisition removed"], 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }



    /**
     * Return requisition statuses
     */
    public function statuses(){
        $statuses = RequisitionStatus::all();
        return response()->json($statuses, 200,array(),JSON_PRETTY_PRINT);
    }
}
