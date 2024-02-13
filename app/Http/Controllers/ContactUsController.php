<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\NewsCategoryModel;

use App\Models\User;
use App\Models\ContactUsmodel;
use App\Models\HotelSpecialOfferModel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;

class ContactUsController extends Controller
{
    public function add_contact_us(Request $request)
    {
        $response = array("status" => false, 'message' => '');

            $rules = [
                'name' => 'required',
                'email' => 'required',
                'description' => 'required'
            ];

            

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);
           
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

                $ContactUsmodel = new ContactUsModel();
    
               
                $ContactUsmodel->full_name = $request->input('name');
                $ContactUsmodel->email = $request->input('email');
                $ContactUsmodel->description = $request->input('description');
               
                $ContactUsmodel->save();
    
                $subject = 'Contact Us';
               
                $body = "User Details!\nName: {$request->input('name')}\nEmail: {$request->input('email')}";
    
                // Email data
                // $to = $request->input('email');
                $to = "info@LuxuryHotelsMagazines.com";
                $key=9;

                $data = [
                    'name' => $request->input('name'),
                    'email' => $to,
                    'key' => $key
                ];

               
                // Use the helper function to send the email
                $helpers = new Helpers();
                $result = $helpers->sendEmail($to, $subject, $body, $key, $data);
  
                if ($result) {
                    $response = response()->json(['status' => true, 'message' => 'Contact Us Created Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Failed to create Contact Us']);
                }
             
            }

            return $response;
        }
    }

    
