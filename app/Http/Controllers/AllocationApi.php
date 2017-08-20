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
use JWTAuth;
use Illuminate\Support\Facades\Request;
use App\Models\AllocationModels\Allocation;

class AllocationApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }


















    

    /**
     * Operation addAllocation
     *
     * Add a new allocation.
     *
     *
     * @return Http response
     */
    public function addAllocation()
    {

        $input = Request::all();


        $allocation = new Allocation;


        try{


            $form = Request::only(
                'account_id',
                'allocatable_id',
                'allocatable_type',
                'amount',
                'month',
                'percentage',
                'project_id',
                'purpose',
                'year'
                );


            $allocation->account_id             =               $form['account_id'];
            $allocation->allocatable_id         =               $form['allocatable_id'];
            $allocation->allocatable_type       =               $form['allocatable_type'];
            $allocation->amount_allocated       =               $form['amount'];
            $allocation->allocation_month       =               $form['month'];
            $allocation->percentage_allocated   =               $form['percentage'];
            $allocation->project_id             =               $form['project_id'];
            $allocation->allocation_purpose     =               $form['purpose'];
            $allocation->allocation_year        =               $form['year'];

            $user = JWTAuth::parseToken()->authenticate();
            $allocation->allocated_by_id            =   (int)   $user->id;


            if($allocation->save()) {


                $allocation->save();
                return Response()->json(array('success' => 'allocation added','allocation' => $allocation), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }


















    
    /**
     * Operation updateAllocation
     *
     * Update an existing allocation.
     *
     *
     * @return Http response
     */
    public function updateAllocation()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateAllocation');
        }
        $body = $input['body'];


        return response('How about implementing updateAllocation as a PUT method ?');
    }


















    
    /**
     * Operation deleteAllocation
     *
     * Deletes an allocation.
     *
     * @param int $allocation_id allocation id to delete (required)
     *
     * @return Http response
     */
    public function deleteAllocation($allocation_id)
    {

        $input = Request::all();


        $deleted_allocation = Allocation::destroy($allocation_id);


        if($deleted_allocation){
            return response()->json(['msg'=>"Allocation deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Allocation not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }


















    
    /**
     * Operation getAllocationById
     *
     * Find allocation by ID.
     *
     * @param int $allocation_id ID of allocation to return object (required)
     *
     * @return Http response
     */
    public function getAllocationById($allocation_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getAllocationById as a GET method ?');
    }


















    
    /**
     * Operation getAllocations
     *
     * allocations List.
     *
     *
     * @return Http response
     */
    public function getAllocations()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $allocation_id = $input['allocation_id'];


        return response('How about implementing getAllocations as a GET method ?');
    }
}
