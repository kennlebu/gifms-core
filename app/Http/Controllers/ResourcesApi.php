<?php
namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use Exception;
use App;
use Illuminate\Support\Facades\Response;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\File;
use App\Models\ResourcesModels\Resource;

class ResourcesApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }


    public function downloadTemplate($type){
        try{
            $path2 = '';
            if($type=='allocations') $path2 = '/allocation_template.xlsx';
            elseif($type=='mpesa') $path2 = '/MPESA_TEMPLATE.xlsx';

            $path           = '/templates'.$path2;
            $path_info      = pathinfo($path);
            $ext            = $path_info['extension'];
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);

            $url            = storage_path("app".$path);
            $file           = File::get($url);

            $response       = Response::make($file, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  

        }catch (Exception $e ){            

            $response       = Response::make("", 500);
            $response->header('Content-Type', 'application/vnd.ms-excel');
            return $response;  

        }
    }

    public function addResource(){
        $input = Request::all();
        try{
            $category = $input['category'];
            $file = $input['file'];
            $name = $input['resource_name'];
            $user = JWTAuth::parseToken()->authenticate();

            FTP::connection()->makeDir('/gifms_resources');
            FTP::connection()->uploadFile($file->getPathname(), '/gifms_resources/'.$name.'.'.$file->getClientOriginalExtension());

            $resource = new Resource;
            $resource->name = $name;
            $resource->category = $category;
            $resource->doc_type = $file->getClientOriginalExtension();
            $resource->staff_id = $user->id;
            $resource->save();

            return response()->json(array('msg' => 'File added'), 200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function getResources(){
        try{
            $files = FTP::connection()->getDirListing('gifms_resources');
            $resources = Resource::with('added_by')->get();
            return response()->json($resources, 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }

    public function downloadResource($id){
        try{
            $resource = Resource::find($id);
            $file_name = $resource->name.'.'.$resource->doc_type;

            $path           = '/gifms_resources/'.$file_name;
            $path_info      = pathinfo($path);
            $ext            = $path_info['extension'];
            $basename       = $path_info['basename'];
            $file_contents  = FTP::connection()->readFile($path);

            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }





    public function getResourceById($resource_id){
        try{
            $resource = Resource::find($resource_id);
            return response()->json($resource, 200,array(),JSON_PRETTY_PRINT);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }



    public function deleteResource($resource_id)
    {
        $deleted = Resource::destroy($resource_id);

        if($deleted){
            return response()->json(['msg'=>"Resource removed"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Resource not found"], 404,array(),JSON_PRETTY_PRINT);
        }

    }



    public function editResource(){
        try{
            $input = Request::all();         
            $category = $input['category'];
            $file = $input['file'];
            $name = $input['resource_name'];

            $resource = Resource::findOrFail($input['resource_id']);

            if($file!=0){
                FTP::connection()->makeDir('/gifms_resources');
                FTP::connection()->uploadFile($file->getPathname(), '/gifms_resources/'.$name.'.'.$file->getClientOriginalExtension());
                $resource->doc_type = $file->getClientOriginalExtension();
            }

            $resource->name = $name;
            $resource->category = $category;

            if($resource->save()){
                return Response()->json(array('msg' => 'Resource updated','resource' => $resource), 200);
            }
        }
        catch(\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }
}
?>