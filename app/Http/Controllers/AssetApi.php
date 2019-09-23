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
use App\Models\Assets\AssetName;
use App\Models\ApprovalsModels\Approval;
use App\Models\Assets\AssetTransferBatch;
use Exception;
use PDF;
use JWTAuth;
use Illuminate\Support\Facades\Response;
use Anchu\Ftp\Facades\Ftp;
use App\Models\Assets\AssetLoss;
use Excel;

class AssetApi extends Controller
{

    public function __construct(){
        date_default_timezone_set ('Africa/Nairobi');
    }

    public function addAsset(){
        $input = Request::all();
        $asset = [
            'asset_name_id'=>$input['asset_name_id'] ?? null,
            'description'=>$input['description'] ?? null,
            'model'=>$input['model'] ?? null,
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
            'staff_responsible_id'=>$input['staff_responsible_id'] ?? null,
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
        if(!empty($input['asset_name_id'])) $asset->status_id=$input['asset_name_id'];
        if(!empty($input['description'])) $asset->description = $input['description'];
        if(!empty($input['model'])) $asset->model = $input['model'];
        if(!empty($input['type_id'])) $asset->type_id=$input['type_id'];
        if(!empty($input['tag'])) $asset->tag=$input['tag'];
        if(!empty($input['serial_no'])) $asset->serial_no=$input['serial_no'];
        if(!empty($input['cost'])) $asset->cost=$input['cost'];
        if(!empty($input['status_id'])) $asset->status_id=$input['status_id'];
        if(!empty($input['supplier_id'])) $asset->supplier_id=$input['supplier_id'];
        if(!empty($input['class_id'])) $asset->class_id=$input['class_id'];
        if(!empty($input['insurance_type_id'])) $asset->insurance_type_id=$input['insurance_type_id'];
        if(!empty($input['location_id'])) $asset->location_id=$input['location_id'];
        if(!empty($input['assigned_to_id'])) $asset->assigned_to_id=$input['assigned_to_id'];
        if(!empty($input['assignee_type'])) $asset->assignee_type = $input['assignee_type'];
        if(!empty($input['staff_responsible_id'])) $asset->staff_responsible_id=$input['staff_responsible_id'];
        if(!empty($input['date_of_issue'])) $asset->date_of_issue=$input['date_of_issue'];
        if(!empty($input['date_of_purchase'])) $asset->date_of_purchase=$input['date_of_purchase'];
        $asset->last_updated_by=$this->current_user()->id;
        if(!empty($input['asset_group_id'])) $asset->asset_group_id=$input['asset_group_id'];
        if(!empty($input['insurance_value'])) $asset->insurance_value=$input['insurance_value'];
        if(!empty($input['comments'])) $asset->comments=$input['comments'];
        if(!empty($input['requisition_id'])) $asset->requisition_id=$input['requisition_id'];
        if(!empty($input['lpo_id'])) $asset->lpo_id=$input['lpo_id'];
        if(!empty($input['invoice_id'])) $asset->invoice_id=$input['invoice_id'];

        if($input['op'] == 'edit') $asset->disableLogging();
        $asset = $asset->save();
        return Response()->json(array('msg' => 'Success: asset updated','data' => $asset), 200);
    }

    public function getAsset($id){
        $asset = Asset::with(['class','assigned_to','insurance_type','location','staff_responsible',
                            'status','supplier','type','logs.causer','lpo', 'asset_name'])
                        ->where('id', $id)
                        ->firstOrFail();           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssets(){
        try{
            $input = Request::all();
            $user = JWTAuth::parseToken()->authenticate();
            $assets = Asset::with('status','assigned_to','type','class','location','asset_name','staff_responsible');
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
            if(array_key_exists('staff_responsible_id', $input)){    // Assigned to
                $assets = $assets->where('staff_responsible_id', $input['staff_responsible_id']);
            }
            if(array_key_exists('assignee_type', $input)){          // Assignee type
                $assets = $assets->where('assignee_type', $input['assignee_type']);
            }
            if(array_key_exists('asset_group_id', $input)){         // Asset group
                $assets = $assets->where('asset_group_id', $input['asset_group_id']);
            }
            if(array_key_exists('asset_name_id', $input)){          // Asset name
                $assets = $assets->where('asset_name_id', $input['asset_name_id']);
            }
            if(array_key_exists('my_approvables', $input)){         // Approvables
                if($user->hasRole(['admin-manager'])){
                    $assets = $assets->whereIn('status_id', [8,10,15,18]);
                }
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
        $status = 8;
        $log_message = 'Asset returned';
        if($input['op'] == 'request') {
            $status = 8;
            $log_message = 'Returned, pending confirmation';
        }
        elseif($input['op'] == 'approve') {
            $status = 7;
            $log_message = 'Asset return confirmed by admin';
        }
        foreach($input['assets'] as $item){
            $asset = Asset::find($item);
            $asset->status_id = $status;
            if($input['op'] == 'approve') {
                $asset->assigned_to_id = null;
                $asset->staff_responsible_id = $this->current_user()->id;
                $asset->assignee_type = 'individual';
                $asset->location_id = null;
                $asset->date_of_issue = null;
            }
            $asset->disableLogging();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset returned, pending confirmation by admin.'])
                ->log($log_message);

            $asset->save();

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $item,
                'transfered_by_id' => $this->current_user(),
                'transfer_type' => 'return',
                'reason' => null
            ]);
        }
        return Response()->json(array('msg' => 'Success: assets returned','data' => $asset), 200);
    }

    public function donateAssets(){
        $input = Request::all();
        $status = 10;
        $transfered_by_id = '';
        $log_message = 'Asset donated, pending approval';
        if($input['op'] == 'request') {
            $status = 10;
            $log_message = 'Asset donated, pending approval';
        }
        elseif($input['op'] == 'approve') {
            $status = 12;
            $log_message = 'Asset donation approved by PM';
        }
        foreach($input['assets'] as $item){
            $asset = Asset::find($item);
            if($input['op'] == 'request' && $asset->status_id == 10) {
                return Response()->json(array('error' => 'Donation pending PM approval','data' => $asset), 409);
            }
            if($input['op'] == 'request') {
                $asset->donation_to_id = $input['recepient'];
                $asset->donation_recepient_type = 'government';
                $property_detail = 'Approval requested for asset donation to '. $asset->donation_to->supplier_name .'. '.
                                    'REASON: '. $input['reason'];
            }
            $asset->status_id = $status;
            if($input['op'] == 'approve') {
                $transfered_by_id = $asset->assigned_to_id;
                $asset->assigned_to_id = $asset->donation_to_id;
                $asset->staff_responsible_id = null;
                $asset->assignee_type = 'government';
                $property_detail = 'Asset donation approved by PM';
                $batch = new AssetTransferBatch;
                $batch->created_by_id = $this->current_user()->id;
                $batch->save();
                $asset->donation_batch_id = $batch->id;
            }
            $asset->disableLogging();
            $asset->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => $property_detail])
                ->log($log_message);

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $item,
                'transfered_by_id' => $transfered_by_id,
                'transfered_to_id' => $input['recepient'],
                'recepient_type' => $input['recepient_type'],
                'transfer_type' => 'donation',
                'reason' => $input['reason'],
                'batch_id' => $batch->id ?? null,
                'approved_by_id' => $this->current_user()->id
            ]);
        }
        return Response()->json(array('msg' => 'Success: assets returned','data' => $asset), 200);
    }

    public function approveAsset($id, $multiple=false){
        $asset = Asset::findOrFail($id);
        if($asset->status_id == 8) {    // Return
            $asset->status_id = 7;
            $asset->assigned_to_id = null;
            $asset->staff_responsible_id = $this->current_user()->id;
            $asset->assignee_type = 'individual';
            $asset->location_id = null;
            $asset->date_of_issue = null;
            $asset->disableLogging();
            $asset->save();
            
            // Approval
            $approval = new Approval;
            $approval->ref                      =   'R-'.$asset->id;
            $approval->approvable_id            =   (int) $asset->id;
            $approval->approvable_type          =   "assets";
            $approval->approval_level_id        =   1;
            $approval->approver_id              =   (int) $this->current_user()->id;
            $approval->disableLogging();
            $approval->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset return confirmed by admin'])
                ->log('Asset returned');
        
            if(!$multiple)
            return Response()->json(array('msg' => 'Success: assets returned','data' => $asset), 200);
        }
        
        if($asset->status_id == 10) {    // Donate
            $asset->status_id = 13;
            $asset->disableLogging();
            $asset->save();
            
            // Approval
            $approval = new Approval;
            $approval->ref                      =   'D-'.$asset->id;
            $approval->approvable_id            =   (int) $asset->id;
            $approval->approvable_type          =   "assets";
            $approval->approval_level_id        =   2;
            $approval->approver_id              =   (int) $this->current_user()->id;
            $approval->disableLogging();
            $approval->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset donation approved by PM.'])
                ->log('Asset donated');
          
            if(!$multiple)
            return Response()->json(array('msg' => 'Success: assets donated','data' => $asset), 200);
        }

        //  Stolen
        if($asset->status_id == 15){
            $asset->disableLogging();
            $asset->status_id = 17;
            $asset->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset loss report has been verified'])
                ->log('Asset loss verified');

            if(!$multiple)
            return Response()->json(array('msg' => 'Success: assets donated','data' => $asset), 200);
        }

        //  Claim
        if($asset->status_id == 18){
            $asset->disableLogging();
            $asset->status_id = 20;
            $asset->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Insurance claim has been verified'])
                ->log('Insurance claim verified');

            if(!$multiple)
            return Response()->json(array('msg' => 'Success: assets donated','data' => $asset), 200);
        }
    }

    public function approveAssets(){
        try {
            $form = Request::only("assets");
            $asset_ids = $form['assets'];

            foreach ($asset_ids as $key => $asset_id) {
                $this->approveAsset($asset_id, true);
            }

            return response()->json(['assets'=>$form['assets']], 201, array(), JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
             return response()->json(['error'=>"An rerror occured during processing"], 500,array(),JSON_PRETTY_PRINT);
        }
    }

    public function rejectAsset($id){
        $input = Request::only('rejection_reason');
        $asset = Asset::findOrFail($id);
        if($asset->status_id == 8) {    // Return
            $asset->status_id = 23;
            $asset->disableLogging();
            $asset->save();
            
            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset return rejected. REASON: '. $input['rejection_reason']])
                ->log('Return rejected');
            
            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $id,
                'transfered_by_id' => $this->current_user(),
                'transfer_type' => 'return',
                'rejection_reason' => $input['rejection_reason']
            ]);
        
            return Response()->json(array('msg' => 'Success: assets return rejected','data' => $asset), 200);
        }

        if($asset->status_id == 10) {    // Donate
            $asset->status_id = 11;
            // $asset->assigned_to_id = null;
            // $asset->assignee_type = null;
            // $asset->location_id = null;
            // $asset->date_of_issue = null;
            $asset->disableLogging();
            $asset->save();
            
            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset donation rejected. REASON: '. $input['rejection_reason']])
                ->log('Donation rejected');
            
            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $id,
                'transfered_by_id' => $this->current_user(),
                'transfer_type' => 'donation',
                'rejection_reason' => $input['rejection_reason']
            ]);
        
            return Response()->json(array('msg' => 'Success: assets donation rejected','data' => $asset), 200);
        }

        if($asset->status_id == 15) {    // Lost
            $asset->status_id = 16;
            $asset->disableLogging();
            $asset->save();
            
            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset loss report rejected. REASON: '. $input['rejection_reason']])
                ->log('Loss report rejected');
        
            return Response()->json(array('msg' => 'Success: assets loss report rejected','data' => $asset), 200);
        }

        if($asset->status_id == 18) {    // Claim
            $asset->status_id = 19;
            $asset->disableLogging();
            $asset->save();
            
            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Insurance claim returned. REASON: '. $input['rejection_reason']])
                ->log('Insurance claim rejected');
        
            return Response()->json(array('msg' => 'Success: insurance claim rejected','data' => $asset), 200);
        }
    }

    public function transferAssets(){
        $input = Request::all();
        foreach($input['assets'] as $item){
            $asset = Asset::findOrFail($item);
            $asset->assigned_to_id = $input['recepient'];
            $asset->staff_responsible_id = $input['recepient'];
            $asset->status_id = $input['status'];
            $asset->date_of_issue = date('Y-m-d');
            $asset->assignee_type = 'individual';
            $asset->added_by_id = $this->current_user()->id;
            $asset->last_updated_by = $this->current_user()->id;
            $asset->disableLogging();
            $asset->save();

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $item,
                'transfered_by_id' => $this->current_user()->id,
                'transfered_to_id' => $input['recepient'],
                'recepient_type' => 'individual',
                'transfer_type' => 'transfer',
                'reason' => $input['reason']
            ]);

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset transfered to '. $asset->assigned_to->name .''])
                ->log('Transfered');
        }
            
        return Response()->json(array('msg' => 'Success: assets returned','data' => $input['assets']), 200);
    }

    public function getDonationDocument($id){
        try{
            $asset = Asset::findOrFail($id);

            $data = array('asset' => $asset);

            $pdf = PDF::loadView('pdf/asset_donation', $data);
            $file_contents  = $pdf->stream();
            $response = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
        catch (Exception $e ){
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
    }
    
    public function getPoliceAbstract($id){
        try{
            $asset          = Asset::findOrfail($id);
            $path           = '/fixed_assets/'.$id.'/'.$asset->loss->incident_file;
            $path_info      = pathinfo($path);
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }
        catch (Exception $e ){
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
    }

    public function getDonationTemplate($id){
        try{
            $asset = Asset::findOrFail($id);
            $transfer = AssetTransfer::where('asset_id', $id)->first();

            $data = array('asset' => $asset, 'transfer' => $transfer);

            $pdf = PDF::loadView('pdf/asset_donation_template', $data);
            $file_contents  = $pdf->stream();
            $response = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
        catch (Exception $e ){
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }
    }

    public function uploadDonationDoc($id){
        try{
            $input = Request::only('file');
            $file = $input['file'];
            $asset = Asset::findOrFail($id);
            $transfered_by_id = $asset->assigned_to_id;
            $asset->assigned_to_id = $asset->donation_to_id;
            $asset->staff_responsible_id = $asset->donation_to_id;
            $asset->assignee_type = 'government';
            $asset->location_id = null;
            $asset->donation_to_id = null;
            $asset->donation_recepient_type = null;
            $batch = new AssetTransferBatch;
            $batch->created_by_id = $this->current_user()->id;
            $batch->save();
            $asset->donation_batch_id = $batch->id;
            $asset->disableLogging();
            $asset->status_id = 14;
            $asset->save();

            FTP::connection()->makeDir('/fixed_assets');
            FTP::connection()->makeDir('/fixed_assets/'.$id);
            FTP::connection()->uploadFile($file->getPathname(), '/fixed_assets/'.$id.'/D'.$id.'.'.$file->getClientOriginalExtension());

            $asset->donation_document = 'D'.$id.'.'.$file->getClientOriginalExtension();
            $asset->save();

            // Log the transfer
            AssetTransfer::create([
                'asset_id' => $id,
                'transfered_by_id' => $transfered_by_id,
                'transfered_to_id' => $asset->assigned_to_id,
                'recepient_type' => $asset->assignee_type,
                'transfer_type' => 'donation',
                // 'reason' => $input['reason'],
                'batch_id' => $batch->id ?? null,
                'approved_by_id' => $this->current_user()->id
            ]);

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Donation document uploaded. Asset donation completed.'])
                ->log('Document uploaded, donation completed');

            return Response()->json(array('success' => 'Document uploaded','asset' => $asset), 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Something went wrong'], 500);
        }
    }

    public function getDonationReceipt($id){
        try{
            $asset          = Asset::findOrFail($id);
            $path           = '/fixed_assets/'.$asset->id.'/'.$asset->donation_document;
            $path_info      = pathinfo($path);
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }
        catch (Exception $e ){
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
    }

    public function downloadAssets(){
        $input = Request::all();
        $assets = Asset::query();

        $classes = [];
        $types = [];
        $groups = [];
        $names = [];
        $insurance_types = [];
        $statuses = [];
        $locations = [];
        $staff = []; 
        $donees = [];

        if(!empty($input['classes']))
        foreach($input['classes'] as $class){
            $classes[] = $class['id'];
        }
        if(!empty($input['types']))
        foreach($input['types'] as $type){
            $types[] = $type['id'];
        }
        if(!empty($input['groups']))
        foreach($input['groups'] as $group){
            $groups[] = $group['id'];
        }
        if(!empty($input['names']))
        foreach($input['names'] as $name){
            $names[] = $name['id'];
        }
        if(!empty($input['insurance_types']))
        foreach($input['insurance_types'] as $insurance_type){
            $insurance_types[] = $insurance_type['id'];
        }
        if(!empty($input['statuses']))
        foreach($input['statuses'] as $status){
            $statuses[] = $status['id'];
        }
        if(!empty($input['locations']))
        foreach($input['locations'] as $location){
            $locations[] = $location['id'];
        }
        if(!empty($input['staff']))
        foreach($input['staff'] as $s){
            $staff[] = $s['id'];
        }
        if(!empty($input['donees']))
        foreach($input['donees'] as $donee){
            $donees[] = $donee['id'];
        }

        if(!empty($classes)){
            $assets = $assets->whereIn('class_id', $classes);
        }
        if(!empty($types)){
            $assets = $assets->whereIn('type_id', $types);
        }
        if(!empty($groups)){
            $assets = $assets->whereIn('asset_group_id', $groups);
        }
        if(!empty($names)){
            $assets = $assets->whereIn('asset_name_id', $names);
        }
        if(!empty($insurance_types)){
            $assets = $assets->whereIn('insurance_type_id', $insurance_types);
        }
        if(!empty($statuses)){
            $assets = $assets->whereIn('status_id', $statuses);
        }
        if(!empty($staff)){
            $assets = $assets->whereIn('assigned_to_id', $staff);
        }
        if(!empty($locations)){
            $assets = $assets->whereIn('location_id', $locations);
        }
        if(!empty($donees)){
            $assets = $assets->whereIn('assigned_to_id', $donees);
        }

        $assets = $assets->get();

        $excel_data = [];
        foreach($assets as $row){
            $excel_row = array();
            $excel_row['asset_name'] = $row->asset_name->name ?? '';
            $excel_row['description'] = $row->description;
            $excel_row['type'] = $row->type->type ?? '';
            $excel_row['model'] = $row->model;
            $excel_row['tag'] = $row->tag;
            $excel_row['serial_no'] = $row->serial_no;
            $excel_row['status'] = $row->status->status ?? '';
            // $excel_row['date_of_issue'] = $row->date_of_issue;
            $excel_row['cost'] = $row->cost;
            $excel_row['supplier'] = $row->supplier->supplier_name ?? '';
            $excel_row['date_of_purchase'] = $row->date_of_purchase;
            $excel_row['class'] = $row->class->name ?? '';
            // $excel_row['insurance_type'] = $row->insurance_type->type ?? '';
            $excel_row['location'] = $row->location->location ?? '';
            $excel_row['assigned_to'] = $row->assignee_type == 'individual' ? ($row->assigned_to->name ?? '') : ($row->assignee->supplier_name ?? '');
            $excel_row['assignee_type'] = $row->assignee_type;
            $excel_row['insurance_value'] = $row->insurance_value;
            $excel_row['comments'] = $row->comments;
            
            $excel_data[] = $excel_row;
        }
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Allow'                            => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
            'Access-Control-Allow-Credentials' => 'true'
        ];
        // Build excel
        $file = Excel::create('CHAI asset list '.date('Y-m-d'), function($excel) use ($excel_data) {

            // Set the title
            $excel->setTitle('CHAI Asset list (filtered)');

            // Chain the setters
            $excel->setCreator('GIFMS')->setCompany('Clinton Health Access Initiative - Kenya');

            $excel->setDescription('A (filtered) list of CHAI assets genetated on '.date('Y-m-d'));

            $headings = array('Asset Name', 'Description', 'Asset type', 'Model', 'Tag', 'Serial No.', 'Status', 'Asset cost', 'Supplier', 'Date of purchase', 'Insurance class', 
            'Asset location', 'Assigned to', 'Assignee type', 'Insurance value', 'Comments');

            $excel->sheet('Assets list', function ($sheet) use ($excel_data, $headings) {
                $sheet->setStyle([
                    'borders' => [
                        'allborders' => [
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ]
                    ]
                ]);
                $i = 1;
                $alternate = true;
                foreach($excel_data as $data_row){
                    // if(empty($data_row['grant_details'])){
                    //     $data_row['grant_details'] = " ";
                    // }

                    $sheet->appendRow($data_row);
                    $sheet->row($i, function($row) use ($data_row, $alternate){
                        // if(!empty($data_row['total'])){
                            $row->setBorder('thin', 'none', 'none', 'none');
                            $row->setFontSize(10);
                        // }
                    });
                    if($alternate){
                        $sheet->cells('C'.$i.':K'.$i, function($cells) {
                            $cells->setBackground('#edf1f3');  
                            $cells->setFontSize(10);                          
                        });
                    }
                    $i++;
                    $alternate = !$alternate;
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
                // $sheet->row(2, function($row){
                //     $row->setFontSize(12);
                //     $row->setFontWeight('bold');
                //     $row->setBorder('none', 'thin', 'none', 'thin');
                // }); 
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 30,
                    'C' => 20,
                    'D' => 20,
                    'E' => 30,
                    'F' => 15,
                    'G' => 35,
                    'H' => 15,
                    'I' => 15,
                    'J' => 35,
                    'K' => 15,
                    'L' => 30,
                    'M' => 35,
                    'N' => 20,
                    'O' => 15,
                    'P' => 50
                ));
                $sheet->getStyle('K1')->getAlignment()->setWrapText(true);

                $sheet->setFreeze('C2');
                $sheet->setAutoFilter('B1:P1');
            });

        })->download('xlsx', $headers);
        
    }

    public function reportStolen(){
        try {
            $input = Request::only('file','id','date_lost','explanation');
            $file = $input['file'];
            $id = $input['id'];
            $loss = new AssetLoss;
            $loss->asset_id = $id;
            $loss->date_lost = $input['date_lost'];
            $loss->explanation = $input['explanation'];
            $loss->submitted_by_id = $this->current_user()->id;
            // $loss->insurer_id = $input['insurer_id'];
            $loss->save();

            $asset = Asset::findOrFail($id);
            $asset->disableLogging();
            $asset->status_id = 15;     // reported stolen or lost
            $asset->save();

            FTP::connection()->makeDir('/fixed_assets');
            FTP::connection()->makeDir('/fixed_assets/'.$id);
            FTP::connection()->uploadFile($file->getPathname(), '/fixed_assets/'.$id.'/L'.$id.'.'.$file->getClientOriginalExtension());

            $loss->disableLogging();
            $loss->incident_file = 'L'.$id.'.'.$file->getClientOriginalExtension();
            $loss->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Asset has been reported stolen or lost. Incident file has been uploaded.'])
                ->log('Reported stolen or lost');
            
            return Response()->json(array('success' => 'Asset reported as lost','asset' => $asset), 200);
            }
            catch(Exception $e){
                return response()->json(['error'=>'Something went wrong'], 500);
            }
    }

    public function claimAsset(){
        try {
            $input = Request::only('file', 'id', 'insurer_id');
            $id = $input['id'];
            $file = $input['file'];
            $loss = AssetLoss::where('asset_id', $id)->first();
            $loss->disableLogging();
            $loss->claim_submitted = 1;
            $loss->insurer_id = $input['insurer_id'];
            $loss->save();

            $asset = Asset::findOrFail($id);
            $asset->disableLogging();
            $asset->status_id = 18;
            $asset->save();

            FTP::connection()->makeDir('/fixed_assets');
            FTP::connection()->makeDir('/fixed_assets/'.$id);
            FTP::connection()->uploadFile($file->getPathname(), '/fixed_assets/'.$id.'/C'.$id.'.'.$file->getClientOriginalExtension());

            $loss->disableLogging();
            $loss->claim_file = 'C'.$id.'.'.$file->getClientOriginalExtension();
            $loss->save();

            // Logging
            activity()
                ->performedOn($asset)
                ->causedBy($this->current_user())
                ->withProperties(['detail' => 'Insurance claim has been uploaded'])
                ->log('Insurance claim submitted');
            
            return Response()->json(array('success' => 'Insurance claim has been submitted','asset' => $asset), 200);
            }
            catch(Exception $e){
                return response()->json(['error'=>'Something went wrong','msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()], 500);
            }
    }



    /**
     * Asset names
     */   
    
    public function addAssetName(){
        $input = Request::all();
        $asset = AssetName::create($input);
        return Response()->json(array('msg' => 'Success: asset name added','data' => $asset), 200);
    }

    public function updateAssetName(){
        $input = Request::all();
        $asset = AssetName::findOrFail($input['id']);
        $asset = $asset->update($input);
        return Response()->json(array('msg' => 'Success: asset type name','data' => $asset), 200);
    }

    public function getAssetName($id){
        $asset = AssetName::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssetNames(){
        try{
            $input = Request::all();
            $assets = AssetName::query();
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
        
                    $assets = AssetName::arr_to_dt_response( 
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

    public function deleteAssetName($id){
        $deleted = AssetName::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset name removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
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
                $assets = $assets->whereIn('id', [1,2,3,4,5,6,7,8,9]);
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
