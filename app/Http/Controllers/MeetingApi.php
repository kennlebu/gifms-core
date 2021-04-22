<?php

namespace App\Http\Controllers;

use App\Models\Meetings\Meeting;
use App\Models\Meetings\MeetingAttendanceLog;
use App\Models\Meetings\MeetingAttendanceRegister;
use App\Models\Meetings\MeetingAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IlluminateRequest;
use Exception;
use Excel;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Response;

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

        $total_records = $meetings->count();
        $records_filtered = 0;

        if(array_key_exists('searchval', $input)){
            $meetings = $meetings->where(function ($query) use ($input) {
                $query->where('title','like', '%' . $input['search']['value']. '%');
                $query->orWhere('county_id','like', '%' . $input['search']['value']. '%');
                $query->orWhere('starts_on','like', '%' . $input['search']['value']. '%');
                $query->orWhere('ends_on','like', '%' . $input['search']['value']. '%');
                $query->orWhere('created_by_id','like', '%' . $input['search']['value']. '%');
                $query->orWhere('invite_url','like', '%' . $input['search']['value']. '%');
                $query->orWhere('organiser_email','like', '%' . $input['search']['value']. '%');
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
                $meetings = $meetings->limit($input['length'])->offset($input['start']);
            }
            else{
                $meetings = $meetings->limit($input['length']);
            }

            $meetings = $meetings->get();
            $response = Meeting::arr_to_dt_response( 
                $meetings->get(), $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $meetings->get();
        }
    
        return response()->json($response, 200);
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
            $meeting->county_id = $request->county_id ?? null;
            $meeting->starts_on = $request->starts_on;
            $meeting->ends_on = $request->ends_on;
            $meeting->organiser_email = $request->organiser_email;
            $meeting->confirmation_mode = $request->confirmation_mode;
            $meeting->allow_over_threshold_amount = $request->allow_over_threshold_amount;
            $meeting->created_by_id = $this->current_user()->id;
            $meeting->save();
            $meeting->disableLogging();
            $meeting->invite_url = uniqid($this->current_user()->id);
            $meeting->save();

            return Response()->json(array('msg' => 'Success: meeting added','meeting' => $meeting), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            $meeting = Meeting::with('county','created_by');
            $meeting = $meeting->find($id);

            if($request->with_attendees){
                $meeting['attendees'] = $meeting->attendees();
            }

            return response()->json($meeting, 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
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
            $meeting = Meeting::findOrFail($id);
            $meeting->title = $request->title;
            $meeting->county_id = $request->county_id;
            $meeting->starts_on = $request->starts_on;
            $meeting->ends_on = $request->ends_on;
            $meeting->organiser_email = $request->organiser_email;
            $meeting->confirmation_mode = $request->confirmation_mode;
            $meeting->allow_over_threshold_amount = $request->allow_over_threshold_amount;
            $meeting->created_by_id = $request->created_by_id;
            $meeting->save();
            $meeting->disableLogging();

            
            // Logging
            // $activity = activity()
            //     ->performedOn($meeting)
            //     ->causedBy($this->current_user())
            //     ->log('Updated');


            return Response()->json(['msg' => 'Success: requisition updated','requisition' => $meeting], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
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
            return response()->json(['msg'=>"Meeting removed"], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage(), 'stack'=>$e->getTraceAsString()], 500);
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
    public function registerIndexBanking($url){
        $meeting = Meeting::where('invite_url', $url)->first();
        return view('meeting_register')
                ->with('url', $url)
                ->with('meeting', $meeting)
                ->with('banking', true);
    }


    public function register(Request $request){
        $url = $request->url;
        $meeting = Meeting::where('invite_url', $url)->first();
        if(empty($meeting)){
            return back()->with('error','This event does not exist');
        }

        if($meeting->expired){
            return back()->with('error','This event has already ended');
        }

        $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
        if(empty($attendee)){
            return view('attendee_register')
            ->with('info','Please complete your registration first')
            ->with('id_no', $request->id_no)
            ->with('meeting_url', $url)
            ->with('meeting', $meeting)
            ->with('banking', $request->banking ?? null);
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
            if(empty(trim($attendee->id_no))){
                return back()->with('error','ID Number cannot be blank.');
            }
            $attendee->email = $request->email;
            $attendee->physical_address = $request->physical_address ?? null;
            $attendee->organisation = $request->organisation ?? null;
            $attendee->station = $request->station ?? null;
            $attendee->designation = $request->designation ?? null;
            $attendee->phone = $request->phone;
            $attendee->bank_name = $request->bank_name ?? null;
            $attendee->bank_branch_name = $request->bank_branch_name ?? null;
            $attendee->bank_account = $request->bank_account ?? null;
            $attendee->kra_pin = $request->kra_pin ?? null;

            $attendee_exists = MeetingAttendee::where('id_no', $attendee->id_no)->where('phone', $attendee->phone)->first();
            if($attendee_exists) {
                return back()->with('error','You are already registered.');
            }

            $attendee->disableLogging();
            $attendee->save();

            if(!empty($request->url)){
                $meeting = Meeting::where('invite_url', $request->url)->first();
                if($meeting->expired){
                    return back()->with('error','This event has already ended');
                }
                if(MeetingAttendanceRegister::where('meeting_id', $meeting->id)->where('attendee_id', $attendee->id)->exists()){
                    return back()->with('error','You have already registered for this event');
                }
                $register = new MeetingAttendanceRegister();
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


    public function verifyAttendee(Request $request){
        try{
            $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Attendee does not exist"], 404, array(), JSON_PRETTY_PRINT);
            }
            if(empty($attendee->fp_template_1)){
                return response()->json(['error'=>"Attendee not enrolled", 'attendee'=>$attendee], 404, array(), JSON_PRETTY_PRINT);
            }

            $path           = '/fpts/'.$attendee->id.'/'.$attendee->id.'.fpt';
            $path_info      = pathinfo($path);
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', "image/bmp");
            return $response;  
        }
        catch(Exception $e){
            $response = Response::make("", 200);
            $response->header('Content-Type', 'application/image');
            return $response;  
        }
    }


    public function logAttendance(Request $request){
        try{
            $final_log = null;
            $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Attendee does not exist"], 404, array(), JSON_PRETTY_PRINT);
            }
            if(empty($attendee->fp_template_1)){
                return response()->json(['error'=>"Attendee not enrolled", 'attendee'=>$attendee], 404, array(), JSON_PRETTY_PRINT);
            }

            $previous_log = MeetingAttendanceLog::where('date', date('Y-m-d'))
                                                ->where('attendee_id', $attendee->id)
                                                ->where('meeting_id', $request->meeting_id)
                                                ->orderBy('id', 'desc')->first();

            if(!empty($previous_log->time_out)){
                return response()->json(['error'=>"Attendee already clocked out", 'attendee'=>$attendee], 409, array(), JSON_PRETTY_PRINT);
            }
            
            if(!empty($previous_log)){
                $previous_log->time_out = date('H:i:s');
                $previous_log->save();
                $final_log = $previous_log;
            }
            else {
                $log = new MeetingAttendanceLog();
                $log->meeting_id = $request->meeting_id;
                $log->attendee_id = $attendee->id;
                $log->date = date('Y-m-d');
                $log->time_in = date('H:i:s'); 
                $log->disableLogging();
                $log->save();
                $final_log = $log;
            }

            return Response()->json(array('msg' => 'Attendance logged','log' => $final_log, 'attendee'=>$attendee), 200);
            // return Response()->json(array('attendee' => $attendee), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
        }
    }



    public function enrollAttendee(Request $request){
        try{
            $attendee = MeetingAttendee::where('id_no', $request->id_no)->first();
            if(empty($attendee)){
                return response()->json(['error'=>"Attendee does not exist"], 404, array(), JSON_PRETTY_PRINT);
            }

            if($request->file!=0){
                FTP::connection()->makeDir('/fpts');
                FTP::connection()->makeDir('/fpts/'.$attendee->id);
                FTP::connection()->uploadFile($request->file->getPathname(), '/fpts/'.$attendee->id.'/'.$attendee->id.'.'."fpt");

                $attendee->fp_template_1 = 1;
                $attendee->disableLogging();
                $attendee->save();

                activity()
                    ->performedOn($attendee)
                    ->causedBy($this->current_user())
                    ->withProperties(['detail' => "Uploaded fingerprint for ".$attendee->name.", ID: ".$attendee->id_no])
                    ->log('Uploaded fingerprint');
            }
            else {
                return response()->json(['error'=>"Failed to read file"], 500, array(), JSON_PRETTY_PRINT);
            }

            return Response()->json(array('msg' => 'Attendee prints uploaded'), 200);
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
            $attendee->email = $request->email;
            $attendee->physical_address = $request->physical_address;
            $attendee->organisation = $request->organisation;
            $attendee->station = $request->station;
            $attendee->designation = $request->designation;
            $attendee->phone = $request->phone;
            $attendee->bank_name = $request->bank_name;
            $attendee->bank_branch_name = $request->bank_branch_name;
            $attendee->bank_account = $request->bank_account;
            $attendee->kra_pin = $request->kra_pin;
            $attendee->disableLogging();
            $attendee->save();

            return Response()->json(array('msg' => 'Attendee registered','attendee' => $attendee), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }


    public function getAttendee(Request $request){
        try{
            $attendee = MeetingAttendee::where('id_no', $request->id_no);
            return Response()->json(['attendee' => $attendee], 200);
        }
            catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage()], 500);
        }
    }

    public function getAttendees(Request $request){
        try{
            if($request->type == 'registered'){
                $attended = MeetingAttendanceLog::where('meeting_id', $request->meeting_id)->pluck('attendee_id')->toArray();
            }
            else{
                $attended = MeetingAttendanceRegister::where('meeting_id', $request->meeting_id)->pluck('attendee_id')->toArray();
            }
            
            $attendees = MeetingAttendee::whereIn('id', $attended);
            return Response()->json($attendees, 200);
        }
            catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
        }
    }



    public function downloadAttendanceSheet(Request $request){
        try{
            $meeting = Meeting::find($request->meeting_id);
            $type = $request->type ?? null;
            if($type == 'attended' || (empty($type) && $meeting->confirmation_code == 'biometric')){
                $logs = MeetingAttendanceLog::where('meeting_id', $request->meeting_id)->pluck('attendee_id')->toArray();
            }
            else {
                $logs = MeetingAttendanceRegister::where('meeting_id', $request->meeting_id)->pluck('attendee_id')->toArray();
            }
            $attendees = MeetingAttendee::whereIn('id', $logs)->get();

            // Record user action
            activity()
                ->performedOn($meeting)
                ->causedBy($this->current_user())
                ->log('Downloaded attendance sheet');
                
            // Generate excel and return it    
            $excel_data = [];
            foreach($attendees as $attendee){
                $excel_row = [];
                $excel_row['name'] = $attendee->name;
                $excel_row['phone'] = $attendee->phone;
                $excel_row['id'] = $attendee->id_no;
                $excel_row['pin'] = $attendee->kra_pin;
                $excel_row['email'] = $attendee->email;
                $excel_row['physical_address'] = $attendee->physical_address;
                $excel_row['organisation'] = $attendee->organisation;
                $excel_row['station'] = $attendee->station;
                $excel_row['designation'] = $attendee->designation;
                $excel_row['account_no'] = $attendee->bank_account;
                $excel_row['bank'] = $attendee->bank_name;
                $excel_row['branch'] = $attendee->bank_branch_name;
                $excel_row['amount'] = $attendee->amount;
                
                $excel_data[] = $excel_row;
            }
            $headers = [
                'Access-Control-Allow-Origin'      => '*',
                'Allow'                            => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true'
            ];
            // Build excel
            $file = Excel::create('Attendance sheet for '.$meeting->title ?? '[No name]', function($excel) use ($excel_data, $meeting) {

                // Set the title
                $excel->setTitle('Attendance sheet');

                // Chain the setters
                $excel->setCreator('GIFMS')->setCompany('Clinton Health Access Initiative - Kenya');

                $excel->setDescription('Attendance sheet for '.$meeting->title ?? '[No name]');

                $headings = array('Name', 'Phone#', 'ID', 'KRA', 'Email', 'Physical Address', 'County/Organisation', 'Subcounty/Station', 'Designation', 'Account No.', 'Bank', 'Branch', 'Amount');

                $excel->sheet("Attendance sheet", function ($sheet) use ($excel_data, $headings) {
                    $sheet->setStyle([
                        'borders' => [
                            'allborders' => [
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);
                    foreach($excel_data as $data_row){
                        $sheet->appendRow($data_row);
                    }
                    
                    $sheet->prependRow(1, $headings);
                    $sheet->setFontSize(10);
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
                        'A' => 30,
                        'B' => 15,
                        'C' => 20,
                        'D' => 20,
                        'E' => 15,
                        'F' => 35,
                        'G' => 50,
                        'H' => 50,
                        'I' => 50,
                        'J' => 50,
                        'K' => 50,
                        'L' => 50,
                        'L' => 50
                    ));

                    $sheet->setFreeze('B2');
                });
        
            })->download('xlsx', $headers);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong",'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
