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
use App\Models\LPOModels\LpoQuotation;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LPOQuotationApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }









    /**
     * Operation addLpoQuotation
     *
     * Add a new lpo quotation.
     *
     *
     * @return Http response
     */
    public function addLpoQuotation()
    {
        // $input = Request::all();

        $lpo_quotation = new LpoQuotation;


        try{


            $form = Request::only(
                'lpo_id',
                'uploaded_by_id',
                'supplier_id',
                'amount',
                'file'
                );


            // FTP::connection()->changeDir('./lpos');

            $ftp = FTP::connection()->getDirListing();

            // print_r($form['file']);

            $file = $form['file'];


            $lpo_quotation->uploaded_by_id                      =   (int)       $form['uploaded_by_id'];
            $lpo_quotation->supplier_id                         =   (int)       $form['supplier_id'];
            $lpo_quotation->amount                              =   (double)    $form['amount'];
            $lpo_quotation->lpo_id                              =   (int)       $form['lpo_id'];


            if($lpo_quotation->save()) {

                FTP::connection()->makeDir('./lpos/'.$lpo_quotation->lpo_id.'/quotations/'.$lpo_quotation->id);
                FTP::connection()->makeDir('./lpos/'.$lpo_quotation->lpo_id.'/quotations/'.$lpo_quotation->id);
                FTP::connection()->uploadFile($file->getPathname(), './lpos/'.$lpo_quotation->lpo_id.'/quotations/'.$lpo_quotation->id.'/'.$lpo_quotation->id.'.'.$file->getClientOriginalExtension());

                $lpo_quotation->quotation_doc                   =   $lpo_quotation->id.'.'.$file->getClientOriginalExtension();
                $lpo_quotation->save();
                
                return Response()->json(array('success' => 'lpo quoatation added','lpo_quotation' => $lpo_quotation), 200);
            }


        }catch (JWTException $e){

            return response()->json(['error'=>'You are not Authenticated'], 500);

        }

    }




































    /**
     * Operation updateLpoQuotation
     *
     * Update an existing LPO Quotation.
     *
     *
     * @return Http response
     */
    public function updateLpoQuotation()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpoQuotation');
        }
        $body = $input['body'];


        return response('How about implementing updateLpoQuotation as a PUT method ?');
    }




















    /**
     * Operation deleteLpoQuotation
     *
     * Deletes an lpo_quotation.
     *
     * @param int $lpo_quotation_id lpo quotation id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpoQuotation($lpo_quotation_id)
    {
        $input = Request::all();

        $deleted_lpo_quotation = LpoQuotation::destroy($lpo_quotation_id);


        if($deleted_lpo_quotation){
            return response()->json(['msg'=>"lpo quotation deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"lpo quotation not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }




















    /**
     * Operation getLpoQuotationById
     *
     * Find lpo quotation by ID.
     *
     * @param int $lpo_quotation_id ID of lpo quotation to return object (required)
     *
     * @return Http response
     */
    public function getLpoQuotationById($lpo_quotation_id)
    {
     $input = Request::all();

     try{

        $response = LpoQuotation::findOrFail($lpo_quotation_id);
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

    }catch(Exception $e){

        $response =  ["error"=>"lpo could not be found"];
        return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
    }
}












    /**
     * Operation getLpoQuotationDocumentById
     *
     * Find lpo quotation document by ID.
     *
     * @param int $lpo_quotation_id ID of lpo quotation to return object (required)
     *
     * @return Http response
     */
    public function getLpoQuotationDocumentById($lpo_quotation_id)
    {   
        try{


            $quotation      = LpoQuotation::findOrFail($lpo_quotation_id);

            $path           = './lpos/'.$quotation->lpo_id.'/quotations/'.$quotation->id.'/'.$quotation->quotation_doc;

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];

            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put('lpo_quotation/'.$quotation->id.'.temp', $file_contents);

            $url            = storage_path("app/lpo_quotation/".$quotation->id.'.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make("", 200);

            $response->header('Content-Type', 'application/pdf');

            return $response;  

        }


    }

















    
    /**
     * Operation lpoQuotationsGet
     *
     * lpo quotations List.
     *
     *
     * @return Http response
     */
    public function lpoQuotationsGet()
    {
        $input = Request::all();
        $response;


        if(array_key_exists('lpo_id', $input)){

            $response = LpoQuotation::where("deleted_at",null)
            ->where('lpo_id', $input['lpo_id'])
            ->get();

        }else{

            $response = LpoQuotation::all();

        }


        $response    = $this->append_relationships_objects($response);
        $response    = $this->append_relationships_nulls($response);


        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }




















    public function append_relationships_objects($data = array()){

        foreach ($data as $key => $value) {

            $model = new LpoQuotation();

            $data[$key]['supplier']             = $model->find($data[$key]['id'])->supplier;
            $data[$key]['uploaded_by']          = $model->find($data[$key]['id'])->uploaded_by;

        }

        return $data;


    }













    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["supplier"]==null){
                $data[$key]["supplier"] = array("supplier_name"=>"N/A");
            }

            if($data[$key]["uploaded_by"]==null){
                $data[$key]["uploaded_by"] = array("full_name"=>"N/A");
            }
        }

        return $data;


    }







}
