<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Mail;

class EmployerController extends Controller
{
    // public function AddEmployer(Request $request)
    // {
 
    //     $response = array("status" => false, 'message' => '');
    //     $rules = [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         // 'password' => 'required',
    //         'type' => 'required',
    //         'contact_no' => 'nullable', // Making 'contact_no' optional
    //     ];
    //     $requestData = $request->all();
      

    //     $validator = Validator::make($requestData, $rules);
    
    //     if ($validator->fails()) {
    //         $response['message'] = $validator->messages();
    //     } else {
           
    //         $randomNumber = rand(100000, 999999);
            
    //         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //         $employee_password = substr(str_shuffle($characters), 0, 8);

    //         $user = new User();
   
    //         $user->name = $requestData['name'];
    //         $user->user_name = $requestData['user_name'];
    //         $user->email = $requestData['email'];
    //         $user->token  = $randomNumber;
    //         $user->password = $employee_password;
    //         $user->type = $requestData['type'];
    //         if (isset($requestData['contact_no'])) {
    //             $user->contact_no = $requestData['contact_no'];
    //         }
     
    //         $user->save();


    //         $subject = 'Password for Employer Login';
    //         $key = 2;
    //         $body = 'Employer Details!
    //         Name: ' . $requestData['name'] . '
    //         Email: ' . $requestData['email'] . '
    //         Password: ' . $employee_password;
    

    //         $to = $requestData['email'];

    //         $data = [
    //             'name' => $user->name,
    //             'email' => $to,
    //              'token' => $randomNumber,
    //             'password' => $employee_password,
    //             'id' => $user->id,
    //             'key' => $key,
    //         ];

    //             $helpers = new Helpers();
    //             $result = $helpers->sendEmail($to, $subject, $body, $key, $data);
    
    //         if ($user) {
    //             $response =  response()->json(['status' => true, 'message' => 'Employer Added Successfully']);
    //         } else {
    //             $response = response()->json(['status' => false, 'message' => 'Failed to add employer']);
    //         }
    //     }
    
    //     return $response;
    // }

    public function AddEmployer(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        
        // Validation rules
        $rules = [
            'name' => 'required',
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'type' => 'required',
            'contact_no' => 'nullable', // Making 'contact_no' optional
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            // Generate random number and password
            $randomNumber = rand(100000, 999999);
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $employee_password = substr(str_shuffle($characters), 0, 8);

            // Create a new User instance
            $user = new User();

            // Assign values to User instance
            $user->name = $request->input('name');
            $user->user_name = $request->input('user_name');
            $user->email = $request->input('email');
            $user->token = $randomNumber;
            $user->password = $employee_password;
            $user->type = $request->input('type');
            
            // Check if 'contact_no' is set before assigning
            if ($request->has('contact_no')) {
                $user->contact_no = $request->input('contact_no');
            }

            // Save the user
            $user->save();

            // Email details
            $subject = 'Password for Employer Login';
            $key = 3;
            $body = "Employer Details!\nName: {$request->input('name')}\nEmail: {$request->input('email')}\nPassword: {$employee_password}";

            // Email data
            $to = $request->input('email');
            $data = [
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $to,
                'token' => $randomNumber,
                'password' => $employee_password,
                'id' => $user->id,
                'key' => $key,
            ];

     

            // Use the helper function to send the email
            $helpers = new Helpers();
            $result = $helpers->sendEmail($to, $subject, $body, $key, $data);

            // Check if the user was successfully saved
            if ($result) {
                $response = response()->json(['status' => true, 'message' => 'Employer Added Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add employer']);
            }
        }

        return $response;
    }

    public function AllEmployer()
{

    // $data = User::all();
    $data = User::whereIn('type', [1, 3, 4])->get();

    return response()->json(['status' => true,'data'=>$data]);

}

public function EditEmployer(Request $request)
    {
    
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'employer_id' => 'required',
        ];
    
        // $requestData = $request->json()->all();
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $employer_id = $request['employer_id'];
    
            $employer = User::where('id', $employer_id)
                ->whereIn('type', [1, 3, 4])
                ->first();

        
            if ($employer) {
               
                $response['status'] = true;
                $response['message'] = $employer;
                // Do something with $hotel_amenity
         
            } else {
                $response['message'] = 'Employer not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }


    public function UpdateEmployer(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all();
      
        $employer_id = $requestData['employer_id'];
    
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($employer_id,'id'),
            ],

            'password' => 'required',
            // 'type' => 'required',
            // 'confirm_password' => 'required|same:password',
            'contact_no' => 'nullable', // Making 'contact_no' optional
        ];

        $validator = Validator::make($requestData, $rules);


        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
           
            $employer = User::where('id', $employer_id)
                ->whereIn('type', [1, 3, 4])
                ->first();


    
            if (!$employer) {
                return response()->json(['status' => false, 'message' => 'Employer not found']);
            }
    
            $employer->name = $requestData['name'];
            $employer->email = $requestData['email'];
            $employer->password = Hash::make($requestData['password']);

            if (isset($requestData['contact_no'])) {
                $employer->contact_no = $requestData['contact_no'];
            }
    
            $employer->save();
    
            if ($employer) {
                $response = response()->json(['status' => true, 'message' => 'Employer Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update Employer']);
            }
        }
    
        return $response;
    }

    public function DeleteEmployer(Request $request)
    {
    
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'employer_id' => 'required',
        ];
    
        // $requestData = $request->json()->all();
    
        // $validator = Validator::make($requestData, $rules);
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
        
    
        $employer_id = $request['employer_id'];
    
        // $employer = User::find($employer_id);
        $employer = User::where('id', $employer_id)
                ->whereIn('type', [1, 3, 4])
                ->first();


    
            if (!$employer) {
                return response()->json(['message' => 'Employer not found'], 404);
            }
    
            $employer->delete();
    
            return response()->json(['message' => 'Employer deleted successfully']);
    
        
        }
       
    }

}
