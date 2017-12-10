<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator bank_account.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BankingModels\BankAccount;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class BankAccountApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addBankAccount
     *
     * Add a new bank_account.
     *
     *
     * @return Http response
     */
    public function addBankAccount()
    {
        $form = Request::only(
            'account_number',
            'bank_name',
            'bank_branch',
            'title'
            );

        $bank_account = new BankAccount;

            $bank_account->account_number                   =         $form['account_number'];
            $bank_account->bank_name                        =         $form['bank_name'];
            $bank_account->bank_branch                      =         $form['bank_branch'];
            $bank_account->title                            =         $form['title'];

        if($bank_account->save()) {

            return Response()->json(array('msg' => 'Success: bank_account added','bank_account' => $bank_account), 200);
        }
    }
    




















    /**
     * Operation updateBankAccount
     *
     * Update an existing bank_account.
     *
     *
     * @return Http response
     */
    public function updateBankAccount()
    {
        $form = Request::only(
            'id',
            'account_number',
            'bank_name',
            'bank_branch',
            'title'
            );

        $bank_account = BankAccount::find($form['id']);

            $bank_account->account_number                   =         $form['account_number'];
            $bank_account->bank_name                        =         $form['bank_name'];
            $bank_account->bank_branch                      =         $form['bank_branch'];
            $bank_account->title                            =         $form['title'];

        if($bank_account->save()) {

            return Response()->json(array('msg' => 'Success: bank_account updated','bank_account' => $bank_account), 200);
        }
    }
    




















    /**
     * Operation deleteBankAccount
     *
     * Deletes an bank_account.
     *
     * @param int $bank_account_id bank_account id to delete (required)
     *
     * @return Http response
     */
    public function deleteBankAccount($bank_account_id)
    {
        $input = Request::all();


        $deleted = BankAccount::destroy($bank_account_id);

        if($deleted){
            return response()->json(['msg'=>"bank_account deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"bank_account not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getBankAccountById
     *
     * Find bank_account by ID.
     *
     * @param int $bank_account_id ID of bank_account to return object (required)
     *
     * @return Http response
     */
    public function getBankAccountById($bank_account_id)
    {
        $input = Request::all();

        try{

            $response   = BankAccount::findOrFail($bank_account_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"bank_account could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation bankAccountsGet
     *
     * bank_accounts List.
     *
     *
     * @return Http response
     */
    public function bankAccountsGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('bank_accounts');

        $qb->whereNull('bank_accounts.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('bank_accounts.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('bank_accounts.account_number','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('bank_accounts.title','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = BankAccount::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "desc";
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
                
                $query->orWhere('bank_accounts.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('bank_accounts.account_number','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('bank_accounts.title','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = BankAccount::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = BankAccount::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = BankAccount::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = BankAccount::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $bank_accounts = BankAccount::find($data[$key]['id']);


            $data[$key]['currency']                    = $bank_accounts->currency;
        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }


        }

        return $data;


    }
}
