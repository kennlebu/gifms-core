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

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BankingModels\BankAccount;
use App\Models\BankingModels\BankProjectBalances;
use Exception;
use Illuminate\Http\Request as HttpRequest;

class BankAccountApi extends Controller
{
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
        $form = Request::all();

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
        $form = Request::all();

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
        $deleted = BankAccount::destroy($bank_account_id);
        if($deleted){
            return response()->json(['msg'=>"bank_account deleted"], 200);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500);
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
        try{
            $response   = BankAccount::findOrFail($bank_account_id);           
            return response()->json($response, 200);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500);
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

        $qb = BankAccount::query();
        if(!array_key_exists('lean', $input)){
            $qb = BankAccount::with('currency');
        }
        $total_records = $qb->count();
        $records_filtered = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('account_number','like', '%' . $input['searchval']. '%');
                $query->orWhere('title','like', '%' . $input['searchval']. '%');
            });
            $records_filtered = (int) $qb->count;
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
                    $query->orWhere('account_number','like', '%' . $input['search']['value']. '%');
                    $query->orWhere('title','like', '%' . $input['search']['value']. '%');
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

            $response = BankAccount::arr_to_dt_response( 
                $qb->get(), $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $qb->get();
        }

        return response()->json($response, 200);
    }







    /** Bank Balances */
    public function addBankBalance(HttpRequest $request){
        try{
            $bank_balance = new BankProjectBalances();
            $bank_balance->balance = $request->balance;
            $bank_balance->balance_date = date('Y-m-d H:i:s');
            $bank_balance->accruals = $request->accruals ?? 0;

            $bank_balance->disableLogging();
            $bank_balance->save();
            return response()->json(['msg' => 'Bank balance added','bank_account' => $bank_balance], 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }

    public function updateBankBalance(HttpRequest $request){
        try{
            $bank_balance = BankProjectBalances::find($request->id);
            if(!empty($request->balance)){
                $bank_balance->balance = $request->balance;
            }
            $bank_balance->accruals = $request->accruals;
            $bank_balance->disableLogging();
            $bank_balance->save();
            return response()->json(['msg' => 'Bank balance updated','bank_account' => $bank_balance], 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }

    public function getBankBalance($id){
        try{
            $bank_balance = BankProjectBalances::find($id);
            return response()->json($bank_balance, 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }

    public function getBankBalances(){
        try{
            $input = Request::all();
            $bank_balances = BankProjectBalances::query();
            
            //ordering
            if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
                $order_direction = "asc";
                $order_column_name = $input['order_by'];
                if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                    $order_direction = $input['order_dir'];
                }
                $bank_balances = $bank_balances->orderBy($order_column_name, $order_direction);
            }
            else {
                // Order by balance date by default
                $bank_balances = $bank_balances->orderBy('balance_date');
            }

            //limit
            if(array_key_exists('limit', $input)){
                $bank_balances = $bank_balances->limit($input['limit']);
            }

            $bank_balances = $bank_balances->get();
            return response()->json($bank_balances, 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }

    public function deleteBankBalance($id){
        try{
            BankProjectBalances::destroy($id);
            return response()->json(['msg'=>"Bank balance removed"], 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }

    public function addCashReceived(HttpRequest $request){
        try{
            $bank_balance = BankProjectBalances::find($request->id);
            $bank_balance->cash_received = $bank_balance->cash_received ?? 0 + $request->cash_received;
            $bank_balance->disableLogging();
            $bank_balance->save();
            return response()->json(['msg' => 'Cash received added','bank_balance' => $bank_balance], 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>'Something went wrong', 'msg'=>$e->getTraceAsString()], 500);
        }
    }
}
