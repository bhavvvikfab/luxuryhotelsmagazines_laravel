<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;

class EmployerController extends Controller
{
    public function AddEmployer(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'type' => 'required',
            'contact_no' => 'nullable', // Making 'contact_no' optional
        ];
        $requestData = $request->all();
      

        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
           

            $user = new User();
   
            $user->name = $requestData['name'];
            $user->email = $requestData['email'];
            $user->password = $requestData['password'];
            $user->type = $requestData['type'];
            if (isset($requestData['contact_no'])) {
                $user->contact_no = $requestData['contact_no'];
            }
     
            $user->save();
    
            if ($user) {
                $response =  response()->json(['status' => true, 'message' => 'Employer Added Successfully']);
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
