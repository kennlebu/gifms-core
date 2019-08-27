<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Models\Assets\Asset;
use App\Models\Assets\AssetClass;
use App\Models\Assets\AssetGroup;
use App\Models\Assets\AssetInsuranceType;
use App\Models\Assets\AssetLocation;
use App\Models\Assets\AssetStatus;
use App\Models\Assets\AssetTransfer;
use App\Models\Assets\AssetType;
use JWTAuth;

class AssetApi extends Controller
{

    public function __construct(){
        date_default_timezone_set ('Africa/Nairobi');
    }

    public function addAsset(){
        $input = Request::all();
        $asset = [
            'title'=>$input['title'] ?? null,
            'type_id'=>$input['type_id'] ?? null,
            'tag'=>$input['tag'] ?? null,
            'serial_no'=>$input['serial_no'] ?? null,
            'cost'=>$input['cost'] ?? null,
            'status_id'=>$input['status_id'] ?? null,
            'supplier_id'=>$input['supplier_id'] ?? null,
            'class_id'=>$input['class_id'] ?? null,
            'insurance_type_id'=>$input['insurance_type_id'] ?? null,
            'location_id'=>$input['location_id'] ?? null,
            'assigned_to_id'=>$input['assigned_to_id'] ?? null,
            'assignee_type'=>!empty($input['assignee_type']) ? $input['assignee_type'] : null,
            'date_of_issue'=>!empty($input['date_of_issue']) ? date('Y-m-d', strtotime($input['date_of_issue'])) : null,
            'date_of_purchase'=>!empty($input['date_of_purchase']) ? date('Y-m-d', strtotime($input['date_of_purchase'])) : null,
            'added_by_id'=>$this->current_user()->id,
            'last_updated_by'=>$this->current_user()->id,
            'asset_group_id'=>$input['asset_group_id'] ?? null,
            'insurance_value'=>$input['insurance_value'] ?? null,
            'comments'=>$input['comments'] ?? null,
            'requisition_id'=>$input['requisition_id'] ?? null,
            'lpo_id'=>$input['lpo_id'] ?? null,
            'invoice_id'=>$input['invoice_id'] ?? null
        ];
        $asset = Asset::create($asset);
        return Response()->json(array('msg' => 'Success: asset added','data' => $asset), 200);
    }

    public function updateAsset(){
        $input = Request::all();
        $asset = Asset::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset updated','data' => $asset), 200);
    }

    public function getAsset($id){
        $asset = Asset::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssets(){
        try{
            $input = Request::all();
            $user = JWTAuth::parseToken()->authenticate();
            $assets = Asset::with('status','assigned_to','type','class','location','group');
            if(array_key_exists('my_assigned', $input) && !$user->hasRole(['admin-manager','admin'])){            // All assets
                $assets = $assets->where('assigned_to_id', $user->id);
            }
            if(array_key_exists('all', $input)){                    // Plus deleted
                $assets = $assets->withTrashed();
            }
            if(array_key_exists('tag', $input)){                    // Tag
                $assets = $assets->where('tag', $input['tag']);
            }
            if(array_key_exists('type_id', $input)){                // Type
                $assets = $assets->where('type_id', $input['type_id']);
            }
            if(array_key_exists('status_id', $input)){              // Status
                if($input['status_id']==-1){
                    $assets = $assets->where('assigned_to_id', $user->id);
                }
                elseif($input['status_id']==-2){
                    // Pass, don't filter
                }
                else{
                    $assets = $assets->where('status_id', $input['status_id']);
                }
            }
            if(array_key_exists('serial_no', $input)){              // Serial Number
                $assets = $assets->where('serial_no', $input['serial_no']);
            }
            if(array_key_exists('supplier_id', $input)){            // Supplier
                $assets = $assets->where('supplier_id', $input['supplier_id']);
            }
            if(array_key_exists('class_id', $input)){               // Class
                $assets = $assets->where('class_id', $input['class_id']);
            }
            if(array_key_exists('insurance_type_id', $input)){      // Insurance Type
                $assets = $assets->where('insurance_type_id', $input['insurance_type_id']);
            }
            if(array_key_exists('location_id', $input)){            // Location
                $assets = $assets->where('location_id', $input['location_id']);
            }
            if(array_key_exists('assigned_to_id', $input)){         // Assigned to
                $assets = $assets->where('assigned_to_id', $input['assigned_to_id']);
            }
            if(array_key_exists('assignee_type', $input)){          // Assignee type
                $assets = $assets->where('assignee_type', $input['assignee_type']);
            }
            if(array_key_exists('asset_group_id', $input)){         // Asset group
                $assets = $assets->where('asset_group_id', $input['asset_group_id']);
            }
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->orWhere('tag','like', '\'%' . $input['search']['value']. '%\'');
                        $query->orWhere('title','like', '\'%' . $input['search']['value']. '%\'');
                        $query->orWhere('serial_no','like', '\'%' . $input['search']['value']. '%\'');
                        $query->orWhere('cost','like', '\'%' . $input['search']['value']. '%\'');
                        $query->orWhere('insurance_value','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = Asset::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAsset($id){
        $deleted = Asset::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }

    public function returnAssets(){
        $input = Request::all();
        $status = 6;
        $log_message = 'Asset returned';
        if($input['op'] == 'request') {
            $status = 6;
            $log_message = 'Requested to return';
        }
        elseif($input['op'] == 'approve') {
            $status = 5;
            $log_message = 'Asset returned';
        }
        foreach($input['assets'] as $item){
            $asset = Asset::find($item);
            $asset->status_id = $status;
            if($input['op'] == 'approve') {
                $asset->assigned_to_id = null;
                $asset->assignee_type = null;
            }
            $asset->disableLogging();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->log($log_message);

            $asset->save();

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $item,
                'transfered_by_id' => $this->current_user(),
                'transfer_type' => 'return'
            ]);
        }
        return Response()->json(array('msg' => 'Success: assets returned','data' => $asset), 200);
    }

    public function donateAssets(){
        $input = Request::all();
        $status = 6;
        $log_message = 'Asset returned';
        if($input['op'] == 'request') {
            $status = 6;
            $log_message = 'Requested to return';
        }
        elseif($input['op'] == 'approve') {
            $status = 5;
            $log_message = 'Asset returned';
        }
        foreach($input['assets'] as $item){
            $asset = Asset::find($item);
            $asset->status_id = $status;
            if($input['op'] == 'approve') {
                $asset->assigned_to_id = null;
                $asset->assignee_type = null;
            }
            $asset->disableLogging();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->log($log_message);

            $asset->save();

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $item,
                'transfered_by_id' => $this->current_user(),
                'transfer_type' => 'return'
            ]);
        }
        return Response()->json(array('msg' => 'Success: assets returned','data' => $asset), 200);
    }



    /**
     * Asset types
     */   
    
    public function addAssetType(){
        $input = Request::all();
        $asset = AssetType::create($input);
        return Response()->json(array('msg' => 'Success: asset type added','data' => $asset), 200);
    }

    public function updateAssetType(){
        $input = Request::all();
        $asset = AssetType::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset type updated','data' => $asset), 200);
    }

    public function getAssetType($id){
        $asset = AssetType::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetTypes(){
        try{
            $input = Request::all();
            $assets = AssetType::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('type','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetType::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAssetType($id){
        $deleted = AssetType::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset type removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }



    /**
     * Asset statuses
     */   
    
    public function addAssetStatus(){
        $input = Request::all();
        $asset = AssetStatus::create($input);
        return Response()->json(array('msg' => 'Success: asset status added','data' => $asset), 200);
    }

    public function updateAssetStatus(){
        $input = Request::all();
        $asset = AssetStatus::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset status updated','data' => $asset), 200);
    }

    public function getAssetStatus($id){
        $asset = AssetStatus::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetStatuses(){
        try{
            $input = Request::all();
            $assets = AssetStatus::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('status','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetStatus::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();

                if(array_key_exists('dropdown', $input)){
                    $assets[]=array(
                        "id"=> -1,
                        "status"=> "My Assets"
                    );

                    if ($this->current_user()->hasRole(['admin-manager','admin'])){
                        $assets[]=array(
                            "id"=> -2,
                            "status"=> "All Assets"
                        );
                    }
                }
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAssetStatus($id){
        $deleted = AssetStatus::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset status removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }



    /**
     * Asset classes
     */   
    
    public function addAssetClass(){
        $input = Request::all();
        $asset = AssetClass::create($input);
        return Response()->json(array('msg' => 'Success: asset class added','data' => $asset), 200);
    }

    public function updateAssetClass(){
        $input = Request::all();
        $asset = AssetClass::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset class updated','data' => $asset), 200);
    }

    public function getAssetClass($id){
        $asset = AssetClass::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetClasses(){
        try{
            $input = Request::all();
            $assets = AssetClass::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('name','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetClass::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAssetClass($id){
        $deleted = AssetClass::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset class removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }



    /**
     * Asset groups
     */   
    
    public function addAssetGroup(){
        $input = Request::all();
        $asset = AssetGroup::create($input);
        return Response()->json(array('msg' => 'Success: asset group added','data' => $asset), 200);
    }

    public function updateAssetGroup(){
        $input = Request::all();
        $asset = AssetGroup::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset group updated','data' => $asset), 200);
    }

    public function getAssetGroup($id){
        $asset = AssetGroup::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetGroups(){
        try{
            $input = Request::all();
            $assets = AssetGroup::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('title','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetGroup::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAssetGroup($id){
        $deleted = AssetGroup::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset group removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }



    /**
     * Asset locations
     */   
    
    public function addAssetLocation(){
        $input = Request::all();
        $asset = AssetLocation::create($input);
        return Response()->json(array('msg' => 'Success: asset location added','data' => $asset), 200);
    }

    public function updateAssetLocation(){
        $input = Request::all();
        $asset = AssetLocation::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset location updated','data' => $asset), 200);
    }

    public function getAssetLocation($id){
        $asset = AssetLocation::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetLocations(){
        try{
            $input = Request::all();
            $assets = AssetLocation::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('location','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetLocation::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteAssetLocation($id){
        $deleted = AssetLocation::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset location removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }



    /**
     * Asset insurance types
     */   
    
    public function addInsuranceType(){
        $input = Request::all();
        $asset = AssetInsuranceType::create($input);
        return Response()->json(array('msg' => 'Success: inusrance type added','data' => $asset), 200);
    }

    public function updateInsuranceType(){
        $input = Request::all();
        $asset = AssetInsuranceType::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: insurance type updated','data' => $asset), 200);
    }

    public function getInsuranceType($id){
        $asset = AssetInsuranceType::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getInsuranceTypes(){
        try{
            $input = Request::all();
            $assets = AssetInsuranceType::query();
            if(array_key_exists('datatables', $input)){             // Datatables
                    $total_records = $assets->count();

                    //searching
                    $assets = $assets->where(function ($query) use ($input) {                
                        $query->where('type','like', '\'%' . $input['search']['value']. '%\'');
                    });

                    $records_filtered = $assets->count();
        
                    //ordering
                    $order_column_id    = (int) $input['order'][0]['column'];
                    $order_column_name  = $input['columns'][$order_column_id]['order_by'];
                    $order_direction    = $input['order'][0]['dir'];
        
                    if($order_column_name!=''){
                        $assets = $assets->orderBy($order_column_name, $order_direction);
                    }
        
                    //limit offset
                    if((int)$input['start']!= 0 ){
                        $assets = $assets->limit($input['length'])->offset($input['start']);
                    }
                    else{
                        $assets = $assets->limit($input['length']);
                    }
        
                    $assets = $assets->get();
        
                    $assets = AssetInsuranceType::arr_to_dt_response( 
                        $assets, $input['draw'],
                        $total_records,
                        $records_filtered
                        );
            }
            else{
                $assets = $assets->get();
            }

            return response()->json($assets, 200); 
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }       
    }

    public function deleteInsuranceType($id){
        $deleted = AssetInsuranceType::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Insurance type removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }
}
