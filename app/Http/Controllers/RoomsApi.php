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
            $name = $input['name'];
            $location = $input['location'];
            $capacity = $input['capacity'];

            $room = new MeetingRoom;
            $room->name = $name;
            $room->location = $location;
            $room->capacity = $capacity;
            $room->save();

            return response()->json(array('msg' => 'Room added'), 200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getRooms(){
        try{
            $input = Request::all();
            $rooms = MeetingRoom::query();

            // not booked
            if(array_key_exists('not_booked', $input)){
                $from = date('Y-m-d', strtotime($input['from_date'])).' '.$input['from_time'].':00';
                $to = date('Y-m-d', strtotime($input['to_date'])).' '.$input['to_time'].':00';
            }

            $response = $rooms->get();
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
            $name = $input['name'];
            $location = $input['location'];
            $capacity = $input['capacity'];

            $room = MeetingRoom::findOrFail($input['room_id']);

            $room->name = $name;
            $room->location = $location;
            $room->capacity = $capacity;

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
            $booked = DB::select("select * from meeting_room_bookings where cast(concat(from_date, ' ', from_time) as datetime) between '".$from_datetime."' and '".$to_datetime."'
            and cast(concat(to_date, ' ', to_time) as datetime) between '".$from_datetime."' and '".$to_datetime."' and room_id = ".$booking->room_id." and deleted_at is null");

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
            $room_bookings = MeetingRoomBooking::query();
            
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
            $booking = MeetingRoomBooking::find($booking_id);
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