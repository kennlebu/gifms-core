<?php

namespace App\Http\Controllers;

use App\Models\Meetings\Meeting;
use App\Models\Meetings\MeetingAttendanceLog;
use App\Models\Meetings\MeetingAttendanceRegister;
use App\Models\Meetings\MeetingAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IlluminateRequest;
use Exception;

class MeetingApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = IlluminateRequest::all();
        $meetings = Meeting::with('county', 'created_by');

        $response_dt;
        $total_records = $meetings->count();
        $records_filtered = 0;

        if(array_key_exists('searchval', $input)){
            $meetings = $meetings->where(function ($query) use ($input) {                
                $query->orWhere('id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('title','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('county_id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('starts_on','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('ends_on','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('created_by_id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('invite_url','like', '\'%' . $input['search']['value']. '%\'');
            });
            
            $records_filtered = $meetings->count();
        }

        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction = "asc";
            $order_column_name = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $meetings = $meetings->orderBy($order_column_name, $order_direction);
        }
    
        //limit
        if(array_key_exists('limit', $input)){
            $meetings = $meetings->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $meetings->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $meetings = $meetings->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $meetings->limit($input['length'])->offset($input['start']);
            }
            else{
                $meetings = $meetings->limit($input['length']);
            }

            $response_dt = $meetings->get();
            $response = Meeting::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $meetings->get();
        }
    
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
            $meeting = new Meeting();
            $meeting->title = $request->title;
            $meeting->county_id = $request->county_id;
            $meeting->starts_on = $request->starts_on;
            $meeting->ends_on = $request->ends_on;
            $meeting->created_by_id = $this->current_user()->id;
            $meeting->save();
            $meeting->disableLogging();
            $meeting->invite_url = uniqid($this->current_user()->id);
            $meeting->save();

            return Response()->json(array('msg' => 'Success: meeting added','meeting' => $meeting), 200);
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
            $meeting = Meeting::with('county','created_by')->find($id);
            return response()->json($meeting, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $meeting = Meeting::findOrFail($id);
            $meeting->title = $request->title;
            $meeting->county_id = $request->county_id;
            $meeting->starts_on = $request->starts_on;
            $meeting->ends_on = $request->ends_on;
            $meeting->created_by_id = $request->created_by_id;
            $meeting->save();
            $meeting->disableLogging();

            
            // Logging
            // $activity = activity()
            //     ->performedOn($meeting)
            //     ->causedBy($this->current_user())
            //     ->log('Updated');


            return Response()->json(array('msg' => 'Success: requisition updated','requisition' => $meeting), 200);
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
            Meeting::destroy($id);
            return response()->json(['msg'=>"Meeting removed"], 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage(), 'stack'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }


    /**
     * Meeting registration
     */
    public function registerIndex($url){
        $meeting = Meeting::where('invite_url', $url)->first();
        return view('meeting_register')
                ->with('url', $url)
                ->with('meeting', $meeting);
    }


    public function register(Request $request){
        $url = $request->url;
        $meeting = Meeting::where('invite_url', $url)->first();
        if(empty($meeting)){
            return back()->with('error','This event does not exist');
        }
        $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
        if(empty($attendee)){
            return view('attendee_register')
            ->with('info','Please complete your registration first')
            ->with('id_no', $request->id_no)
            ->with('meeting_url', $url)
            ->with('meeting', $meeting);
        }

        if(MeetingAttendanceRegister::where('meeting_id', $meeting->id)->where('attendee_id', $attendee->id)->exists()){
            return back()->with('error','You have already registered for this event');
        }

        $register = new MeetingAttendanceRegister();
        $register->meeting_id = $meeting->id;
        $register->attendee_id = $attendee->id;
        $register->disableLogging();
        $register->save();

        return view('meeting_registration_success')->with('attendee', $attendee)->with('meeting', $meeting);
    }


    public function registerAttendee(Request $request){
        try{
            $attendee = new MeetingAttendee();
            $attendee->name = $request->name;
            $attendee->id_no = $request->id_no;
            $attendee->organisation = $request->organisation;
            $attendee->designation = $request->designation;
            $attendee->phone = $request->phone;
            $attendee->bank_name = $request->bank_name;
            $attendee->bank_branch_name = $request->bank_branch_name;
            $attendee->bank_account = $request->bank_account;
            $attendee->kra_pin = $request->kra_pin;
            $attendee->disableLogging();
            $attendee->save();

            if(!empty($request->url)){
                $register = new MeetingAttendanceRegister();
                $meeting = Meeting::where('invite_url', $request->url)->first();
                $register->meeting_id = $meeting->id;
                $register->attendee_id = $attendee->id;
                $register->disableLogging();
                $register->save();
            }

            return view('meeting_registration_success')->with('attendee', $attendee)->with('meeting', $meeting);
        }
        catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }


    public function logAttendance(Request $request){
        try{
            $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Attendee does not exist"], 404, array(), JSON_PRETTY_PRINT);
            }
            if(empty($attendee->fp_template_1)){
                return response()->json(['error'=>"Attendee not enrolled", 'attendee'=>$attendee], 404, array(), JSON_PRETTY_PRINT);
            }

            $attendee = MeetingAttendee::where('id_no', $request->id_no)->where('fp_template_1', $request->fp_template_1)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Fingerprints do not match"], 401, array(), JSON_PRETTY_PRINT);
            }

            $previous_log = MeetingAttendanceLog::where('date', date('Y-m-d'))->first();
            $log = new MeetingAttendanceLog();
            $log->meeting_id = $request->meeting_id;
            $log->attendee_id = $attendee->id;
            $log->date = date('Y-m-d');
            
            if(!empty($previous_log)){
                $log->time_out = date('H:i:s');
            }
            else {
                $log->time_in = date('H:i:s');
            }            
            $log->disableLogging();
            $log->save();

            return Response()->json(array('msg' => 'Attendance logged','log' => $log), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }



    public function enrollAttendee(Request $request){
        try{
            $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Attendee does not exist"], 404, array(), JSON_PRETTY_PRINT);
            }

            $attendee->fp_template_1 = $request->fp_template_1;
            $attendee->disableLogging();
            $attendee->save();

            return Response()->json(array('msg' => 'Attendee enrolled','attendee' => $attendee), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }



    public function registerAttendeeApi(Request $request){
        try{
            $attendee = new MeetingAttendee();
            $attendee->name = $request->name;
            $attendee->id_no = $request->id_no;
            $attendee->organisation = $request->organisation;
            $attendee->designation = $request->designation;
            $attendee->phone = $request->phone;
            $attendee->bank_name = $request->bank_name;
            $attendee->bank_branch_name = $request->bank_branch_name;
            $attendee->bank_account = $request->bank_account;
            $attendee->kra_pin = $request->kra_pin;
            $attendee->fp_template_1 = $request->fp_template_1;
            $attendee->disableLogging();
            $attendee->save();

            return Response()->json(array('msg' => 'Attendee registered','attendee' => $attendee), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
