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
            $rooms = MeetingRoom::all();
            return response()->json($rooms, 200);
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
                // $available_rooms = MeetingRoom::whereHas('bookings', function ($query) use ($from, $to){
                //     $query->whereNotBetween('from', [$from, $to])->orWhereNull('from');
                //     $query->whereNotBetween('to', [$from, $to])->orWhereNull('to');
                //     // $query->whereNotBetween('from', [$from, $to]);
                //     // $query->whereNotBetween('to', [$from, $to]);
                // })->where('capacity', '<=', $input['capacity'])->get();
                $sql = MeetingRoom::where('capacity', '<=', $input['capacity'])->toSql();
                // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , PHP_EOL.$sql , FILE_APPEND);

                $available_rooms = MeetingRoom::where('capacity', '>=', $input['capacity'])->get();
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

            $booking = new MeetingRoomBooking;
            $booking->room_id = $input['room_id'];
            $booking->booked_by_id = $input['booked_by_id'];
            $booking->reason = $input['reason'];
            $booking->reason_desc = $input['reason_desc'];
            $booking->from = $input['from'];
            $booking->to = $input['to'];
            $booking->save();

            return response()->json(array('msg' => 'Room booked'), 200);

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

            return response()->json($room_bookings, 200);
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
        $deleted = MeetingRoom::destroy($booking_id);

        if($deleted){
            return response()->json(['msg'=>"Booking removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Booking not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }

    public function editRoomBooking($booking_id){
        try{
            $booking = MeetingRoomBooking::findOrFail($booking_id);

            $booking->room_id = $input['room_id'];
            $booking->booked_by_id = $input['booked_by_id'];
            $booking->reason = $input['reason'];
            $booking->reason_desc = $input['reason_desc'];
            $booking->from = $input['from'];
            $booking->to = $input['to'];

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