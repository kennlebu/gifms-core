<?php


namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use App\Models\AssetsModels\FixedAsset;
use App\Models\AssetsModels\FixedAssetStatus;
use App\Models\AssetsModels\FixedAssetCategory;
use App\Models\AssetsModels\FixedAssetLocation;

class FixedAssetsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }



    /**
     * Fixed Asset Statuses
     */
    public function assetStatusesGet()
    {
        $response = FixedAssetStatus::all();
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addAssetStatus()
    {
        $input = Request::all();

        $status = new FixedAssetStatus;
        $status->status = $input['status'];
        $status->next_status_id = $input['next_status_id'];
        $status->order_priority = $input['order_priority'];
        $status->display_color = $input['display_color'];

        if($status->save()) {
            return Response()->json(array('msg' => 'Success: status added','status' => $status), 200);
        }
    }   
    
    public function updateAssetStatus()
    {
        $input = Request::all();

        $status = FixedAssetStatus::find($input['id']);
        $status->status = $input['status'];
        $status->next_status_id = $input['next_status_id'];
        $status->order_priority = $input['order_priority'];
        $status->display_color = $input['display_color'];

        if($status->save()) {
            return Response()->json(array('msg' => 'Success: status updated','status' => $status), 200);
        }
    }  
    
    public function deleteAssetStatus($id)
    {
        $deleted = FixedAssetStatus::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"status deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"status not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getAssetStatusById($id)
    {
        $input = Request::all();

        try{
            $response = FixedAssetStatus::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"status could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

















    /**
     * Fixed Asset
     */
    public function assetsGet()
    {
        $response;
        $response_dt;

        $total_records          = FixedAsset::count();
        $records_filtered       = 0;
        $user = JWTAuth::parseToken()->authenticate();

        $input = Request::all();        
        $asset = FixedAsset::with('status','category','location','assigned_to','added_by');

        // Status
        if(array_key_exists('status',$input)){
            $asset = $asset->where('status_id', $input['status']);
        }

        // Category
        if(array_key_exists('asset_category_id',$input)){
            $asset = $asset->where('asset_category_id', $input['asset_category_id']);
        }

        // Location
        if(array_key_exists('asset_location_id',$input)){
            $asset = $asset->where('asset_location_id', $input['asset_location_id']);
        }

        // Assignee
        if(array_key_exists('assigned_to_id',$input)){
            $asset = $asset->where('assigned_to_id', $input['assigned_to_id']);
        }

        // Added By
        if(array_key_exists('added_by_id',$input)){
            $asset = $asset->where('added_by_id', $input['added_by_id']);
        }

        // My Assets
        if(array_key_exists('my_assigned', $input)){
            $asset = $asset->where('assigned_to_id', $user->id);
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $asset = $asset->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $asset = $asset->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            //searching
            $asset = $asset->where(function ($query) use ($input) {                
                $query->orWhere('asset_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('cost','like', '\'%' . $input['search']['value']. '%\'');
            });

            $records_filtered = $asset->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $asset = $asset->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $asset->limit($input['length'])->offset($input['start']);
            }
            else{
                $asset = $asset->limit($input['length']);
            }

            $response_dt = $asset->get();

            $response = Claim::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $asset->get();
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addAsset()
    {
        $input = Request::all();
        $user = JWTAuth::parseToken()->authenticate();

        $asset = new FixedAsset;
        $asset->asset_name = $input['asset_name'];
        if(!empty($input['purchase_date']))     $asset->purchase_date = $input['purchase_date'];
        if(!empty($input['cost']))              $asset->cost = $input['cost'];
        if(!empty($input['donated_value']))     $asset->donated_value = $input['donated_value'];
        if(!empty($input['date_donated']))      $asset->date_donated = $input['date_donated'];
        if(!empty($input['obsolete_value']))    $asset->obsolete_value = $input['obsolete_value'];
        if(!empty($input['obsolescence_date'])) $asset->obsolescence_date = $input['obsolescence_date'];
        if(!empty($input['scrapped_value']))    $asset->scrapped_value = $input['scrapped_value'];
        if(!empty($input['date_scrapped']))     $asset->date_scrapped = $input['date_scrapped'];
        if(!empty($input['insured_value']))     $asset->insured_value = $input['insured_value'];
        if(!empty($input['status_id']))         $asset->status_id = $input['status_id'];
        if(!empty($input['location_id']))       $asset->asset_location_id = $input['location_id'];
        if(!empty($input['category_id']))       $asset->asset_category_id = $input['category_id'];
        if(!empty($input['assigned_to_id']))    $asset->assigned_to_id = $input['assigned_to_id'];
        if(!empty($input['serial_number']))     $asset->serial_number = $input['serial_number'];
        
        $asset->added_by_id = $user->id;

        if($asset->save()) {
            return Response()->json(array('msg' => 'Success: asset added','asset' => $asset), 200);
        }
    }   
    
    public function updateAsset()
    {
        $input = Request::all();

        $asset = FixedAsset::find($input['id']);
        $asset->asset_name = $input['asset_name'];
        if(!empty($input['purchase_date']))     $asset->purchase_date = $input['purchase_date'];
        if(!empty($input['cost']))              $asset->cost = $input['cost'];
        if(!empty($input['donated_value']))     $asset->donated_value = $input['donated_value'];
        if(!empty($input['date_donated']))      $asset->date_donated = $input['date_donated'];
        if(!empty($input['obsolete_value']))    $asset->obsolete_value = $input['obsolete_value'];
        if(!empty($input['obsolescence_date'])) $asset->obsolescence_date = $input['obsolescence_date'];
        if(!empty($input['scrapped_value']))    $asset->scrapped_value = $input['scrapped_value'];
        if(!empty($input['date_scrapped']))     $asset->date_scrapped = $input['date_scrapped'];
        if(!empty($input['insured_value']))     $asset->insured_value = $input['insured_value'];
        if(!empty($input['status_id']))         $asset->status_id = $input['status_id'];
        if(!empty($input['location_id']))       $asset->asset_location_id = $input['location_id'];
        if(!empty($input['category_id']))       $asset->asset_category_id = $input['category_id'];
        if(!empty($input['assigned_to_id']))    $asset->assigned_to_id = $input['assigned_to_id'];

        if($asset->save()) {
            return Response()->json(array('msg' => 'Success: asset updated','asset' => $asset), 200);
        }
    }  
    
    public function deleteAsset($id)
    {
        $deleted = FixedAsset::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"asset deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"asset not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getAssetById($id)
    {
        $input = Request::all();

        try{
            $response = FixedAsset::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"asset could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


























    /**
     * Fixed Asset Location
     */
    public function assetLocationsGet()
    {
        $response = FixedAssetLocation::all();
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addAssetLocation()
    {
        $input = Request::all();

        $location = new FixedAssetLocation;
        $location->location = $input['location'];
        $location->location_description = $input['location_description'];

        if($location->save()) {
            return Response()->json(array('msg' => 'Success: location added','location' => $location), 200);
        }
    }   
    
    public function updateAssetLocation()
    {
        $input = Request::all();

        $location = FixedAssetLocation::find($input['id']);
        $location->location = $input['location'];
        $location->location_description = $input['location_description'];

        if($location->save()) {
            return Response()->json(array('msg' => 'Success: location updated','location' => $location), 200);
        }
    }  
    
    public function deleteAssetLocation($id)
    {
        $deleted = FixedAssetLocation::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"location deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"location not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getAssetLocationById($id)
    {
        $input = Request::all();

        try{
            $response = FixedAssetLocation::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"location could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }


























    /**
     * Fixed Asset Category
     */
    public function assetCategoriesGet()
    {
        $response = FixedAssetCategory::all();
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addAssetCategory()
    {
        $input = Request::all();

        $category = new FixedAssetCategory;
        $category->category = $input['category'];
        $category->category_description = $input['category_description'];

        if($category->save()) {
            return Response()->json(array('msg' => 'Success: category added','category' => $category), 200);
        }
    }   
    
    public function updateAssetCategory()
    {
        $input = Request::all();

        $category = FixedAssetCategory::find($input['id']);
        $category->category = $input['category'];
        $category->category_description = $input['category_description'];

        if($category->save()) {
            return Response()->json(array('msg' => 'Success: category updated','category' => $category), 200);
        }
    }  
    
    public function deleteAssetCategory($id)
    {
        $deleted = FixedAssetCategory::destroy($id);

        if($deleted){
            return response()->json(['msg'=>"category deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"category not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    public function getAssetCategoryById($id)
    {
        $input = Request::all();

        try{
            $response = FixedAssetCategory::findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"category could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
}
