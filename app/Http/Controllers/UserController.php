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
        $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                $credentials = $request->only('email', 'password');
// dd(Hash::make($credentials['password']));
                if (Auth::attempt($credentials)) {
                    // Instead of setting response within 'response' key, set it directly
                    $response = ['status' => true, 'message' => 'Login Successfully'];
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
        ];
        $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                if (User::where('email', $request->email)->exists()) {
                    return response()->json(['status' => false,'message' => 'Email already exists']);
                    exit;
                }

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);

                $user->save();

                if ($user) {
                    $response =  response()->json(['status' => true,'message' => 'User Registered Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to register user']);
                }
        
              }

              return $response;
    }
}