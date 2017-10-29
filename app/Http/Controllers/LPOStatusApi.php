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
use App\Models\LPOModels\LpoStatus;
use App\Models\LPOModels\Lpo;

class LPOStatusApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }
















    /**
     * Operation addLpoStatus
     *
     * Add a new lpo status to the store.
     *
     *
     * @return Http response
     */
    public function addLpoStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addLpoStatus');
        }
        $body = $input['body'];


        return response('How about implementing addLpoStatus as a POST method ?');
    }
























    /**
     * Operation updateLpoStatus
     *
     * Update an existing LPO Status.
     *
     *
     * @return Http response
     */
    public function updateLpoStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpoStatus');
        }
        $body = $input['body'];


        return response('How about implementing updateLpoStatus as a PUT method ?');
    }

























    /**
     * Operation deleteLpoStatus
     *
     * Deletes an lpo_status.
     *
     * @param int $lpo_status_id lpo status id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpoStatus($lpo_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteLpoStatus as a DELETE method ?');
    }





























    /**
     * Operation getLpoStatusById
     *
     * Find lpo by ID.
     *
     * @param int $lpo_status_id ID of lpo status to return object (required)
     *
     * @return Http response
     */
    public function getLpoStatusById($lpo_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getLpoStatusById as a GET method ?');
    }


























    /**
     * Operation lpoStatusesGet
     *
     * lpo statuses List.
     *
     *
     * @return Http response
     */
    public function lpoStatusesGet()
    {
        // $input = Request::all();

        // $response;

        // //path params validation


        // //if status is set

        // if(array_key_exists('staff_id', $input)){

     

        // }else{

        //      $response = LpoStatus::where("deleted_at",null)
        //         ->orderBy('lpo_status', 'desc')
        //         ->get();
        // }


           


        //     return response()->json($response, 200,array(),JSON_PRETTY_PRINT);



        $input = Request::all();
        $response;
        $qb = DB::table('lpo_statuses');
        $qb->whereNull('deleted_at');


        if(array_key_exists('allowed_only', $input)){

            $qb = $this->get_my_allowed_statuses($qb);

        }else{

            $qb->orderBy('lpo_status', 'DESC');
        }


        $response = $qb->get();
        $response = json_decode(json_encode($response),true);

      


        //count lpos on each status
        foreach ($response as $key => $value) {
            // $response[$key]['lpo_count'] = LpoStatus::find($value['id'])->lpo_count;


            $response[$key]['lpo_count'] = Lpo::where('requested_by_id',$this->current_user()->id)
                                            ->where('status_id', $value['id'] )
                                            ->count();


        }

        //add -1 and -2 statuses

        if(array_key_exists('allowed_only', $input)){

            //-1
            $response[]=array(
                    "id"=> -1,
                    "lpo_status"=> "My Lpos",
                    "order_priority"=> 999,
                    "display_color"=> "#37A9E17A",
                    "lpo_count"=> LPO::where('requested_by_id',$this->current_user()->id)->count()
                  );



            //-1
            $response[]=array(
                    "id"=> -2,
                    "lpo_status"=> "All Lpos",
                    "order_priority"=> 1000,
                    "display_color"=> "#092D50",
                    "lpo_count"=> LPO::count()
                  );

        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

    }





    private function get_my_allowed_statuses($qb){


        $qb->orderBy('order_priority', 'ASC');

        return $qb;
    }
}
