<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assets\Asset;

class AssetApi extends Controller
{
    public function addAsset(){
        $input = Request::all();
        $asset = Asset::create($input);
        return Response()->json(array('msg' => 'Success: asset added','data' => $asset), 200);
    }

    public function updateAsset($id){
        $input = Request::all();
        $asset = Asset::findOrFail($id);
        $asset = $asset->update($input);
        $asset = $asset->refresh();
        return Response()->json(array('msg' => 'Success: asset updated','data' => $asset), 200);
    }

    public function getAsset($id){
        $asset = Asset::findOrFail($id);           
        return response()->json($asset, 200, array(), JSON_PRETTY_PRINT);
    }

    public function getAssets(){
        try{
            $input = Request::all();
            $assets = Asset::query();
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
                $assets = $assets->where('status_id', $input['status_id']);
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
        $deleted = MeetingRoom::destroy($id);
        if($deleted){
            return response()->json(['msg'=>"Asset removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500, array(), JSON_PRETTY_PRINT);
        }
    }
}
