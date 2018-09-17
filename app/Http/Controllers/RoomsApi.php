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
            $available_rooms = array();

            $available_rooms = MeetingRoom::whereHas('bookings', function ($query) use ($input){
                $query->where('from', '<>', $input['from'])->where('to', '<>', $input['to']); 
            })->where('capacity', '<=', $input['capacity']);
            return response()->json($available_rooms, 200);
        }
        catch (\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }
}
?>