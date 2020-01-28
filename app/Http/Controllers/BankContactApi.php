<?php

namespace App\Http\Controllers;

use App\Models\BankingModels\BankContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as SupportRequest;

class BankContactApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = SupportRequest::all();
        //query builder
        $contacts = BankContact::query();

        // $response;
        // $response_dt;

        $total_records = $contacts->count();
        $records_filtered = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $contacts = $contacts->where(function ($query) use ($input) {
                $query->orWhere('name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('email','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('phone','like', '\'%' . $input['searchval']. '%\'');
            });

            $records_filtered = (int) $contacts->count();
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction = "asc";
            $order_column_name = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $contacts = $contacts->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $contacts = $contacts->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            if($input['search']['value']){
                $contacts = $contacts->where(function ($query) use ($input) {                
                    $query->orWhere('name','like', '\'%' . $input['search']['value']. '%\'');
                    $query->orWhere('email','like', '\'%' . $input['search']['value']. '%\'');
                    $query->orWhere('phone','like', '\'%' . $input['search']['value']. '%\'');
                });
            }            

            $records_filtered = (int) $contacts->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $contacts = $contacts->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $contacts->limit($input['length'])->offset($input['start']);
            }else{
                $contacts = $contacts->limit($input['length']);
            }
            
            $response = BankContact::arr_to_dt_response( 
                    $contacts->get(), $input['draw'],
                    $total_records,
                    $records_filtered
                );
        }
        else{
            $response = $contacts->get();
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
        $contact = new BankContact();
        $contact->name = $request->name;
        $contact->email = $request->email ?? null;
        $contact->phone = $request->phone ?? null;
        $contact->default_bank_contact = $request->default_bank_contact ?? null;

        $contact->save();
        return Response()->json(array('msg' => 'Success: contact added','contact' => $contact), 200);
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
            $response = BankContact::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(\Exception $e){
            $response = ["error"=>"Something went wrong", "msg"=>$e->getMessage()];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $contact = BankContact::findOrFail($request->id);
        $contact->name = $request->name;
        $contact->email = $request->email ?? null;
        $contact->phone = $request->phone ?? null;
        $contact->default_bank_contact = $request->default_bank_contact ?? null;

        $contact->save();
        return Response()->json(array('msg' => 'Success: Bank contact updated','contact' => $contact), 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = BankContact::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"contact deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"contact not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
}
