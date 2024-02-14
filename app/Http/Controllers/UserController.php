<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use App\Helpers\Helpers;



use App\Mail\MyCustomMail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     summary="this is for test",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="status", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Hello,i'm test."),
     *             }
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {"hXuRUGsEGuhGf6KM"}}
     *     }
     * )
     */
    public function test(){
        return ['status'=>true,'message'=>"Hello,i'm test."];
    }

    public function UserLogin(Request $request)
    {
        
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
                                 
                    $randomNumber = rand(100000, 999999);
                    if(isset($requestData['role']) && !empty($requestData['role']) && $requestData['role'] == 1){
                        if ($user->type == 1) {
                            $user->token = $randomNumber;
                            $user->save();

                            $helpers = new Helpers();
                            $result = $helpers->sendOtp($user);

              
                            $token = $user->createToken('AppName')->accessToken;
                            $response = ['status' => true, 'message' => 'Login Successfully','token'=>$token,'data'=>$user];
                        } else {
                            Auth::logout();
                            $response = ['status' => true, 'message' => 'Unauthorized access'];
                        }
                    }else{
                        if ($user->type == 2) {
                            $user->token = $randomNumber;
                            $user->save();

                            $helpers = new Helpers();
                            $result = $helpers->sendOtp($user);

               
                            $token = $user->createToken('AppName')->accessToken;

                            $response = ['status' => true, 'message' => 'Login Successfully','token'=>$token,'data'=>$user];
                        } elseif($user->type == 5){
                            $user->token = $randomNumber;
                            $user->save();

                            $token = $user->createToken('AppName')->accessToken;

                            $response = ['status' => true, 'message' => 'Login Successfully','token'=>$token,'data'=>$user];
                        }else {
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

                $randomNumber = rand(100000, 999999);
                $type = ''; 
                $user = new User();
                $user->name = $requestData['name'];
                $user->email = $requestData['email'];
                $user->password = Hash::make($requestData['password']);
                $user->token = $randomNumber;
                if(isset($requestData['type']) && !empty($requestData['type']) && $requestData['type'] == 5){
                    $type = $requestData['type'];
                    $user->type = $requestData['type'];
                }
                $lastInsertId = $user->id;

                $user->save();

                if($type != 5){
                    $helpers = new Helpers();
                    $result = $helpers->sendOtp($user);
                }else{
                    if($type == 5){
                        $result = true;
                    }
                }
                if ($result) {
                    $response =  response()->json(['status' => true,'message' => 'User Registered Successfully','data'=>$user]);
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
            // 'password' => 'required',
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
    

            $randomNumber = rand(100000, 999999);
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $user_password = substr(str_shuffle($characters), 0, 8);

            $user = new User();
   

            $user->name = $requestData['name'];
            $user->email = $requestData['email'];
            $user->token = $randomNumber;
            $user->password = $user_password;

            if (isset($requestData['contact_no'])) {
                $user->contact_no = $requestData['contact_no'];
            }
     
    
            $user->save();

            $subject = 'Password for User Login';
            $key = 4;
            $body = "User Details!\nName: {$request->input('name')}\nEmail: {$request->input('email')}\nPassword: {$user_password}";

            // Email data
            $to = $request->input('email');
            $data = [
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $to,
                'token' => $randomNumber,
                'password' => $user_password,
                'id' => $user->id,
                'key' => $key,
            ];


            $helpers = new Helpers();
            $result = $helpers->sendEmail($to, $subject, $body, $key, $data);

    
            if ($result) {
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

            // 'password' => 'required',
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
            // $user->password = Hash::make($requestData['password']);

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

    $data = User::orderBy('id','DESC')->get();
   
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

    public function LoginUserUpdateProfile(Request $request)
    { 
    
        $response = array("status" => false, 'message' => '');
        $user = Auth::guard('api')->user();
        
        $user_id = $user->id;
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user_id,'id'),
            ],

            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

     
  
     $user->name = $requestData['name'];
     $user->email = $requestData['email'];
     $user->password = $requestData['password'];

     $user->save();



    if($user)
    {
       $response = ['status' => true, 'message' => 'Profile Data Updated Successfully!'];
    }
    else {
        $response = ['status' => false,'message' => 'Failed to update profile'];

    }
}

    return $response;


    }


    public function sendOTP(Request $request) {
      
        $user = Auth::guard('api')->user();
  
        $to = $user->email;
       
        $randomNumber = rand(100000, 999999);
    
        $user->token = $randomNumber;
       
        $user->save();

        $subject = 'OTP for Verification';
        $key = 1;
        $body = 'Your OTP is: ' . $randomNumber;

        $data = [
                    'name' => $user->name,
                    'email' => $to,
                    'token' => $randomNumber,
                    'id' => $user->id,
                    'key'=> $key,


        ];
    
        // Create an instance of the Helpers class
        $helpers = new Helpers();
        $helpers->sendEmail($to, $subject,$body,$key,$data);
        return $helpers->sendEmail($to, $subject,$body,$key,$data);
        // return response()->json(['message' => 'Email sent successfully']);
    }

//    public function Forgotpassword(Request $request)
// {
   

//     $responseData = ['status' => false, 'message' => ''];

//     $rules = [
//         'email' => 'required|email',
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $responseData['message'] = $validator->messages();
//         return $responseData; // Return validation error messages
//     }

//     $email = $request['email'];
//     $url = $request['url'];
    
//     // Check if email exists
//     $user = User::where('email',$email)->first();
   


//     if (!$user) {
//         $responseData['message'] = 'User not found';
//         return $responseData; // Return error if email doesn't exist
//     }

//     // Generate random token
//     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     $randomString = substr(str_shuffle($characters), 0, 16);
   
//     // Save the token to the user
//     $user->remember_token = $randomString;
//     $user->save();

//     // Email details
//     $to = $email;
//     $subject = 'Forgot Password';
//     $key = 2;
//     $body = 'Your Key is: ' . $randomString;

//     $data = [
//         'name' => $user->name,
//         'email' => $to,
//         'remember_token' => $randomString,
//         'id' => $user->id,
//         'key' => $key,
//         'url' => $url,
//     ];

//     // Send email
//     $helpers = new Helpers();
//     $helpers->sendEmail($to, $subject, $body, $key, $data);

//     $responseData['status'] = true;
//     $responseData['message'] = 'Email sent successfully';
//     return $responseData;
// }
    
   

// public function Resetpassword(Request $request, $rememberToken)
// public function Resetpassword(Request $request)
// {
//     $responseData = ['status' => false, 'message' => ''];

//     $rules = [
//         'email' => 'required|email',
//         // Add any other validation rules for password and confirm_password if needed
//     ];

//     $requestData = $request->all();

//     $validator = Validator::make($requestData, $rules);

//     if ($validator->fails()) {
//         $responseData['message'] = $validator->messages();
//         return $responseData;
//     } else {
//         $email = $requestData['email'];
//         // $randomNumber = rand(100000, 999999);

//         // Check if the user with the provided email exists
//          $user = User::where('email', $email)->first();

//     //     if ($user) {
//     //         // Update the user's token with the generated random number
//     //         $user->token = $randomNumber;
//     //         $user->save();

//     //         $subject = 'OTP for Verification';
//     //         $key = 1;
//     //         $body = 'Your OTP is: ' . $randomNumber;

//     //         $to = $user->email; // Fix: Use $user->email instead of undefined variable $to

//     //         $data = [
//     //             'name' => $user->name,
//     //             'email' => $to,
//     //             'token' => $randomNumber,
//     //             'id' => $user->id,
//     //             'key' => $key,
//     //         ];

//     //         // Create an instance of the Helpers class
//              $helpers = new Helpers();

//     //         // Send the email
//             $result = $helpers->sendotp($user);
//               $result = $helpers->sendEmail($to, $subject, $body, $key, $data);
             

//     //         if ($result) {
//     //             // Email sent successfully
//     //             $responseData['status'] = true;
//     //             $responseData['message'] = 'OTP sent successfully';
//     //             $responseData['data'] = $user;
//     //         } else {
//     //             // Email sending failed
//     //             $responseData['message'] = 'Failed to send OTP email';
//     //         }
//     //     } else {
//     //         // User not found
//     //         $responseData['message'] = 'User not found';
//     //     }
//      }

//     // return $responseData;
// }

public function resetPassword(Request $request)
{
    $responseData = ['status' => false, 'message' => ''];

    $rules = [
        'email' => 'required|email',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $responseData['message'] = $validator->messages();
        return $responseData;
    }

    $email = $request->input('email');
    $user = User::where('email', $email)->first();

    if ($user) {
        $helpers = new Helpers();
        $result = $helpers->sendOtp($user);

        if ($result) {
            $responseData['status'] = true;
            $responseData['message'] = 'OTP sent successfully';
            $responseData['data'] = $user;
        } else {
            $responseData['message'] = 'Failed to send OTP email';
        }
    } else {
        $responseData['message'] = 'User not found';
    }

    return $responseData;
}

public function verify_otp(Request $request)
{
    $responseData = ['status' => false, 'message' => ''];

    $rules = [
        'user_id' => 'required',
        'new_otp' => 'required',
        // Add any other validation rules for password and confirm_password if needed
    ];

    $requestData = $request->all();
 
    $validator = Validator::make($requestData, $rules);
    if ($validator->fails()) {
        $responseData['message'] = $validator->messages();
        // return $responseData;
    } else {
        $user_id = $requestData['user_id'];
        $verify_otp = $requestData['new_otp'];
       
        $user = User::where('id', $user_id)->first();
        if($user){
        
        $send_otp =  $user->token;
      

        if($verify_otp == $send_otp)
        {
                $responseData['status'] = true;
                $responseData['message'] = 'OTP verify successfully';
                $responseData['data'] = $user;
        }
        else{
            $responseData['message'] = 'Please enter valid OTP';
        }
    }
    else{
        $responseData['message'] = 'User not found';

    }

    }
    return $responseData;

}

public function resend_password(Request $request)
{
    $responseData = array("status" => false, 'message' => '');

    $rules = [
        'user_id' => 'required|exists:users,id',
        'password' => 'required',
        'confirm_password' => 'required|same:password',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $responseData['message'] = $validator->messages();
    } else {
        $user = User::find($request->input('user_id'));

        if ($user) {
            $user->password = bcrypt($request->input('password')); // Hash the password for security
            $user->save();
            
            $responseData['status'] = true;
            $responseData['message'] = 'Password created successfully';
        } else {
            $responseData['message'] = 'User not found';
        }
    }

    return $responseData;
}


    
}