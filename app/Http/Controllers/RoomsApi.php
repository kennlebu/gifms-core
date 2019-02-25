<?php
namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\RoomsModels\MeetingRoom;
use App\Models\RoomsModels\MeetingRoomBooking;

class RoomsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    public function addRoom(){
        $input = Request::all();
        try{

            $room = new MeetingRoom;
            $room->name = $input['name'];
            $room->location = $input['location'];
            $room->capacity = $input['capacity'];
            $room->display_color = $input['display_color'];
            $room->save();

            return response()->json(array('msg' => 'Room added'), 200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRooms(){
        try{
            $response;
            $response_dt;

            $total_records          = MeetingRoom::count();
            $records_filtered       = 0;

            $input = Request::all();
            $rooms = MeetingRoom::query();

            $response;
            $response_dt;

            $total_records          = $rooms->count();
            $records_filtered       = 0;

            //searching
            if(array_key_exists('searchval', $input)){
                $rooms = $rooms->where(function ($query) use ($input) {                    
                    $query->orWhere('name','like', '\'%' . $input['searchval']. '%\'');
                    $query->orWhere('capacity','like', '\'%' . $input['searchval']. '%\'');
                });

                $dt = $rooms->get();

                $records_filtered = $rooms->count();
            }

            //ordering
            if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
                $order_direction     = "asc";
                $order_column_name   = $input['order_by'];
                if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                    $order_direction = $input['order_dir'];
                }

                $rooms = $rooms->orderBy($order_column_name, $order_direction);
            }

            //limit
            if(array_key_exists('limit', $input)){
                $rooms = $rooms->limit($input['limit']);
            }

            // not booked
            if(array_key_exists('not_booked', $input)){
                $from = date('Y-m-d', strtotime($input['from_date'])).' '.$input['from_time'].':00';
                $to = date('Y-m-d', strtotime($input['to_date'])).' '.$input['to_time'].':00';
            }

            if(array_key_exists('datatables', $input)){

                //searching
                // $rooms = $rooms->where(function ($query) use ($input) {                
                //     $query->orWhere('name','like', '\'%' . $input['search']['value']. '%\'');
                //     $query->orWhere('capacity','like', '\'%' . $input['search']['value']. '%\'');
                //     $query->orWhere('location','like', '\'%' . $input['search']['value']. '%\'');
                // });
    
                $records_filtered = $rooms->count();
    
                //ordering
                $order_column_id    = (int) $input['order'][0]['column'];
                $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                $order_direction    = $input['order'][0]['dir'];
    
                if($order_column_name!=''){
                    $rooms = $rooms->orderBy($order_column_name, $order_direction);
                }
    
                //limit $ offset
                if((int)$input['start']!= 0 ){
                    $response_dt = $rooms->limit($input['length'])->offset($input['start']);
                }
                else{
                    $rooms = $rooms->limit($input['length']);
                }
    
                $response_dt = $rooms->get();
    
                $response = MeetingRoom::arr_to_dt_response( 
                    $response_dt, $input['draw'],
                    $total_records,
                    $records_filtered
                    );
            }
            else{
                $response = $rooms->get();
            }
            
            return response()->json($response, 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRoomById($room_id){
        try{
            $room = MeetingRoom::find($room_id);
            return response()->json($room, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function deleteRoom($room_id)
    {
        $deleted = MeetingRoom::destroy($room_id);

        if($deleted){
            return response()->json(['msg'=>"Room removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Room not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }

    public function editRoom(){
        try{
            $input = Request::all();

            $room = MeetingRoom::findOrFail($input['id']);

            $room->name = $input['name'];
            $room->location = $input['location'];
            $room->capacity = $input['capacity'];
            $room->display_color = $input['display_color'];

            if($room->save()){
                return Response()->json(array('msg' => 'Room updated','resource' => $room), 200);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function searchAvailable(){
        try{
            $input = Request::all();
            $from = date('Y-m-d', strtotime($input['from_date']));
            if(!empty($input['from_time'])) $from = $from.' '.$input['from_time'].':00';

            $to = date('Y-m-d', strtotime($input['to_date']));
            if(!empty($input['to_time'])) $to = $to.' '.$input['to_time'].':00';
            $available_rooms = array();

            if(empty($from) || empty($to)){
                $available_rooms = MeetingRoom::all();
            }
            else{
                $available_rooms = MeetingRoom::with('bookings')->whereDoesntHave('bookings', function ($query) use ($input, $from, $to){
                    $query->whereBetween(DB::raw("cast(CONCAT(`from_date`, ' ', `from_time`) as datetime)"), [$from, $to]);
                    $query->orwhereBetween(DB::raw("cast(CONCAT(`to_date`, ' ', `to_time`) as datetime)"), [$from, $to]);
                    // $query->where('from_date', $input['from_date']);
                    // $query->where('to_date', $input['to_date']);
                    // $query->whereNotBetween('from_time', [$input['from_time'], $input['to_time']]);
                    // $query->whereNotBetween('to_time', [$input['from_time'], $input['to_time']]);
                })->where('capacity', '>=', $input['capacity'])->get();
                // $sql = MeetingRoom::where('capacity', '<=', $input['capacity'])->toSql();
                // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , PHP_EOL.$sql , FILE_APPEND);

                // $available_rooms = MeetingRoom::where('capacity', '>=', $input['capacity'])->get();
            }
            

            return response()->json($available_rooms, 200);
        }
        catch (\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }





    /**
     * Room Bookings
     */
    public function addRoomBooking(){
        $input = Request::all();
        try{
            $user = JWTAuth::parseToken()->authenticate();

            $booking = new MeetingRoomBooking;
            $booking->room_id = $input['room_id'];
            $booking->booked_by_id = $user->id;
            $booking->reason = $input['reason'];
            if(!empty($input['reason_desc'])) $booking->reason_desc = $input['reason_desc'];
            $booking->from_date = date('Y-m-d', strtotime($input['from_date']));
            $booking->to_date = date('Y-m-d', strtotime($input['to_date']));
            $booking->from_time = $input['from_time'];
            $booking->to_time = $input['to_time'];

            $from_datetime = $booking->from_date.' '.$booking->from_time;
            $to_datetime = $booking->to_date.' '.$booking->to_time;

            // Check if room is already booked at that time
            $booked = DB::select("select * from meeting_room_bookings
            where ((DATE_ADD('".$from_datetime."', INTERVAL 1 MINUTE) between 
            cast(concat(from_date, ' ', from_time) as datetime) and cast(concat(to_date, ' ', to_time) as datetime)
            or DATE_SUB('".$to_datetime."', INTERVAL 1 MINUTE) between
            cast(concat(from_date, ' ', from_time) as datetime) and cast(concat(to_date, ' ', to_time) as datetime))
            or (cast(concat(from_date, ' ', from_time) as datetime) 
            between DATE_ADD('".$from_datetime."', INTERVAL 1 MINUTE) and DATE_SUB('".$to_datetime."', INTERVAL 1 MINUTE)
            or cast(concat(to_date, ' ', to_time) as datetime) 
            between DATE_ADD('".$from_datetime."', INTERVAL 1 MINUTE) and DATE_SUB('".$to_datetime."', INTERVAL 1 MINUTE)))
            and room_id = ".$booking->room_id." and deleted_at is null");

            if(!empty($booked)){
                return response()->json(array('msg' => 'already booked'), 409);
            }

            if($booking->save()){
                return response()->json(array('msg' => 'Room booked'), 200);
            }

        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRoomBookings(){
        try{
            $input = Request::all();
            $room_bookings = MeetingRoomBooking::with('room');
            
            // Room id
            if(array_key_exists('room_id', $input)){
                $room_bookings = $room_bookings->where('room_id', $input['room_id']);
            }

            // booked by
            if(array_key_exists('booked_by_id', $input)){
                $room_bookings = $room_bookings->where('booked_by_id', $input['booked_by_id']);
            }

            // from
            if(array_key_exists('from', $input)){
                $room_bookings = $room_bookings->where('from', $input['from']);
            }

            // to
            if(array_key_exists('to', $input)){
                $room_bookings = $room_bookings->where('to', $input['to']);
            }

            $response = $room_bookings->get();
            return response()->json($response, 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRoomBookingById($booking_id){
        try{
            $booking = MeetingRoomBooking::with('booked_by','room')->find($booking_id);
            return response()->json($booking, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function deleteRoomBooking($booking_id)
    {
        $deleted = MeetingRoomBooking::destroy($booking_id);

        if($deleted){
            return response()->json(['msg'=>"Booking removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Booking not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }

    public function editRoomBooking(){
        try{
            $input = Request::all();
            $booking = MeetingRoomBooking::findOrFail($input['id']);

            $booking->room_id = $input['room_id'];
            $booking->reason = $input['reason'];
            if(!empty($input['reason_desc'])) $booking->reason_desc = $input['reason_desc'];
            $booking->from_date = date('Y-m-d', strtotime($input['from_date']));
            $booking->to_date = date('Y-m-d', strtotime($input['to_date']));
            $booking->from_time = $input['from_time'];
            $booking->to_time = $input['to_time'];

            if($booking->save()){
                return Response()->json(array('msg' => 'Booking updated','resource' => $booking), 200);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }
}
?>