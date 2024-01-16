<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;


use App\Mail\MyCustomMail;
use Illuminate\Support\Facades\Mail;


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

    // public function Forgotpassword(Request $request)
    // {
      
    //     $response = array("status"=>false,'message' => '');

    //     $rules = [
    //         'email' => 'required|email',
    //     ];

    //     $requestData = $request->all();
      

    //     $email = $requestData['email'];
      
    //     $validator = Validator::make($requestData, $rules);

    //     if ($validator->fails()) {
    //         $response['message'] = $validator->messages();
    //     } else {
    //         $email = $requestData['email'];
    
    //         if (User::where('email', $email)->exists()) {
    //             // Data to pass to the email view (you can customize this data)
    //             $data = ['name' => 'John Doe'];
    
    //             // Send the custom email
    //             Mail::to($email)->send(new MyCustomMail($data));
    
    //             return response()->json(['message' => 'Email sent successfully']);
    //         } else {
    //             return response()->json(['status' => false, 'message' => 'Email not registered!']);
    //         }
    //     }
    // }
    public function Forgotpassword(Request $request)
    {
        $responseData = ['status' => false, 'message' => ''];

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Shuffle the characters to create randomness
        $shuffledString = str_shuffle($characters);
    
        // Take the first $length characters from the shuffled string
        $randomString = substr($shuffledString, 0, 16);

    
        $rules = [
            'email' => 'required|email',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $responseData['message'] = $validator->messages();
        } else {
            $email = $request->input('email');
          
    
            try {
                if (User::where('email', $email)->exists()) {
                    // Retrieve user data from the database
                    $user = User::where('email', $email)->first();
                
                    // Data to pass to the email view
                    $data = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'key' => $randomString,
                        'id' => $user->id,
                        // Add more data as needed
                    ];
                
               

                    // Send the custom email
                    Mail::to($email)->send(new MyCustomMail($data));
                
                    $responseData['status'] = true;
                    $responseData['message'] = 'Email sent successfully';
                } else {
                    // User does not exist
                    $responseData['status'] = false;
                    $responseData['message'] = 'User not found';
                }
                 
            } catch (\Exception $e) {
                // Log or handle the exception as needed
                $responseData['message'] = 'Error sending email: ' . $e->getMessage();
            }
        }
    
        return response()->json($responseData);
    }
    
    // public function Resetpassword(Request $request)
    // {
    //     echo "tyh";

       


    //     $response = array("status"=>false,'message' => '');
    //     $user = Auth::guard('api')->user();

    //     $requestData = $request->all();


 
    //     $rules = [
    //         // 'old_password' => 'required',
    //         'new_password' => 'required|min:6',
    //         'confirm_password' => 'required|same:new_password',
    //     ];
    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    //     } else {
    //         try {
    //             // if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
    //             //     $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
    //             // }
    //             //  else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
    //                 if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
    //                 $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
    //             } else {
    //                 User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
    //                 $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
    //             }
    //         } catch (\Exception $ex) {
    //             if (isset($ex->errorInfo[2])) {
    //                 $msg = $ex->errorInfo[2];
    //             } else {
    //                 $msg = $ex->getMessage();
    //             }
    //             $arr = array("status" => 400, "message" => $msg, "data" => array());
    //         }
    //     }
    //     return \Response::json($arr);
    // }

    // public function resetPassword(Request $request)
    // {
    //     $response = array("status" => false, 'message' => '');
    
    //     // Check if the user is authenticated
    //     // if (!Auth::guard('api')->check()) {
    //     //     $arr = array("status" => 401, "message" => "Unauthorized", "data" => array());
    //     //     return \Response::json($arr);
    //     // }
    
    //     $user = Auth::guard('api')->user();
    //     dd($user);
    //     $requestData = $request->all();
    
    //     $rules = [
    //         'new_password' => 'required|min:6',
    //         'confirm_password' => 'required|same:new_password',
    //     ];
    
    //     $validator = Validator::make($requestData, $rules);
    
    //     if ($validator->fails()) {
    //         $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    //     } else {
    //         try {
    //             if ((Hash::check($requestData['new_password'], $user->password)) == true) {
    //                 $arr = array("status" => 400, "message" => "Please enter a password that is not similar to the current password.", "data" => array());
    //             } else {
    //                 // Use $user->id instead of undefined $userid
    //                 User::where('id', $user->id)->update(['password' => Hash::make($requestData['new_password'])]);
    //                 $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
    //             }
    //         } catch (\Exception $ex) {
    //             if (isset($ex->errorInfo[2])) {
    //                 $msg = $ex->errorInfo[2];
    //             } else {
    //                 $msg = $ex->getMessage();
    //             }
    //             $arr = array("status" => 400, "message" => $msg, "data" => array());
    //         }
    //     }
    //     return \Response::json($arr);
    // }
    
    // public function resetPassword(Request $request, $token)
    // {
    //     $this->validateReset($request);
    
    //     $response = $this->broker()->reset(
    //         $this->credentials($request),
    //         function ($user, $password) {
    //             $this->resetPassword($user, $password);
    //         }
    //     );
    
    //     return $response == Password::PASSWORD_RESET
    //         ? response()->json(['message' => 'Password reset successfully'])
    //         : response()->json(['error' => 'Unable to reset password'], 422);
    // }

    // public function Resetpassword(Request $request)
    // {

    //     echo "tgrt";
    //     die;


    //    $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed|min:8',
    //         'token' => 'required',
    //     ]);
    //     $response = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => bcrypt($password),
    //                 'remember_token' => Str::random(60),
    //             ])->save();
    //         }
    //     );

    //     return $response == Password::PASSWORD_RESET
    //         ? response()->json(['message' => 'Password reset successfully'], 200)
    //         : response()->json(['message' => 'Password reset failed'], 400);
    // }

    public function Resetpassword($token, $id)
{  


    // $newPassword = $request->input('new_password');
    // $confirmPassword = $request->input('confirm_password');
    // $id = $request->input('userid');
    // $resetPasswordKey = $request->input('reset_password_key');

    // if ($confirmPassword == $newPassword) {
    //     $user = User::find($id);

    //     if (!$user || $user->forgot_pass_key != $resetPasswordKey) {
    //         return response()->json(['status' => 'fail', 'message' => 'Invalid user or reset password key'], 400);
    //     }

    //     $user->password = Hash::make($newPassword);
    //     $user->forgot_pass_key = null;
    //     $user->save();

    //     return response()->json(['status' => 'success', 'message' => 'Password updated successfully']);
    // } else {
    //     return response()->json(['status' => 'fail', 'message' => 'Confirm password does not match with new password'], 400);
    // }
    return view('reset password');
}

}

    
