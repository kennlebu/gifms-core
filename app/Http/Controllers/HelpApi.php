<?php

namespace App\Http\Controllers;

use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Exception;

class HelpApi extends Controller
{
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
            $basename       = $path_info['basename'];
            $url            = storage_path("app".$path);
            $file           = File::get($url);
            $response       = Response::make($file, 200);
            $response->header('Content-Type', $this->get_mime_type($basename));
            return $response;  
        }catch (Exception $e ){            
            $response       = Response::make("", 500);
            $response->header('Content-Type', 'application/pdf');
            return $response;  

        }
    }
}
