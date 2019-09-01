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

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\SuppliesModels\Supplier;
use App\Models\BankingModels\Bank;
use App\Models\BankingModels\BankBranch;
use App\Models\LookupModels\Currency;
use App\Models\PaymentModels\PaymentMode;
use App\Models\SuppliesModels\SupplyCategory;
use App\Models\LookupModels\County;
use Exception;
use Excel;

class SupplierApi extends Controller
{
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
            'contact_phone_2',
            'requires_lpo',
            'supply_category_id',
            'county_id'
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
        $supplier->tax_pin                 =         trim($form['tax_pin']);
        $supplier->contact_name_1          =         $form['contact_name_1'];
        $supplier->contact_email_1         =         $form['contact_email_1'];
        $supplier->contact_phone_1         =         $form['contact_phone_1'];
        $supplier->contact_name_2          =         $form['contact_name_2'];
        $supplier->contact_email_2         =         $form['contact_email_2'];
        $supplier->contact_phone_2         =         $form['contact_phone_2'];
        $supplier->requires_lpo            =         $form['requires_lpo'];
        $supplier->supply_category_id = $form['supply_category_id'];
        $supplier->county_id = $form['county_id'];

        if(Supplier::where('tax_pin', $supplier->tax_pin)->exists()){
            return response()->json(["error"=>"Supplier with the same tax pin already exists"], 409,array(),JSON_PRETTY_PRINT);
        }

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
            'contact_phone_2',
            'requires_lpo',
            'supply_category_id',
            'county_id'
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
        $supplier->tax_pin                 =         trim($form['tax_pin']);
        $supplier->contact_name_1          =         $form['contact_name_1'];
        $supplier->contact_email_1         =         $form['contact_email_1'];
        $supplier->contact_phone_1         =         $form['contact_phone_1'];
        $supplier->contact_name_2          =         $form['contact_name_2'];
        $supplier->contact_email_2         =         $form['contact_email_2'];
        $supplier->contact_phone_2         =         $form['contact_phone_2'];
        $supplier->requires_lpo            =         $form['requires_lpo'];
        $supplier->supply_category_id = $form['supply_category_id'];
        $supplier->county_id = $form['county_id'];

        if(!empty($form['tax_pin']) && Supplier::where('tax_pin', $supplier->tax_pin)->where('id', '!=', $form['id'])->exists()){
            return response()->json(["error"=>"Supplier with the same tax pin already exists"], 409,array(),JSON_PRETTY_PRINT);
        }

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
        $deleted = Supplier::destroy($supplier_id);
        if($deleted){
            return response()->json(['msg'=>"supplier deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
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
        try{
            $response   = Supplier::findOrFail($supplier_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
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
        $qb->where('status_id', '!=', '1');

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

            $sql = Supplier::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
        }

        // Supply category
        if(array_key_exists('supply_category_id', $input) && !empty($input['supply_category_id'])){
            $qb->where('supply_category_id', $input['supply_category_id']);
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

        // Donation recepients
        if(array_key_exists('donation', $input)){
            $qb->whereIn('supply_category_id', [10,21]);
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
                $query->orWhere('suppliers.tax_pin','like', '\'%' . $input['search']['value']. '%\'');
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
            }
            else {
                $qb->orderBy("supplier_name", "desc");
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);
            }
            else {
                $qb->limit($input['length']);
            }

            $sql = Supplier::bind_presql($qb->toSql(),$qb->getBindings());
            $response_dt = DB::select($sql);

            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Supplier::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else {

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



    public function suppliersSearch(){
        $input = Request::all();
        $search_term = $input['search_term'];
        $category = $input['category'];
        $location = $input['location'];

        $suppliers = Supplier::with(['county', 'supply_category', 'payment_mode'])->where('status_id', '!=', '1');
        if(!empty($search_term)){
            $suppliers->where('supplier_name', 'like', '%'.$search_term.'%');
        }
        if(!empty($category)){
            $suppliers->where('supply_category_id', $category);
        }
        if(!empty($location)){
            $suppliers->where('county_id', $location);
        }

        $results = $suppliers->get();
        return response()->json($results, 200,array(),JSON_PRETTY_PRINT);
    }





    public function uploadExcel(){
        try{
            $form = Request::only("file");
            $file = $form['file'];

            $data = Excel::load($file->getPathname(), function($reader) {
            })->get()->toArray();

            foreach ($data as $key => $value) {
                try{                    
                    $supplier = new Supplier;
                    $supplier->supplier_name = trim($value['supplier_name']);
                    $supplier->email = trim($value['email']);
                    $supplier->status_id = 0;

                    $telephone = trim($value['phone']);
                    if($this->startsWith($telephone, '254')) $telephone = '(+254)'.substr($telephone, 3);
                    elseif ($this->startsWith($telephone, '07')) $telephone = '(+254)'.substr($telephone, 1);
                    $supplier->telephone = $telephone;

                    if(!empty(trim($value['address'])))
                        $supplier->address = trim($value['address']);
                    if(!empty(trim($value['alternative_email'])))
                        $supplier->alternative_email = trim($value['alternative_email']);
                    if(!empty(trim($value['website'])))
                        $supplier->website = trim($value['website']);
                    if(!empty(trim($value['mobile_payment_number']))){
                        $alt_num = trim($value['mobile_payment_number']);
                        if($this->startsWith($alt_num, '254')) $alt_num = '(+254)'.substr($alt_num, 3);
                        elseif ($this->startsWith($alt_num, '07')) $alt_num = '(+254)'.substr($alt_num, 1);
                        $supplier->mobile_payment_number = $alt_num;
                    }
                    if(!empty(trim($value['mobile_payment_name'])))
                        $supplier->mobile_payment_name = trim($value['mobile_payment_name']);
                    if(!empty(trim($value['bank_account_no'])))
                        $supplier->bank_account = trim($value['bank_account_no']);
                    if(!empty(trim($value['bank']))){
                        $bank = Bank::where('bank_name', 'like', '%' .trim($value['bank']). '%')->first();
                        if(!empty($bank))
                            $supplier->bank_id = $bank->id;
                    }
                    if(!empty(trim($value['branch']))){
                        $branch = BankBranch::where('branch_name', 'like', '%' .trim($value['branch']). '%')->first();
                        if(!empty($branch))
                            $supplier->bank_branch_id = $branch->id;
                    }
                    if(!empty(trim($value['cheque_address'])))
                        $supplier->chaque_address = trim($value['cheque_address']);
                    if(!empty(trim($value['bank_code'])))
                        $supplier->bank_code = trim($value['bank_code']);
                    if(!empty(trim($value['usd_account'])))
                        $supplier->usd_account = trim($value['usd_account']);
                    if(!empty(trim($value['tax_pin'])))
                        $supplier->tax_pin = trim($value['tax_pin']);
                    if(!empty(trim($value['currency']))){
                        $currency = Currency::where('currency_name', 'like', '%' .trim($value['currency']). '%')->first();
                        if(!empty($currency))
                            $supplier->currency_id = $currency->id;
                    }
                    if(!empty(trim($value['payment_mode']))){
                        $payment_mode = PaymentMode::where('abrv', 'like', '%' .trim($value['payment_mode']). '%')->first();
                        if(!empty($payment_mode))
                            $supplier->payment_mode_id = $payment_mode->id;
                    }
                    $supplier->contact_name_1 = trim($value['contact_person_1_name']);
                    $supplier->contact_email_1 = trim($value['contact_person_1_email']);

                    $phone1 = trim($value['contact_person_1_phone']);
                    if($this->startsWith($phone1, '254')) $phone1 = '(+254)'.substr($phone1, 3);
                    elseif ($this->startsWith($phone1, '07')) $phone1 = '(+254)'.substr($phone1, 1);
                    $supplier->contact_phone_1 = $phone1;

                    if(!empty(trim($value['contact_person_2_name'])))
                        $supplier->contact_name_2 = trim($value['contact_person_2_name']);
                    if(!empty(trim($value['contact_person_2_email'])))
                        $supplier->contact_email_2 = trim($value['contact_person_2_email']);
                    if(!empty(trim($value['contact_person_2_phone']))){
                        $phone2 = trim($value['contact_person_2_phone']);
                        if($this->startsWith($phone2, '254')) $phone2 = '(+254)'.substr($phone2, 3);
                        elseif ($this->startsWith($phone2, '07')) $phone2 = '(+254)'.substr($phone2, 1);
                        $supplier->contact_phone_2 = $phone2;
                    }
                    if(!empty(trim($value['supplier_category']))){
                        $supply_category = SupplyCategory::where('supply_category_name', 'like', '%' .trim($value['supplier_category']). '%')->first();
                        if(!empty($supply_category))
                            $supplier->supply_category_id = $supply_category->id;
                    }
                    if(!empty(trim($value['location_county']))){
                        $county = County::where('county_name', 'like', '%' .trim($value['location_county']). '%')->first();
                        if(!empty($county))
                            $supplier->county_id = $county->id;
                    }
                    if(trim($value['requires_lpo']) == 'Yes' || trim($value['requires_lpo']) == 'yes'){
                        $supplier->requires_lpo = 'Yes';
                    }
                    else {
                        $supplier->requires_lpo = 'No';
                    }                   

                    $supplier->save();
                }
                catch(\Exception $e){
                    $response =  ["error"=>'An error occurred during processing.',
                                    "msg"=>$e->getMessage()];
                    return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
                }
            }

            return response()->json(['msg'=>'finished'], 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
