<?php

namespace App\Http\Controllers;

use App\Models\Requisitions\Requisition;
use App\Models\Requisitions\RequisitionStatus;
use Exception;
use Illuminate\Http\Request;

class RequisitionApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            $allocations = $request->allocations;
            foreach($allocations as $alloc){
                $allocation = new RequisitionAllocation();
                $allocation->requisition_id = $requisition->id;
                $allocation->percentage_allocated = $alloc->percentage;
                $allocation->purpose = $alloc->purpose;
                $allocation->allocated_by_id = $alloc->allocated_by_id;
                $allocation->project_id = $alloc->project_id;
                $allocation->account_id = $alloc->account_id;
                $allocation->disableLogging();
                $allocation->save();
            }

            $items = $request->items;
            foreach($items as $i){
                $item = new RequisitionItem();
                $item->requisition_id = $requisition->id;
                $item->percentage_allocated = $i->percentage;
                $item->purpose = $i->purpose;
                $item->allocated_by_id = $this->current_user()->id;
                $item->project_id = $i->project_id;
                $item->account_id = $i->account_id;
                $item->disableLogging();
                $item->save();
            }

            return Response()->json(array('msg' => 'Success: requisition added','requisition' => $requisition), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage()], 500,array(),JSON_PRETTY_PRINT);
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
            return response()->json(Requisition::findOrFail($id), 200,array(),JSON_PRETTY_PRINT);
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
        //
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
