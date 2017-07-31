<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\StaffModels\Staff;

class ApiAuthController extends Controller{




    public function authenticate(){

        $credentials = request()->only('email','password');

        try{

            $token = JWTAuth::attempt($credentials);

            if(!$token){

                return response()->json(['error'=>'invalid credentials'], 401);

            }
        }catch (JWTException $e){

           return response()->json(['error'=>'something went wrong'], 500);

       }

       return response()->json(['token'=>$token], 200);

   }




















    public function authenticateSecondUser(){

        // $credentials = request()->only('email');


        try{

            $token = JWTAuth::attempt($credentials);

            if(!$token){

                return response()->json(['error'=>'invalid credentials'], 401);

            }
        }catch (JWTException $e){

           return response()->json(['error'=>'something went wrong'], 500);

       }

       return response()->json(['token'=>$token], 200);

   }
























   public function getAuthenticatedUser(){


    try{

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

    }catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

        return response()->json(['token_expired'], $e->getStatusCode());

    }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        return response()->json(['token_invalid'], $e->getStatusCode());

    }catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

        return response()->json(['token_absent'], $e->getStatusCode());

    }

    // the token is valid and we have found the user via the sub claim

    $user = Staff::with(['roles'])->find($user['id']);
    
    return response()->json(compact('user'));
  }
}
