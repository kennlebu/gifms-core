<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Exception;

class HelpApi extends Controller
{
    //
    public function __construct()
    {
        
    }



        /**
     * Operation getTemplate
     *
     * Mobile Payments Template.
     *
     *
     * @return Http response
     */
    public function getUserGuide()
    {



        try{



            $path           = '/guides/GIFMS_USER_GUIDE.pdf';

            $path_info      = pathinfo($path);

            $ext            = $path_info['extension'];


            $basename       = $path_info['basename'];

            $file_contents  = FTP::connection()->readFile($path);

            Storage::put($path , $file_contents);

            $url            = storage_path("app".$path);

            $file           = File::get($url);

            $response       = Response::make($file, 200);

            $response->header('Content-Type', $this->get_mime_type($basename));

            return $response;  
        }catch (Exception $e ){            

            $response       = Response::make("", 500);

            $response->header('Content-Type', 'application/pdf');
            //file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , '== '>$e, FILE_APPEND);

            return $response;  

        }
    }
}
