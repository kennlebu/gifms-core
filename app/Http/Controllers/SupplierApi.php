<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator supplier.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\SuppliesModels\Supplier;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class SupplierApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addSupplier
     *
     * Add a new supplier.
     *
     *
     * @return Http response
     */
    public function addSupplier()
    {
        $form = Request::only(
            'bank_id',
            'bank_branch_id',
            'supplier_name',
            'address',
            'telephone',
            'email',
            'website',
            'bank_account',
            'mobile_payment_number',
            'chaque_address',
            'payment_mode_id',
            'bank_code',
            'swift_code',
            'usd_account',
            'alternative_email',
            'currency_id',
            'mobile_payment_name',
            'city_id',
            'qb',
            'status_id',
            'staff_id',
            'password',
            'quick_books',
            'tax_pin',
            'contact_name_1',
            'contact_email_1',
            'contact_phone_1',
            'contact_name_2',
            'contact_email_2',
            'contact_phone_2'
            );

        $supplier = new Supplier;


            $supplier->bank_id                 =  (int)  $form['bank_id'];
            $supplier->bank_branch_id          =  (int)  $form['bank_branch_id'];
            $supplier->supplier_name           =         $form['supplier_name'];
            $supplier->address                 =         $form['address'];
            $supplier->telephone               =         $form['telephone'];
            $supplier->email                   =         $form['email'];
            $supplier->website                 =         $form['website'];
            $supplier->bank_account            =         $form['bank_account'];
            $supplier->mobile_payment_number   =         $form['mobile_payment_number'];
            $supplier->chaque_address          =         $form['chaque_address'];
            $supplier->payment_mode_id         =  (int)  $form['payment_mode_id'];
            $supplier->bank_code               =         $form['bank_code'];
            $supplier->swift_code              =         $form['swift_code'];
            $supplier->usd_account             =         $form['usd_account'];
            $supplier->alternative_email       =         $form['alternative_email'];
            $supplier->currency_id             =  (int)  $form['currency_id'];
            $supplier->mobile_payment_name     =         $form['mobile_payment_name'];
            $supplier->city_id                 =  (int)  $form['city_id'];
            $supplier->qb                      =         $form['qb'];
            $supplier->status_id               =  (int)  $form['status_id'];
            $supplier->staff_id                =  (int)  $form['staff_id'];
            $supplier->password                =         $form['password'];
            $supplier->quick_books             =         $form['quick_books'];
            $supplier->tax_pin                 =         $form['tax_pin'];
            $supplier->contact_name_1          =         $form['contact_name_1'];
            $supplier->contact_email_1         =         $form['contact_email_1'];
            $supplier->contact_phone_1         =         $form['contact_phone_1'];
            $supplier->contact_name_2          =         $form['contact_name_2'];
            $supplier->contact_email_2         =         $form['contact_email_2'];
            $supplier->contact_phone_2         =         $form['contact_phone_2'];

        if($supplier->save()) {

            return Response()->json(array('msg' => 'Success: supplier added','supplier' => $supplier), 200);
        }
    }
    




















    /**
     * Operation updateSupplier
     *
     * Update an existing supplier.
     *
     *
     * @return Http response
     */
    public function updateSupplier()
    {
        $form = Request::only(
            'id',
            'bank_id',
            'bank_branch_id',
            'supplier_name',
            'address',
            'telephone',
            'email',
            'website',
            'bank_account',
            'mobile_payment_number',
            'chaque_address',
            'payment_mode_id',
            'bank_code',
            'swift_code',
            'usd_account',
            'alternative_email',
            'currency_id',
            'mobile_payment_name',
            'city_id',
            'qb',
            'status_id',
            'staff_id',
            'password',
            'quick_books',
            'tax_pin',
            'contact_name_1',
            'contact_email_1',
            'contact_phone_1',
            'contact_name_2',
            'contact_email_2',
            'contact_phone_2'
            );

        $supplier = Supplier::find($form['id']);

            $supplier->bank_id                 =  (int)  $form['bank_id'];
            $supplier->bank_branch_id          =  (int)  $form['bank_branch_id'];
            $supplier->supplier_name           =         $form['supplier_name'];
            $supplier->address                 =         $form['address'];
            $supplier->telephone               =         $form['telephone'];
            $supplier->email                   =         $form['email'];
            $supplier->website                 =         $form['website'];
            $supplier->bank_account            =         $form['bank_account'];
            $supplier->mobile_payment_number   =         $form['mobile_payment_number'];
            $supplier->chaque_address          =         $form['chaque_address'];
            $supplier->payment_mode_id         =  (int)  $form['payment_mode_id'];
            $supplier->bank_code               =         $form['bank_code'];
            $supplier->swift_code              =         $form['swift_code'];
            $supplier->usd_account             =         $form['usd_account'];
            $supplier->alternative_email       =         $form['alternative_email'];
            $supplier->currency_id             =  (int)  $form['currency_id'];
            $supplier->mobile_payment_name     =         $form['mobile_payment_name'];
            $supplier->city_id                 =  (int)  $form['city_id'];
            $supplier->qb                      =         $form['qb'];
            $supplier->status_id               =  (int)  $form['status_id'];
            $supplier->staff_id                =  (int)  $form['staff_id'];
            $supplier->password                =         $form['password'];
            $supplier->quick_books             =         $form['quick_books'];
            $supplier->tax_pin                 =         $form['tax_pin'];
            $supplier->contact_name_1          =         $form['contact_name_1'];
            $supplier->contact_email_1         =         $form['contact_email_1'];
            $supplier->contact_phone_1         =         $form['contact_phone_1'];
            $supplier->contact_name_2          =         $form['contact_name_2'];
            $supplier->contact_email_2         =         $form['contact_email_2'];
            $supplier->contact_phone_2         =         $form['contact_phone_2'];
        if($supplier->save()) {

            return Response()->json(array('msg' => 'Success: supplier updated','supplier' => $supplier), 200);
        }
    }
    




















    /**
     * Operation deleteSupplier
     *
     * Deletes an supplier.
     *
     * @param int $supplier_id supplier id to delete (required)
     *
     * @return Http response
     */
    public function deleteSupplier($supplier_id)
    {
        $input = Request::all();


        $deleted = Supplier::destroy($supplier_id);

        if($deleted){
            return response()->json(['msg'=>"supplier deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"supplier not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getSupplierById
     *
     * Find supplier by ID.
     *
     * @param int $supplier_id ID of supplier to return object (required)
     *
     * @return Http response
     */
    public function getSupplierById($supplier_id)
    {
        $input = Request::all();

        try{

            $response   = Supplier::findOrFail($supplier_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"supplier could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation suppliersGet
     *
     * suppliers List.
     *
     *
     * @return Http response
     */
    public function suppliersGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('suppliers');

        $qb->whereNull('suppliers.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('suppliers.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('suppliers.supplier_name','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Supplier::bind_presql($qb->toSql(),$qb->getBindings());
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
            // $qb->orderBy("supplier_name", "asc");
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
                
                $query->orWhere('suppliers.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.supplier_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.address','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.telephone','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.email','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.website','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.bank_account','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.city_id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.contact_name_1','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('suppliers.contact_name_2','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Supplier::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];


            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){

                $qb->orderBy($order_column_name, $order_direction);

            }else{
                $qb->orderBy("supplier_name", "desc");
            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = Supplier::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Supplier::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{


            $qb->orderBy("supplier_name", "asc");

            $sql            = Supplier::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);

            //with_assigned_projects
            if(array_key_exists('detailed', $input)&& $input['detailed'] = "true"){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }

        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $suppliers = Supplier::find($data[$key]['id']);

            $data[$key]['bank']                = $suppliers->bank;
            $data[$key]['bank_branch']         = $suppliers->bank_branch;
            $data[$key]['payment_mode']        = $suppliers->payment_mode;
            $data[$key]['currency']            = $suppliers->currency;
            $data[$key]['city']                = $suppliers->city;
            $data[$key]['staff']               = $suppliers->staff;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["bank"]==null){
                $data[$key]["bank"] = array("bank_name"=>"N/A");
            }
            if($data[$key]["bank_branch"]==null){
                $data[$key]["bank_branch"] = array("branch_name"=>"N/A");
            }
            if($data[$key]["payment_mode"]==null){
                $data[$key]["payment_mode"] = array("payment_mode_description"=>"N/A");
            }
            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }
            if($data[$key]["city"]==null){
                $data[$key]["city"] = array("city_name"=>"N/A");
            }
            if($data[$key]["staff"]==null){
                $data[$key]["staff"] = array("full_name"=>"N/A");
            }


        }

        return $data;


    }
}
