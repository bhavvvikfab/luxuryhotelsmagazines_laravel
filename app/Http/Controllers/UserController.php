<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function UserLogin(Request $request){
        
        $response = array("status"=>false,'message' => '');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $requestData = $request->json()->all();
// dd($requestData);
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                  // $credentials = $request->only('email', 'password');
                  $credentials['email'] = $requestData['email'];
                  $credentials['password'] = $requestData['password'];

                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                    
                    if(isset($requestData['role']) && !empty($requestData['role']) && $requestData['role'] == 1){
                        if ($user->type == 1) {
                            // $token = $user->createToken('token-name')->plainTextToken;
                            $token = $user->createToken('AppName')->accessToken;
                            $response = ['status' => true, 'message' => 'Login Successfully','token'=>$token];
                        } else {
                            Auth::logout();
                            $response = ['status' => true, 'message' => 'Unauthorized access'];
                        }
                    }else{
                        if ($user->type == 2) {
                            // $token = $user->createToken('token-name')->plainTextToken;
                            $token = $user->createToken('AppName')->accessToken;
                            
                            $response = ['status' => true, 'message' => 'Login Successfully','token'=>$token];
                        } else {
                            Auth::logout();
                            $response = ['status' => true, 'message' => 'Unauthorized access'];
                        }
                    }
                } else {
                    $response = ['status' => false, 'message' => 'Invalid email or password'];
                }
              }
              return $response;
    }

    public function UserRegister(Request $request)
    {
       $response = array("status"=>false,'message' => '');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];
        $requestData = $request->json()->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                if (User::where('email', $requestData['email'])->exists()) {
                    return response()->json(['status' => false,'message' => 'Email already exists']);
                    exit;
                }

                $user = new User();
                $user->name = $requestData['name'];
                $user->email = $requestData['email'];
                $user->password = Hash::make($requestData['password']);

                $user->save();

                if ($user) {
                    $response =  response()->json(['status' => true,'message' => 'User Registered Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to register user']);
                }
        
              }

              return $response;
    }

    public function UserLogout(Request $request)
 
{
 
    $user = Auth::guard('api')->user();
    if ($user) {
        // Auth::guard('api')->logout();
        $user->tokens()->delete();
        // $user = Auth::guard('api')->user();
    
    } else {
        dd('User not authenticated');
    }

       $response =  response()->json([
            'status' => true,
            'message' => 'Logout Successfully!',
        ]);
        return  $response;

    }

    public function LoginUserProfile(Request $request)
    { 

        $response = array("status"=>false,'message' => '');

     $user = Auth::guard('api')->user();
    if($user)
    {
       $response = ['status' => true, 'message' => 'Profile Data Successfully!','profile_data'=>$user];
    }
    else {
        $response = ['status' => false,'message' => 'Failed to register user'];

    }
    return $response;


    }
}

