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
use App\Models\SuppliesModels\SupplierDocument;
use Exception;
use Excel;
use Illuminate\Http\Request as HttpRequest;
use Anchu\Ftp\Facades\Ftp;
use App\Mail\Generic;
use App\Models\SuppliesModels\QuoteRequest;
use App\Models\SuppliesModels\SupplierService;
use App\Models\SuppliesModels\SupplierSupplyCategory;
use Illuminate\Support\Facades\Mail;

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
        $form = Request::all();

        $supplier = new Supplier;
        $supplier->bank_id                 = $form['bank_id'];
        $supplier->bank_branch_id          = $form['bank_branch_id'];
        $supplier->supplier_name           = $form['supplier_name'];
        $supplier->trading_name            = $form['trading_name'];
        $supplier->address                 = $form['address'] ?? null;
        $supplier->telephone               = $form['telephone'] ?? null;
        $supplier->email                   = $form['email'] ?? null;
        $supplier->website                 = $form['website'] ?? null;
        $supplier->bank_account            = $form['bank_account'] ?? null;
        $supplier->mobile_payment_number   = $form['mobile_payment_number'] ?? null;
        $supplier->chaque_address          = $form['chaque_address'] ?? null;
        $supplier->payment_mode_id         = $form['payment_mode_id'] ?? null;
        $supplier->bank_code               = $form['bank_code'] ?? null;
        $supplier->usd_account             = $form['usd_account'] ?? null;
        $supplier->swift_code              = $form['swift_code'] ?? null;
        $supplier->alternative_email       = $form['alternative_email'] ?? null;
        $supplier->mobile_payment_name     = $form['mobile_payment_name'] ?? null;
        $supplier->tax_pin                 = trim($form['tax_pin']);
        $supplier->contact_name_1          = $form['contact_name_1'] ?? null;
        $supplier->contact_email_1         = $form['contact_email_1'] ?? null;
        $supplier->contact_phone_1         = $form['contact_phone_1'] ?? null;
        $supplier->contact_name_2          = $form['contact_name_2'] ?? null;
        $supplier->contact_email_2         = $form['contact_email_2'] ?? null;
        $supplier->contact_phone_2         = $form['contact_phone_2'] ?? null;
        $supplier->requires_lpo            = $form['requires_lpo'] ?? null;
        $supplier->supply_category_id      = $form['supply_category_id'] ?? null;
        $supplier->county_id               = $form['county_id'] ?? null;

        if(Supplier::where('tax_pin', $supplier->tax_pin)->exists()){
            return response()->json(["error"=>"Supplier with the same tax pin already exists"], 409);
        }

        if($supplier->save()) {
            foreach($form['supply_categories'] as $s_cat){
                SupplierSupplyCategory::create([
                    'supplier_id' => $supplier->id,
                    'supply_category_id' => $s_cat['id']
                ]);
            }

            $supplier = Supplier::with('bank','bank_branch','payment_mode','county','supply_category','documents.added_by','supply_categories')->find($supplier->id);
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
        $form = Request::all();

        $supplier = Supplier::find($form['id']);
        $supplier->bank_id                 =         $form['bank_id'];
        $supplier->bank_branch_id          =         $form['bank_branch_id'];
        $supplier->supplier_name           =         $form['supplier_name'];
        $supplier->trading_name            =         $form['trading_name'] ?? null;
        $supplier->address                 =         $form['address'];
        $supplier->telephone               =         $form['telephone'];
        $supplier->email                   =         $form['email'];
        $supplier->website                 =         $form['website'];
        $supplier->bank_account            =         $form['bank_account'];
        $supplier->mobile_payment_number   =         $form['mobile_payment_number'];
        $supplier->chaque_address          =         $form['chaque_address'];
        $supplier->payment_mode_id         =         $form['payment_mode_id'];
        $supplier->bank_code               =         $form['bank_code'];
        $supplier->usd_account             =         $form['usd_account'];
        $supplier->swift_code              =         $form['swift_code'];
        $supplier->alternative_email       =         $form['alternative_email'];
        $supplier->mobile_payment_name     =         $form['mobile_payment_name'];
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
        
        foreach($supplier->supply_categories as $old_cat){
            SupplierSupplyCategory::where('supplier_id', $supplier->id)->where('supply_category_id',  $old_cat->id)->delete();
        }

        if(!empty($form['supply_categories'])){
            foreach($form['supply_categories'] as $new_cat){
                SupplierSupplyCategory::create([
                    'supplier_id' => $supplier->id,
                    'supply_category_id' => $new_cat['id']
                ]);
            }
        }

        if(!empty($form['tax_pin']) && Supplier::where('tax_pin', $supplier->tax_pin)->where('id', '!=', $form['id'])->exists()){
            return response()->json(["error"=>"Supplier with the same tax pin already exists"], 409);
        }

        if($supplier->save()) {
            $supplier = Supplier::with('bank','bank_branch','payment_mode','county','supply_category','documents.added_by','supply_categories')->find($supplier->id);
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
            return response()->json(['msg'=>"supplier deleted"], 200);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500);
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
            $input = Request::all();
            $response = Supplier::with('bank','bank_branch','payment_mode','county','supply_category','documents.added_by',
                        'supply_categories.service_types')->findOrFail($supplier_id);  
            if(array_key_exists('with_transactions', $input)){
                $response['transactions'] = $response->getTransactionsAttribute();
            }
                     
            return response()->json($response, 200);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong" ,"msg"=>$e->getMessage()];
            return response()->json($response, 500);
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

        $qb = Supplier::query();
        if(!array_key_exists('lean', $input)){
            $qb = Supplier::with('bank','bank_branch','payment_mode','county','supply_category');
        }

        $qb = $qb->where(function($query){
            $query->whereNull('status_id')->orWhere('status_id', '!=', '1');
        });

        if(array_key_exists('with_disabled', $input)){
            // do nothing
        }
        else{            
            $qb = $qb->where(function($query){
                $query->whereNull('active')->orWhere('active', '!=', 'disabled');
            });
        }

        $total_records = $qb->count();
        $records_filtered = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {
                $query->orWhere('supplier_name','like', '%' . $input['searchval']. '%');
            });

            $records_filtered = (int) $qb->count();
        }

        // Supply category
        if(array_key_exists('supply_category_id', $input) && !empty($input['supply_category_id'])){
            $qb = $qb->where('supply_category_id', $input['supply_category_id']);
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

        // Donation recepients
        if(array_key_exists('donation', $input)){
            $qb = $qb->whereIn('supply_category_id', [21]);   // Government organisations
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb = $qb->where(function ($query) use ($input) {                
                $query->orWhere('supplier_name','like', '%' . $input['search']['value']. '%');
                $query->orWhere('trading_name','like', '%' . $input['search']['value']. '%');
                $query->orWhere('address','like', '%' . $input['search']['value']. '%');
                $query->orWhere('telephone','like', '%' . $input['search']['value']. '%');
                $query->orWhere('email','like', '%' . $input['search']['value']. '%');
                $query->orWhere('website','like', '%' . $input['search']['value']. '%');
                $query->orWhere('bank_account','like', '%' . $input['search']['value']. '%');
                $query->orWhere('city_id','like', '%' . $input['search']['value']. '%');
                $query->orWhere('contact_name_1','like', '%' . $input['search']['value']. '%');
                $query->orWhere('contact_name_2','like', '%' . $input['search']['value']. '%');
                $query->orWhere('tax_pin','like', '%' . $input['search']['value']. '%');
            });

            $records_filtered = (int) $qb->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb = $qb->orderBy($order_column_name, $order_direction);
            }
            else {
                $qb = $qb->orderBy("supplier_name", "desc");
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $qb = $qb->limit($input['length'])->offset($input['start']);
            }
            else {
                $qb = $qb->limit($input['length']);
            }

            $response_dt = $qb->get();
            $response = Supplier::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else {
            $qb = $qb->orderBy("supplier_name", "asc");
            $response = $qb->get();;
        }

        return response()->json($response, 200);
    }



    public function suppliersSearch(){
        $input = Request::all();
        $search_term = $input['search_term'];
        $category = $input['category'];
        $location = $input['location'];

        $suppliers = Supplier::with(['county', 'supply_category', 'payment_mode'])->where(function($query){
            $query->whereNull('status_id')->orWhere('status_id', '!=', '1');
        });
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
        return response()->json($results, 200);
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
                    $supplier->trading_name = trim($value['trading_name']);
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
                    return response()->json($response, 500);
                }
            }

            return response()->json(['msg'=>'finished'], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"An rerror occured during processing"], 500);
        }
    }





    public function downloadExcel(){
        // try {
            $suppliers = Supplier::with('bank','bank_branch','payment_mode','currency','county','supply_category')
                        ->where(function ($query) {
                            $query->where('status_id', '!=', '1')
                                ->orWhereNull('status_id');
                        })->get();
            $excel_data = [];

            foreach($suppliers as $row){
                $excel_row = [];
                $excel_row['id'] = $row->id;
                $excel_row['supplier_name'] = $row->supplier_name;
                $excel_row['trading_name'] = $row->trading_name;
                $excel_row['address'] = $row->address;
                $excel_row['telephone'] = " ".$row->telephone;
                $excel_row['email'] = $row->email;
                $excel_row['website'] = $row->website;
                $excel_row['bank'] = $row->bank->bank_name ?? '';
                $excel_row['bank_branch'] = $row->bank_branch->branch_name ?? '';
                $excel_row['cheque_address'] = $row->chaque_address;
                $excel_row['bank_account'] = " ".$row->bank_account;
                $excel_row['usd_account'] = " ".$row->usd_account;
                $excel_row['bank_code'] = " ".$row->bank_code;
                $excel_row['swift_code'] = $row->swift_code;
                $excel_row['tax_pin'] = $row->tax_pin;
                $excel_row['mobile_payment_number'] = " ".$row->mobile_payment_number;
                $excel_row['mobile_payment_name'] = $row->mobile_payment_name;
                $excel_row['payment_mode'] = $row->payment_mode->abrv ?? '';
                $excel_row['currency'] = $row->currency->currency_name ?? '';
                $excel_row['contact_name_1'] = $row->contact_name_1;
                $excel_row['contact_email_1'] = $row->contact_email_1;
                $excel_row['contact_phone_1'] = " ".$row->contact_phone_1;
                $excel_row['contact_name_2'] = $row->contact_name_2;
                $excel_row['contact_email_2'] = $row->contact_email_2;
                $excel_row['contact_phone_2'] = " ".$row->contact_phone_2;
                $excel_row['requires_lpo'] = $row->requires_lpo;
                $excel_row['supply_category'] = $row->supply_category->supply_category_name ?? '';
                $excel_row['county'] = $row->county->county_name ?? '';

                $excel_data[] = $excel_row;
            }
            $headers = [
                'Access-Control-Allow-Origin'      => '*',
                'Allow'                            => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true'
            ];
            $date = date('Y-m-d');
            // Build excel
            $file = Excel::create('Suppliers '.$date, function($excel) use ($excel_data, $date) {

                // Set the title
                $excel->setTitle('Suppliers '.$date);
    
                // Chain the setters
                $excel->setCreator('GIFMS')->setCompany('Clinton Health Access Initiative - Kenya');
    
                $excel->setDescription('A list of vendors in the GIFMS system as of '.$date);
    
                $headings = ['ID', 'Supplier Name', 'Trading Name', 'Address', 'Telephone', 'Email', 'Website', 'Bank name', 'Bank branch', 'Cheque address', 'Bank account', 'USD Account', 
                            'Bank code', 'Swift code', 'Tax PIN', 'Mobile Payment number', 'Mobile Payment name', 'Payment mode', 'Currency', 'Contact Name 1', 'Contact Email 1',
                            'Contact Phone 1', 'Contact Name 2', 'Contact Email 2', 'Contact Phone 2', 'Requires LPO', 'Supply Category', 'Location'];
    
                $excel->sheet('Suppliers', function ($sheet) use ($excel_data, $headings) {
                    // $i = 1;
                    // $alternate = true;
                    foreach($excel_data as $data_row){

                        $sheet->appendRow($data_row);
                        // if($alternate){
                        //     $sheet->cells('A'.$i.':AA'.$i, function($cells) {
                        //         $cells->setBackground('#edf1f3');  
                        //         $cells->setFontSize(10);                          
                        //     });
                        // }
                        // $i++;
                        // $alternate = !$alternate;
                    }
                    
                    $sheet->prependRow(1, $headings);
                    $sheet->setFontSize(10);
                    $sheet->setHeight(1, 25);
                    $sheet->row(1, function($row){
                        $row->setFontSize(11);
                        $row->setFontWeight('bold');
                        $row->setAlignment('center');
                        $row->setValignment('center');
                        $row->setBorder('none', 'thin', 'none', 'thin');
                        $row->setBackground('#004080');                        
                        $row->setFontColor('#ffffff');
                    });
                    $sheet->setWidth([
                        'A' => 10,
                        'B' => 50,
                        'C' => 50,
                        'D' => 50,
                        'E' => 30,
                        'F' => 50,
                        'G' => 35,
                        'H' => 35,
                        'I' => 35,
                        'J' => 50,
                        'K' => 35,
                        'L' => 35,
                        'M' => 15,
                        'N' => 20,
                        'O' => 20,
                        'P' => 35,
                        'Q' => 50,
                        'R' => 15,
                        'S' => 15,
                        'T' => 50,
                        'U' => 35,
                        'V' => 35,
                        'W' => 50,
                        'X' => 35,
                        'Y' => 35,
                        'Z' => 15,
                        'AA' => 50,
                        'AB' => 15
                    ]);

                    $sheet->setFreeze('A2');
                });
    
            })->download('xlsx', $headers);

        // }
        // catch(\Exception $e){
        //     return response()->json(["error"=>"Something went wrong","msg"=>$e->getMessage(),"trace"=>$e->getTraceAsString()], 500,array(),JSON_PRETTY_PRINT);
        // }
    }


    public function addDocument(HttpRequest $request){
        try{
            if(empty($request->document_id)){
                $document = new SupplierDocument();
                $document->supplier_id = $request->supplier_id;
                $document->filename = $this->current_user()->id.time();
            }
            else {
                $document = SupplierDocument::find($request->document_id);
            }            
            $document->title = $request->title;            
            $document->added_by_id = $this->current_user()->id;
            
            $file = $request->file;
            if(!empty($file) && $file != 0){
                $document->type = $file->getClientOriginalExtension();
            }
            $document->save();

            if(!empty($file) && $file != 0){
                Ftp::connection()->makeDir('/suppliers');
                Ftp::connection()->makeDir('/suppliers/'.$document->supplier_id);
                Ftp::connection()->uploadFile($file->getPathname(), '/suppliers/'.$document->supplier_id.'/'.$document->filename.'.'.$document->type);
            }
            

            return response(['msg'=>'Success', 'document'=>SupplierDocument::with('added_by')->find($document->id)], 200);
        }
        catch(Exception $e){
            return response(['error'=>'Something went wrong', 'msg'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()], 500);
        }
    }

    public function deleteDocument(HttpRequest $request){
        try{
            $document = SupplierDocument::findOrFail($request->id);
            Ftp::connection()->delete('/suppliers/'.$document->supplier_id.'/'.$document->filename.'.'.$document->type);
            $document->delete();
            return response()->json(['msg'=>"Document removed"], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage()], 500);
        }
    }

    public function changeActiveStatus(HttpRequest $request){
        try{
            $supplier = Supplier::find($request->supplier_id);
            if(empty($supplier->active) || $supplier->active == 'active'){
                $supplier->active = 'disabled';
            }
            else {
                $supplier->active = 'active';
            }
            $supplier->disableLogging();
            $supplier->save();

            // Logging
            activity()
                ->performedOn($supplier)
                ->causedBy($this->current_user())
                ->log('Changed status to '.$request->active);

            return response()->json(['msg'=>"Success", 'active'=>$supplier->active], 200);
        }
        catch (Exception $e){
            return response()->json(['error'=>"Something went wrong", 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getSupplierTransactions($supplier_id){
        try{
            $supplier = Supplier::findOrFail($supplier_id);
            $response = $supplier->getTransactionsAttribute();
                     
            return response()->json($response, 200);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong" ,"msg"=>$e->getMessage()];
            return response()->json($response, 500);
        }
    }

    public function requestQuote(HttpRequest $request){
        try{
            $to = $this->multiexplode([',',';'], $request->to);
            $cc = [];
            if(!empty($request->cc)){
                $cc = $this->multiexplode([',',';'], $request->cc);
            }
            $cc[] = $this->current_user()->email;

            $quote_request = new QuoteRequest();
            $quote_request->supplier_id = $request->supplier_id;
            $quote_request->to = $request->to;
            $quote_request->cc = $request->cc;
            $quote_request->bcc = $request->bcc;
            $quote_request->body = $request->body;
            $quote_request->requested_by_id = $this->current_user()->id;
            $quote_request->save();

            Mail::queue(new Generic(
                $to, $cc, 'Request for quote', 'Request for quote', [$request->body],
                null, false
            ));

            return response()->json(['msg'=>'Success'], 200);
        }
        catch(Exception $e){
            $response =  ["error"=>"Something went wrong" ,"msg"=>$e->getMessage()];
            return response()->json($response, 500);
        }
    }
}
