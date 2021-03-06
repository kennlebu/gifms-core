<?php


namespace App\Http\Controllers;

use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Request;
use App\Models\AssetsModels\FixedAsset;
use App\Models\AssetsModels\FixedAssetStatus;
use App\Models\AssetsModels\FixedAssetCategory;
use App\Models\AssetsModels\FixedAssetLocation;
use App\Models\AssetsModels\LostAsset;
use App\Models\AssetsModels\ClaimedAsset;
use Anchu\Ftp\Facades\Ftp;
use Excel;

class FixedAssetsApi extends Controller
{
    /**
     * Fixed Asset Statuses
     */
    public function assetStatusesGet()
    {
        $response;
        $response_dt;

        $total_records          = FixedAssetStatus::count();
        $records_filtered       = 0;
        $input = Request::all();
        $asset_status = FixedAssetStatus::query();

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $asset_status = $asset_status->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $asset_status = $asset_status->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $asset_status->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $asset_status = $asset_status->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $asset_status->limit($input['length'])->offset($input['start']);
            }
            else{
                $asset_status = $asset_status->limit($input['length']);
            }

            $response_dt = $asset_status->get();

            $response = FixedAssetStatus::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $asset_status->get();

            $response[]=array(
                "id"=> -1,
                "status"=> "All My Assets",
                "order_priority"=> 2,
                "display_color"=> "#37A9E17A",
                "count"=> FixedAsset::where('assigned_to_id',$this->current_user()->id)->count()
              );

            if ($this->current_user()->hasRole(['admin-manager','admin'])){
                $response[]=array(
                    "id"=> -2,
                    "status"=> "All Assets",
                    "order_priority"=> 1,
                    "display_color"=> "#37A9E17A",
                    "count"=> FixedAsset::count()
                );
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }

    public function addAssetStatus()
    {
        $input = Request::all();

        $status = new FixedAssetStatus;
        $status->status = $input['status'];
        if(!empty($input['next_status_id'])) $status->next_status_id = $input['next_status_id'];
        if(!empty($input['order_priority'])) $status->order_priority = $input['order_priority'];
        if(!empty($input['display_color'])) $status->display_color = $input['display_color'];

        if($status->save()) {
            return Response()->json(array('msg' => 'Success: status added','status' => $status), 200);
        }
    }   
    
    public function updateAssetStatus()
    {
        $input = Request::all();

        $status = FixedAssetStatus::find($input['id']);
        $status->status = $input['status'];
        if(!empty($input['next_status_id'])) $status->next_status_id = $input['next_status_id'];
        if(!empty($input['order_priority'])) $status->order_priority = $input['order_priority'];
        if(!empty($input['display_color'])) $status->display_color = $input['display_color'];

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

            $response = FixedAsset::arr_to_dt_response( 
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
        if(!empty($input['purchase_date']))     $asset->purchase_date = date('Y-m-d', strtotime($input['purchase_date']));
        if(!empty($input['cost']))              $asset->cost = $input['cost'];
        if(!empty($input['donated_value']))     $asset->donated_value = $input['donated_value'];
        if(!empty($input['date_donated']))      $asset->date_donated = date('Y-m-d', strtotime($input['date_donated']));
        if(!empty($input['obsolete_value']))    $asset->obsolete_value = $input['obsolete_value'];
        if(!empty($input['obsolescence_date'])) $asset->obsolescence_date = date('Y-m-d', strtotime($input['obsolescence_date']));
        if(!empty($input['scrapped_value']))    $asset->scrapped_value = $input['scrapped_value'];
        if(!empty($input['date_scrapped']))     $asset->date_scrapped = date('Y-m-d', strtotime($input['date_scrapped']));
        if(!empty($input['insured_value']))     $asset->insured_value = $input['insured_value'];
        if(!empty($input['status_id']))         $asset->status_id = $input['status_id'];
        if(!empty($input['location_id']))       $asset->asset_location_id = $input['location_id'];
        if(!empty($input['category_id']))       $asset->asset_category_id = $input['category_id'];
        if(!empty($input['assigned_to_id']))    $asset->assigned_to_id = $input['assigned_to_id'];
        if(!empty($input['serial_number']))     $asset->serial_number = $input['serial_number'];
        if(!empty($input['tag_number']))        $asset->tag_number = $input['tag_number'];
        
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
        if(!empty($input['purchase_date']))     $asset->purchase_date = date('Y-m-d', strtotime($input['purchase_date']));
        if(!empty($input['cost']))              $asset->cost = $input['cost'];
        if(!empty($input['donated_value']))     $asset->donated_value = $input['donated_value'];
        if(!empty($input['date_donated']))      $asset->date_donated = date('Y-m-d', strtotime($input['date_donated']));
        if(!empty($input['obsolete_value']))    $asset->obsolete_value = $input['obsolete_value'];
        if(!empty($input['obsolescence_date'])) $asset->obsolescence_date = date('Y-m-d', strtotime($input['obsolescence_date']));
        if(!empty($input['scrapped_value']))    $asset->scrapped_value = $input['scrapped_value'];
        if(!empty($input['date_scrapped']))     $asset->date_scrapped = date('Y-m-d', strtotime($input['date_scrapped']));
        if(!empty($input['insured_value']))     $asset->insured_value = $input['insured_value'];
        if(!empty($input['status_id']))         $asset->status_id = $input['status_id'];
        if(!empty($input['location_id']))       $asset->asset_location_id = $input['location_id'];
        if(!empty($input['category_id']))       $asset->asset_category_id = $input['category_id'];
        if(!empty($input['assigned_to_id']))    $asset->assigned_to_id = $input['assigned_to_id'];
        if(!empty($input['serial_number']))     $asset->serial_number = $input['serial_number'];
        if(!empty($input['tag_number']))        $asset->tag_number = $input['tag_number'];

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
        try{
            $response = FixedAsset::with('status','category','location','assigned_to','added_by','logs.causer')->findOrFail($id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(Exception $e){
            $response =  ["error"=>"asset could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }

    public function reportLost()
    {
        try{
            $input = Request::only(
                'file',
                'fixed_asset_id',
                'date_lost',
                'explanation'
            );

            $lost_asset = new LostAsset;
            $lost_asset->fixed_asset_id = $input['fixed_asset_id'];
            $lost_asset->date_lost = date('Y-m-d', strtotime($input['date_lost']));
            $lost_asset->explanation = $input['explanation'];
            $file = $input['file'];

            $lost_status = FixedAssetStatus::find(5);   // Lost/Stolen status id

            $asset = FixedAsset::findOrFail($input['fixed_asset_id']);
            $asset->status_id = $lost_status->id;

            if($lost_asset->save()) {
                $asset->save();

                FTP::connection()->makeDir('/fixed-assets');
                FTP::connection()->makeDir('/fixed-assets/'.$lost_asset->id);
                FTP::connection()->uploadFile($file->getPathname(), '/fixed-assets/'.$lost_asset->id.'/'.$lost_asset->id.'.'.$file->getClientOriginalExtension());

                $lost_asset->police_abstract = $lost_asset->id.'.'.$file->getClientOriginalExtension();
                $lost_asset->save();

                return Response()->json(array('msg' => 'Success: asset loss reported','asset' => $lost_asset), 200);
            }
        }
        catch(Exception $e){
            return response()->json(['error'=>"something went wrong", 'msg'=>$e->getMessage()], 404,array(),JSON_PRETTY_PRINT);
        }
    } 

    public function claimAsset(){
        try{
            $input = Request::only(
                'file',
                'fixed_asset_id',
                'insurer_id'
            );

            $claimed_asset = new ClaimedAsset;
            $claimed_asset->fixed_asset_id = $input['fixed_asset_id'];
            $claimed_asset->insurer_id = $input['insurer_id'];
            $file = $input['file'];

            $asset = FixedAsset::findOrFail($input['fixed_asset_id']);
            $asset->status_id = 7;      // Lost & Claimed status id

            if($claimed_asset->save()) {
                $asset->save();

                FTP::connection()->makeDir('/asset-claims');
                FTP::connection()->makeDir('/asset-claims/'.$claimed_asset->id);
                FTP::connection()->uploadFile($file->getPathname(), '/asset-claims/'.$claimed_asset->id.'/'.$claimed_asset->id.'.'.$file->getClientOriginalExtension());

                $claimed_asset->claim_document = $claimed_asset->id.'.'.$file->getClientOriginalExtension();
                $claimed_asset->save();

                return Response()->json(array('msg' => 'Success: asset claimed','asset' => $claimed_asset), 200);
            }
        }
        catch(Exception $e){
            return response()->json(['error'=>"something went wrong", 'msg'=>$e->getMessage()], 404,array(),JSON_PRETTY_PRINT);
        }
    }


    public function returnAssets(){
        try {
            $form = Request::only("assets");
            $asset_ids = $form['assets'];

            foreach ($asset_ids as $key => $asset_id) {
                $asset = FixedAsset::findOrFail($asset_id);
                $asset->status_id = 8;  // Returned Pending Approval

                $asset->disableLogging(); //! Do not log the update

                if($asset->save()){
                    // Logging submission
                    activity()
                        ->performedOn($asset)
                        ->causedBy($this->current_user())
                        ->log('returned');
                }
            }

            return response()->json(['assets'=>$form['assets']], 201,array(),JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
        }
    }


    public function uploadAssetList(){
        try{
            $form = Request::only("file");
            $file = $form['file'];

            $data = Excel::load($file->getPathname(), function($reader) {
            })->get()->toArray();

            $assets_array = array();
            foreach ($data as $key => $value) {
                // $allocation = new Allocation();

                try{
                    $line = trim($value['asset_name']).' | '.trim($value['tag_number']).' | '.trim($value['location_optional']).' | '.trim($value['staff_optional']).' | '.trim($value['status']);
                //     $project = Project::where('project_code','like', '%'.trim($value['pid']).'%')->firstOrFail();
                //     $account = Account::where('account_code', 'like', '%'.trim($value['account_code']).'%')->firstOrFail();

                //     $allocation->allocatable_id = $payable_id;
                //     $allocation->allocatable_type = $payable_type;
                //     $allocation->amount_allocated = $value['amount_allocation'];
                //     $allocation->allocation_purpose = $value['specific_journal_rference'];
                //     $allocation->percentage_allocated  (string) $this->getPercentage($value['amount_allocation'], $total);
                //     $allocation->allocated_by_id =  (int) $user->id;
                //     $allocation->account_id =  $account->id;
                //     $allocation->project_id = $project->id;
                //     array_push($assets_array, $allocation);

                }
                catch(\Exception $e){
                    $response =  ["error"=>'Account or Project not found. Please use form to allocate.',
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


























    /**
     * Fixed Asset Location
     */
    public function assetLocationsGet()
    {
        $response;
        $response_dt;

        $total_records          = FixedAssetLocation::count();
        $records_filtered       = 0;

        $input = Request::all(); 
        $asset_location = FixedAssetLocation::query();

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $asset_location = $asset_location->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $asset_location = $asset_location->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $asset_location->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $asset_location = $asset_location->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $asset_location->limit($input['length'])->offset($input['start']);
            }
            else{
                $asset_location = $asset_location->limit($input['length']);
            }

            $response_dt = $asset_location->get();

            $response = FixedAssetLocation::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $asset_location->get();
        }

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
        $response;
        $response_dt;

        $total_records          = FixedAssetCategory::count();
        $records_filtered       = 0;

        $input = Request::all(); 
        $asset_category = FixedAssetCategory::query();

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $asset_category = $asset_category->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $asset_category = $asset_category->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){

            $records_filtered = $asset_category->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $asset_category = $asset_category->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt = $asset_category->limit($input['length'])->offset($input['start']);
            }
            else{
                $asset_category = $asset_category->limit($input['length']);
            }

            $response_dt = $asset_category->get();

            $response = FixedAssetCategory::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $response = $asset_category->get();
        }

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
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }
}
