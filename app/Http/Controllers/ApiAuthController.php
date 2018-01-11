<?php
namespace App\Http\Controllers;
use Exception;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\StaffModels\Staff;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyChangePassword;


class ApiAuthController extends Controller{
    public function authenticate(){
        $credentials = request()->only('email','password');
        try{
            $token = JWTAuth::attempt($credentials);
            if(!$token){
                return response()->json(['error'=>'invalid credentials'], 401);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>$e,'stack_trace'=>$e->getTrace()], 500);
        }catch(\Exception $ex){
            return response()->json(['error'=>$ex->getMessage(),'stack_trace'=>$ex->getTrace()], 500);
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
        $user = Staff::with(['roles','programs','assigned_projects'])->find($user['id']);

        return response()->json(compact('user'));
    }










    public function userCan(){
        $req = request()->only('permissions');
        try{
            $user;
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            if ($user->can(explode(',', $req['permissions']))) {
                return response()->json(['status'=>true], 200);
            }else{
                return response()->json(['status'=>false], 200);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }

    }










    public function userHasRole(){
        $req = request()->only('roles');
        try{
            $user;
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            if ($user->hasRole(explode(',', $req['roles']))) {
                return response()->json(['status'=>true], 200);
            }else{
                return response()->json(['status'=>false], 200);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }

    }












    public function UpdateMyProfile(){
        $req = request()->only(
            'f_name',
            'l_name',
            'mobile_no',
            'mpesa_no',
            'bank_account',
            'bank_id',
            'bank_branch_id',
            'payment_mode_id'
        );
        try{
            $user;
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }else{

                $staff = Staff::find($user->id);

                $staff->f_name                   =               $req['f_name'];
                $staff->l_name                   =               $req['l_name'];
                $staff->mobile_no                =               $req['mobile_no'];
                $staff->mpesa_no                 =               $req['mpesa_no'];
                $staff->bank_account             =               $req['bank_account'];
                $staff->bank_id                  =   (int)       $req['bank_id'];
                $staff->bank_branch_id           =   (int)       $req['bank_branch_id'];
                $staff->payment_mode_id          =   (int)       $req['payment_mode_id'];

                if($staff->save()) {
                    
                    return response()->json(['status'=>true,'user'=>$staff], 200);
                }
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
    }









    public function changePassword(){
        $user = JWTAuth::parseToken()->authenticate();
        $req = request()->only('password','new_password');
        $credentials = request()->only('email','password');
        $credentials['email'] = $user->email;
        try{
            $token = JWTAuth::attempt($credentials);
            if(!$token){
                return response()->json(['error'=>'invalid credentials'], 401);
            }else{


                $staff = Staff::find($user->id);

                $staff->password             =               bcrypt($req['new_password']);

                if($staff->save()) {
                    
                    return response()->json(['status'=>true,'user'=>$staff], 200);
                }
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
        return response()->json(['token'=>$token], 200);
    }









    public function forgotPassword(){

        $req = request()->only('email');

        try{
            $usr = Staff::where("email",$req['email'] )->first();

            $staff = Staff::find($usr->id);


            $new_password = $this->generateRandomString();  

            $staff->password             =               bcrypt($new_password);

            if($staff->save()) {
                Mail::send(new NotifyChangePassword($staff, $new_password));                    
                return response()->json(['status'=>true,'user'=>$staff], 200);
            }


            
        }catch (JWTException $e){
            return response()->json(['error'=>'something went wrong'], 500);
        }
    }
}