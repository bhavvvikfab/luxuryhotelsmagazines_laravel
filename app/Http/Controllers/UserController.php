<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserLogin(Request $request){
        
        $response = array("status"=>false,'message' => '');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                // print_r($validator->messages());die;
                $response['message'] = $validator->messages();
              } else {
              //process the request
                $credentials = $request->only('email', 'password');
// dd(Hash::make($credentials['password']));
                if (Auth::attempt($credentials)) {
                    // Instead of setting response within 'response' key, set it directly
                    $response = ['status' => true, 'message' => 'Login successful'];
                } else {
                    $response = ['status' => false, 'message' => 'Invalid email or password'];
                }
                
                // if (Auth::attempt($credentials)) {
                    
                //     $response['response'] = response()->json(["status"=>true,"message"=>1]);
                // }
                // else {
                //     $response = ['status' => false, 'message' => 'Invalid email or password'];
                // }
              }
              return $response;
    }
}
