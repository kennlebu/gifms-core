<?php
namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinanceModels\TaxRate;


use Exception;
use App;
use Illuminate\Support\Facades\Response;

class TaxRatesApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















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
            return response()->json(['error'=>"Rate not found"], 404,array(),JSON_PRETTY_PRINT);
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

            $response =  ["error"=>"tax_rate could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
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

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());
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

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = TaxRate::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );

        }else{

            $sql            = TaxRate::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }



    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {
            $tax_rate = TaxRate::find($data[$key]['id']);
        }

        return $data;
    }


    public function append_relationships_nulls($data = array()){

        // foreach ($data as $key => $value) {
        // }

        return $data;
    }
}
