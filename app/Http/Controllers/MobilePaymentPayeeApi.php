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
use App\Models\MobilePaymentModels\MobilePaymentPayee;
use DB;

class MobilePaymentPayeeApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }
































    /**
     * Operation addMobilePaymentPayee
     *
     * Add a new mobile_payment_payee.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentPayee()
    {
        $input = Request::all();


        $mobile_payment_payee = new MobilePaymentPayee;


        try{


            $form = Request::only(
                'mobile_payment_id',
                'full_name',
                'registered_name',
                'id_number',
                'mobile_number',
                'amount',
                'email'
                );


            $mobile_payment_payee->mobile_payment_id          =               $form['mobile_payment_id'];
            $mobile_payment_payee->full_name                  =               $form['full_name'];
            $mobile_payment_payee->registered_name            =               $form['registered_name'];
            $mobile_payment_payee->id_number                  =               $form['id_number'];
            $mobile_payment_payee->mobile_number              =               $form['mobile_number'];
            $mobile_payment_payee->amount                     =   (double)    $form['amount'];
            $mobile_payment_payee->email                      =               $form['email'];
            $mobile_payment_payee->withdrawal_charges         =               $mobile_payment_payee->calculated_withdrawal_charges;
            $mobile_payment_payee->total                      =               $mobile_payment_payee->calculated_total;


            if($mobile_payment_payee->save()) {


                $mobile_payment_payee->save();
                return Response()->json(array('success' => 'mobile payment payee added','mobile_payment_payee' => $mobile_payment_payee), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }































    /**
     * Operation updateMobilePaymentPayee
     *
     * Update an existing mobile_payment_payee.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentPayee()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentPayee');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentPayee as a PUT method ?');
    }































    /**
     * Operation deleteMobilePaymentPayee
     *
     * Deletes an mobile_payment_payee.
     *
     * @param int $mobile_payment_payee_id mobile_payment_payee id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentPayee($mobile_payment_payee_id)
    {
        $input = Request::all();


        $deleted_mobile_payment_payee = MobilePaymentPayee::destroy($mobile_payment_payee_id);


        if($deleted_mobile_payment_payee){
            return response()->json(['msg'=>"Mobile payment payee deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Mobile payment payee not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }































    /**
     * Operation getMobilePaymentPayeeById
     *
     * Find mobile_payment_payee by ID.
     *
     * @param int $mobile_payment_payee_id ID of mobile_payment_payee to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentPayeeById($mobile_payment_payee_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentPayeeById as a GET method ?');
    }































    /**
     * Operation mobilePaymentPayeesGet
     *
     * mobile_payment_payees List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentPayeesGet()
    {
        $input = Request::all();
    }





























    private function get_withdrawal_charges($amount){

        $withdrawal_charges = 0 ;


        $tariff_res = DB::table('mobile_payment_tariffs')
        ->select(DB::raw('tariff'))
        ->where('min_limit', '<=', $amount)
        ->where('max_limit', '>=', $amount)
        ->get();

        foreach ($tariff_res as $key => $value) {

            $withdrawal_charges = (double)  $value->tariff ;

        }

        return $withdrawal_charges;
        
    }




























    private function get_total($amount){

        return  $amount + $this->get_withdrawal_charges($amount);
    }

}
