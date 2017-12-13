<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator mobile_payment_tariff.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\MobilePaymentModels\MobilePaymentTariff;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class MobilePaymentTariffApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addMobilePaymentTariff
     *
     * Add a new mobile_payment_tariff.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentTariff()
    {
        $form = Request::only(
            'min_limit',
            'max_limit',
            'tariff'
            );

        $mobile_payment_tariff = new MobilePaymentTariff;

            $mobile_payment_tariff->min_limit                   =         $form['min_limit'];
            $mobile_payment_tariff->max_limit                   =         $form['max_limit'];
            $mobile_payment_tariff->tariff                      =         $form['tariff'];

        if($mobile_payment_tariff->save()) {

            return Response()->json(array('msg' => 'Success: mobile_payment_tariff added','mobile_payment_tariff' => $mobile_payment_tariff), 200);
        }
    }
    




















    /**
     * Operation updateMobilePaymentTariff
     *
     * Update an existing mobile_payment_tariff.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentTariff()
    {
        $form = Request::only(
            'id',
            'min_limit',
            'max_limit',
            'tariff'
            );

        $mobile_payment_tariff = MobilePaymentTariff::find($form['id']);

            $mobile_payment_tariff->min_limit                   =         $form['min_limit'];
            $mobile_payment_tariff->max_limit                   =         $form['max_limit'];
            $mobile_payment_tariff->tariff                      =         $form['tariff'];

        if($mobile_payment_tariff->save()) {

            return Response()->json(array('msg' => 'Success: mobile_payment_tariff updated','mobile_payment_tariff' => $mobile_payment_tariff), 200);
        }
    }
    




















    /**
     * Operation deleteMobilePaymentTariff
     *
     * Deletes an mobile_payment_tariff.
     *
     * @param int $mobile_payment_tariff_id mobile_payment_tariff id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentTariff($mobile_payment_tariff_id)
    {
        $input = Request::all();


        $deleted = MobilePaymentTariff::destroy($mobile_payment_tariff_id);

        if($deleted){
            return response()->json(['msg'=>"mobile_payment_tariff deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"mobile_payment_tariff not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getMobilePaymentTariffById
     *
     * Find mobile_payment_tariff by ID.
     *
     * @param int $mobile_payment_tariff_id ID of mobile_payment_tariff to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentTariffById($mobile_payment_tariff_id)
    {
        $input = Request::all();

        try{

            $response   = MobilePaymentTariff::findOrFail($mobile_payment_tariff_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"mobile_payment_tariff could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation mobilePaymentTariffsGet
     *
     * mobile_payment_tariffs List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentTariffsGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('mobile_payment_tariffs');

        $qb->whereNull('mobile_payment_tariffs.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('mobile_payment_tariffs.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('mobile_payment_tariffs.min_limit','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('mobile_payment_tariffs.max_limit','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('mobile_payment_tariffs.tariff','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = MobilePaymentTariff::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb->orderBy($order_column_name, $order_direction);
        }else{
            //$qb->orderBy("project_code", "asc");
        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


        }

        //migrated
        if(array_key_exists('migrated', $input)){

            $mig = (int) $input['migrated'];

            if($mig==0){
                $qb->whereNull('migration_id');
            }else if($mig==1){
                $qb->whereNotNull('migration_id');
            }


        }



        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('mobile_payment_tariffs.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('mobile_payment_tariffs.min_limit','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('mobile_payment_tariffs.max_limit','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('mobile_payment_tariffs.tariff','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = MobilePaymentTariff::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];


            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){

                $qb->orderBy($order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = MobilePaymentTariff::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = MobilePaymentTariff::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = MobilePaymentTariff::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $mobile_payment_tariffs = MobilePaymentTariff::find($data[$key]['id']);


        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            // if($data[$key]["account"]==null){
            //     $data[$key]["account"] = array("account_name"=>"N/A");
            // }


        }

        return $data;


    }
}
