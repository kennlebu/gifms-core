<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    public function authenticate(){

    	$credentials = request()->only('email','password');

        // print_r($credentials);

    	try{

    		$token = JWTAuth::attempt($credentials);

    		if(!$token){

    			return response()->json(['error'=>'invalid credentials'], 401);

    		}
    	}
    	catch (JWTException $e){

    			return response()->json(['error'=>'something went wrong'], 500);

    	}

		return response()->json(['token'=>$token], 200);

    }
}
