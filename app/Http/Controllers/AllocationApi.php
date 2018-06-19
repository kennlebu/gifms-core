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
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\AccountingModels\Account;
use App\Models\ProjectsModels\Project;
use Excel;

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
                'year',
                'allocation_step'
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
            $allocation->allocation_step        =               $form['allocation_step'];

            $user = JWTAuth::parseToken()->authenticate();
            $allocation->allocated_by_id            =   (int)   $user->id;


            if($allocation->save()) {
                activity()
                   ->performedOn($allocation->allocatable)
                   ->causedBy($user)
                   ->log('allocated');
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




        try{


            $form = Request::only(
                'id',
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


            $allocation = Allocation::findOrFail($form['id']);


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


                $user = JWTAuth::parseToken()->authenticate();
                activity()
                   ->performedOn($allocation->allocatable)
                   ->causedBy($user)
                   ->log('re-allocated');
                $allocation->save();
                return Response()->json(array('success' => 'allocation updated','allocation' => $allocation), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }
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


        $allocation = Allocation::findOrFail($allocation_id);
        $user = JWTAuth::parseToken()->authenticate();
        activity()
           ->performedOn($allocation->allocatable)
           ->causedBy($user)
           ->log('de-allocated');
               
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
        $response = [];

        try{
            $response   = Allocation::with( 
                                        'allocatable',
                                        'allocated_by',
                                        'project',
                                        'account'
                                    )->findOrFail($allocation_id);


            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"Allocation could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
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





    /**
     * Operation uploadAllocations
     * CSV file with allocations
     * @return Http response
     */
    public function uploadAllocations(){
        $form = Request::all();

        try{
            $file = $form['file'];
            $payable_type = $form['payable_type'];
            $payable_id = $form['payable_id'];            
            $user = JWTAuth::parseToken()->authenticate();
            $payable = null;
            $total = 0;

            if($payable_type=='claims'){
                $payable = Claim::find($payable_id);
                $total = $payable->total;
            }
            else if($payable_type=='advances'){
                $payable = Advance::find($payable_id);
                $total = $payable->total;
            }
            else if($payable_type=='invoices'){
                $payable = Invoice::find($payable_id);
                $total = $payable->total;
            }
            else if($payable_type=='mobile_payments'){
                $payable = MobilePayment::find($payable_id)->with('totals');
                $total = $payable->totals;
            }

            $data = Excel::load($file->getPathname(), function($reader) {
            })->get()->toArray();

            $allocations_array = array();
            foreach ($data as $key => $value) {
                $allocation = new Allocation();

                try{
                    $project = Project::where('project_code','like', '%'.trim($value['pid']).'%')->firstOrFail();
                    $account = Account::where('account_code', 'like', '%'.trim($value['account_code']).'%')->firstOrFail();

                    $allocation->allocatable_id = $payable_id;
                    $allocation->allocatable_type = $payable_type;
                    $allocation->amount_allocated = $value['amount_allocation'];
                    $allocation->allocation_purpose = $value['specific_journal_rference'];
                    $allocation->percentage_allocated = (string) $this->getPercentage($value['amount_allocation'], $total);
                    $allocation->allocated_by_id =  (int) $user->id;
                    $allocation->account_id =  $account->id;
                    $allocation->project_id = $project->id;
                    array_push($allocations_array, $allocation);

                }
                catch(\Exception $e){
                    $response =  ["error"=>'Account or Project not found. Please use form to allocate.',
                                    "msg"=>$e->getMessage()];
                    return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
                }
            }

            foreach($allocations_array as $allocation){
                $allocation->save();
            }

            // Logging
            activity()
                ->performedOn($payable)
                ->causedBy($user)
                ->log('uploaded allocations');
            return Response()->json(array('success' => 'allocations added','payable' => $payable), 200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong'], 500);
        }
    }

    public function getPercentage($amount, $total){
        return ($amount / $total) * 100;
    }
}
