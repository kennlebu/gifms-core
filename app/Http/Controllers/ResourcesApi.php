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
}
?>