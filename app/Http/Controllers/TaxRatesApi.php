<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinanceModels\TaxRate;
use App\Models\FinanceModels\WithholdingVatRate;
use Exception;

class TaxRatesApi extends Controller
{
    /**
     * Operation addTaxRate
     *
     * Add a new tax_rate.
     *
     *
     * @return Http response
     */
    public function addTaxRate()
    {
        $form = Request::all();

        $tax_rate = new TaxRate;
        $tax_rate->charge = $form['charge'];
        $tax_rate->rate = $form['rate'];
        $tax_rate->type = $form['type'];
        $tax_rate->min_limit = $form['min_limit'];
        $tax_rate->max_limit = $form['max_limit'];

        if($tax_rate->save()) {
            return Response()->json(array('msg' => 'Success: New rate added','tax_rate' => $tax_rate), 200);
        }
    }
    

    /**
     * Operation updateTaxRate
     *
     * Update an existing tax_rate.
     *
     *
     * @return Http response
     */
    public function updateTaxRate()
    {
        $form = Request::all();

        $tax_rate = TaxRate::find($form['id']);
        $tax_rate->charge = $form['charge'];
        $tax_rate->rate = $form['rate'];
        $tax_rate->type = $form['type'];
        $tax_rate->min_limit = $form['min_limit'];
        $tax_rate->max_limit = $form['max_limit'];

        if($tax_rate->save()) {
            return Response()->json(array('msg' => 'Success: Rate updated','tax_rate' => $tax_rate), 200);
        }
    }




    /**
     * Operation deleteTaxRate
     * Deletes a tax_rate.
     * @param int $tax_rate_id tax_rate id to delete (required) 
     * @return Http response
     */
    public function deleteTaxRate($tax_rate_id)
    {
        $deleted = TaxRate::destroy($tax_rate_id);
        if($deleted){
            return response()->json(['msg'=>"Rate deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Somethign went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
    


    /**

     * Operation getTaxRate
     * Find tax_rate by ID.
     * @param int $tax_rate_id ID of tax_rate to return object (required)
     * @return Http response
     */
    public function getTaxRateById($tax_rate_id)
    {

        try{
            $response   = TaxRate::findOrFail($tax_rate_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation taxRatesGet
     *
     * tax_rate List.
     *
     * @return Http response
     */
    public function taxRatesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('tax_rates');
        $qb->whereNull('tax_rates.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('tax_rates.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('tax_rates.charge','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('tax_rates.rate','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('tax_rates.type','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('tax_rates.min_limit','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('tax_rates.max_limit','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }
            $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('tax_rates.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('tax_rates.charge','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('tax_rates.rate','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('tax_rates.type','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('tax_rates.min_limit','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('tax_rates.max_limit','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = TaxRate::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{

            $sql            = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }







    /**
     * Withholding rates
     */
    public function addWithholdingRate(){
        $input = Request::all();
        $rate = new WithholdingVatRate();
        $rate->rate = $input['rate'];
        $rate->save();
        return Response()->json(array('msg' => 'Success: Rate added','rate' => $rate), 200);
    }

    public function updateWithholdingRate()
    {
        $form = Request::all();
        $rate = WithholdingVatRate::find($form['id']);
        $rate->rate = $form['rate'];
        if($rate->save()) {
            return Response()->json(array('msg' => 'Success: Rate updated','rate' => $rate), 200);
        }
    }

    public function deleteWithholdingRate($id)
    {
        $deleted = WithholdingVatRate::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Rate deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Somethign went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    public function getWithholdingRate($id)
    {

        try{
            $response = WithholdingVatRate::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }

    public function getWithholdingRates(){
        $rates = WithholdingVatRate::query();
        $input = Request::all();
        $response_dt = null;
        $total_records = $rates->count();
        $records_filtered = 0;

        if(array_key_exists('datatables', $input)){
            $records_filtered = $rates->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $rates->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $rates->limit($input['length'])->offset($input['start']);
            }else{
                $rates->limit($input['length']);
            }

            $response_dt = $rates->get();
            $rates = WithholdingVatRate::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
            );
        }
        else{
            $rates = $rates->get();
        }

        return response()->json($rates, 200,array(),JSON_PRETTY_PRINT);
    }
}
