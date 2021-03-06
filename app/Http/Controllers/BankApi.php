<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator bank.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BankingModels\Bank;
use Exception;

class BankApi extends Controller
{
    /**
     * Operation addBank
     * Add a new bank.
     * @return Http response
     */
    public function addBank()
    {
        $form = Request::all();
        $bank = new Bank;
        $bank->bank_name = $form['bank_name'];
        $bank->swift_code = $form['swift_code'];
        $bank->bank_code = $form['bank_code'];
        if($bank->save()) {
            return Response()->json(array('msg' => 'Success: bank added','bank' => $bank), 200);
        }
    }


    /**
     * Operation updateBank
     * Update an existing bank.
     * @return Http response
     */
    public function updateBank()
    {
        $form = Request::all();
        $bank = Bank::find($form['id']);
        $bank->bank_name = $form['bank_name'];
        $bank->swift_code = $form['swift_code'];
        $bank->bank_code = $form['bank_code'];
        if($bank->save()) {
            return Response()->json(array('msg' => 'Success: bank updated','bank' => $bank), 200);
        }
    }



    /**
     * Operation deleteBank
     * Deletes an bank.
     * @param int $bank_id bank id to delete (required)
     * @return Http response
     */
    public function deleteBank($bank_id)
    {
        $deleted = Bank::destroy($bank_id);
        if($deleted){
            return response()->json(['msg'=>"bank deleted"], 200);
        }else{
            return response()->json(['error'=>"bank not found"], 500);
        }
    }



    /**
     * Operation getBankById
     * Find bank by ID.
     * @param int $bank_id ID of bank to return object (required)
     * @return Http response
     */
    public function getBankById($bank_id)
    {
        try{
            $response   = Bank::findOrFail($bank_id);           
            return response()->json($response, 200);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500);
        }
    }



    /**
     * Operation banksGet
     * banks List.
     * @return Http response
     */
    public function banksGet()
    {
        $input = Request::all();
        //query builder
        $qb = Bank::query();
        $total_records = $qb->count();
        $records_filtered = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {                
                $query->orWhere('bank_name','like', '%' . $input['searchval']. '%');
                $query->orWhere('bank_code','like', '%' . $input['searchval']. '%');
                $query->orWhere('swift_code','like', '%' . $input['searchval']. '%');
            });

            $records_filtered = (int) $qb->count();
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }
            $qb = $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb = $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            if(!empty($input['search']['value'])){
                $qb = $qb->where(function ($query) use ($input) {                
                    $query->orWhere('id','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('bank_name','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('bank_code','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('swift_code','like', '%' . $input['search']['value']. '%');
                });
            }

            $records_filtered = (int) $qb->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb = $qb->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $qb = $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb = $qb->limit($input['length']);
            }
            $response = Bank::arr_to_dt_response( 
                $qb->get(), $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $qb->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
