<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdvancesModels\AdvanceStatus;
use App\Models\AdvancesModels\Advance;
use JWTAuth;

class AdvanceStatusApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

























    /**
     * Operation addAdvanceStatus
     *
     * Add a new advance_status.
     *
     *
     * @return Http response
     */
    public function addAdvanceStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addAdvanceStatus');
        }
        $body = $input['body'];


        return response('How about implementing addAdvanceStatus as a POST method ?');
    }
























    /**
     * Operation updateAdvanceStatus
     *
     * Update an existing advance_status.
     *
     *
     * @return Http response
     */
    public function updateAdvanceStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateAdvanceStatus');
        }
        $body = $input['body'];


        return response('How about implementing updateAdvanceStatus as a PUT method ?');
    }
























    /**
     * Operation deleteAdvanceStatus
     *
     * Deletes an advance_status.
     *
     * @param int $advance_status_id advance_status id to delete (required)
     *
     * @return Http response
     */
    public function deleteAdvanceStatus($advance_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteAdvanceStatus as a DELETE method ?');
    }
























    /**
     * Operation getAdvanceStatusById
     *
     * Find advance_status by ID.
     *
     * @param int $advance_status_id ID of advance_status to return object (required)
     *
     * @return Http response
     */
    public function getAdvanceStatusById($advance_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getAdvanceStatusById as a GET method ?');
    }
























    /**
     * Operation getAdvanceStatuses
     *
     * advance_statuses List.
     *
     *
     * @return Http response
     */
    public function getAdvanceStatuses()
    {
        
        $input = Request::all();
        $response;
        $qb = DB::table('advance_statuses');
        $qb->whereNull('deleted_at');

        $user = JWTAuth::parseToken()->authenticate();

        if(array_key_exists('allowed_only', $input)){

            $qb = $this->get_my_allowed_statuses($qb);

        }else{

            $qb->orderBy('advance_status', 'DESC');
        }


        $response = $qb->get();
        $response = json_decode(json_encode($response),true);

      


        //count lpos on each status
        foreach ($response as $key => $value) {


            $response[$key]['advances_count'] = Advance::where('requested_by_id',$this->current_user()->id)
                                            ->where('status_id', $value['id'] )
                                            ->count();


        }

        //add -1 and -2 statuses

        if(array_key_exists('allowed_only', $input)){

            //-1
            $response[]=array(
                    "id"=> -1,
                    "advance_status"=> "My Advances",
                    "order_priority"=> 999,
                    "display_color"=> "#37A9E17A",
                    "advances_count"=> Advance::where('requested_by_id',$this->current_user()->id)->count()
                  );


            if ($user->can('READ_ADVANCE_-2')){

                //-1
                $response[]=array(
                        "id"=> -2,
                        "advance_status"=> "All Advances",
                        "order_priority"=> 1000,
                        "display_color"=> "#092D50",
                        "advances_count"=> Advance::count()
                      );
            }

        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

    }

















    


    private function get_my_allowed_statuses($qb){


        $qb->orderBy('order_priority', 'ASC');

        return $qb;
    }
}
