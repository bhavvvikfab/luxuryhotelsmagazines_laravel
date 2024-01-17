<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;

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

    public function AddUser(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'contact_no' => 'nullable', // Making 'contact_no' optional
        ];
        $requestData = $request->all();
      

        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
           

            if (User::where('email', $requestData['email'])->exists()) {
              
                return response()->json(['status' => false, 'message' => 'Email already exists']);
                exit;
            }
    
            $user = new User();
   

            $user->name = $requestData['name'];
            $user->email = $requestData['email'];
            $user->password = $requestData['password'];
            if (isset($requestData['contact_no'])) {
                $user->contact_no = $requestData['contact_no'];
            }
     
    
            $user->save();
    
            if ($user) {
                $response =  response()->json(['status' => true, 'message' => 'User Added Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add user']);
            }
        }
    
        return $response;
    }
    
    public function UpdateUser(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all();
    
        $user_id = $requestData['user_id'];
    
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user_id,'id'),
            ],

            'password' => 'required',
            // 'confirm_password' => 'required|same:password',
            'contact_no' => 'nullable', // Making 'contact_no' optional
        ];

        $validator = Validator::make($requestData, $rules);


        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $user = User::find($user_id);
    
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found']);
            }
    
            $user->name = $requestData['name'];
            $user->email = $requestData['email'];
            $user->password = Hash::make($requestData['password']);

            if (isset($requestData['contact_no'])) {
                $user->contact_no = $requestData['contact_no'];
            }
    
            $user->save();
    
            if ($user) {
                $response = response()->json(['status' => true, 'message' => 'User Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update user']);
            }
        }
    
        return $response;
    }
    public function EditUser(Request $request)
    {
    
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'user_id' => 'required',
            
        ];
    
        // $requestData = $request->json()->all();
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $user_id = $request['user_id'];
    
            $user = User::find($user_id);
        
            if ($user) {
               
                $response['status'] = true;
                $response['message'] = $user;
                // Do something with $hotel_amenity
         
            } else {
                $response['message'] = 'User not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }
    

    public function DeleteUser(Request $request)
{

    // echo "rtrt";

    $response = array("status" => false, 'message' => '');

    $rules = [
        'user_id' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {


    

    $user_id = $request['user_id'];


    $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);

    
    }
   
}
public function AllUser()
{

    $data = User::all();
   
        return response()->json(['status' => true,'data'=>$data]);

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

