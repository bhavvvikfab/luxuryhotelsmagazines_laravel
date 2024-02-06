<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\HotelModel;
use App\Models\HotelContactsModel;
use App\Models\HotelSpecialOfferModel;
use App\Models\HotelPageAddonModel;
use App\Models\HotelAmetiesModel;
use App\Models\MagazinesModel;
use App\Models\News;
use App\Models\SubscribersModel;
use App\Models\HomeInfoModel;
use App\Models\AllpagedetailsModel;
use App\Models\HotelCreateProfileModel;

class HotelCreateProfileController extends Controller
{
   
    public function add_hotel_create_profile(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $hotel_type = $request->input('type');

if ($hotel_type == 1) {

    $rules = [
        'type' => 'required',
        'background_image' => 'required',
        'content' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $requestData = $request->all();

        $homeaboutDetails = [
   
            'content' => $requestData['content']
        ];

        if ($request->hasFile('background_image')) {
            $homeaboutDetails['background_image'] = $request->file('background_image')->store('uploads');
        } 

        $home_info = HotelCreateProfileModel::where('type', $requestData['type'])->first();



        if ($home_info) {
        $home_info->details = $homeaboutDetails;
        $home_info->save();

      
            $response = response()->json(['status' => true, 'message' => 'Hotel Created Profile Updated Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to updated hotel created profile!']);
        }
    }
}

else if ($hotel_type == 2)
 {
    
    $rules = [
        'type' => 'required',
        'number' => 'required|numeric',
        'number_sufix' => 'required|numeric',
        'text' => 'required',
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_info = HotelCreateProfileModel::where('type', $requestData['type'])->first();

        $digitalCampaignDetails = [
            'number' => $requestData['number'],
            'number_sufix' => $requestData['number_sufix'],
            'text' => $requestData['text'],
        ];
        

        if ($home_info) {
            $detailsArray = json_decode($home_info->details, true);

        //  $detailsArray = $homeaboutDetails;
        $detailsArray[] = $digitalCampaignDetails;

            $home_info->details = json_encode(($detailsArray));
            // dd($home_info->details);

            $home_info->save();
            $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
        } else {
            // If the record doesn't exist, create a new one
            HotelCreateProfileModel::create([
                'type' => $requestData['type'],
                'details' => json_encode($digitalCampaignDetails),
            ]);

            $response = response()->json(['status' => true, 'message' => 'New Home Info Created Successfully']);
        }
    }
}

else if ($hotel_type == 3) 
{

$rules = [
    'title' => 'required',
    'content' => 'required',
];

$requestData = $request->all();
$validator = Validator::make($requestData, $rules);

if ($validator->fails()) {
    $response['status'] = false;
    $response['message'] = $validator->messages();
} else {

    $home_info = HotelCreateProfileModel::where('type', $requestData['type'])->first();
    // dd($homeInfo);

    $detail = $home_info['details'];
    $bg_data = json_decode($detail);

    $digitalCampaignDetails = [
        'title' => $requestData['title'],
        'content' => $requestData['content'],
    ];

    if($home_info){
        $detailsArray = json_decode($home_info->details, true);

        //  $detailsArray = $homeaboutDetails;
        $detailsArray[] = $digitalCampaignDetails;

            $home_info->details = json_encode(($detailsArray));
            // dd($home_info->details);

            $home_info->save();
            $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
        } else {
            // If the record doesn't exist, create a new one
            HotelCreateProfileModel::create([
                'type' => $requestData['type'],
                'details' => json_encode($digitalCampaignDetails),
            ]);

            $response = response()->json(['status' => true, 'message' => 'New Home Info Created Successfully']);
        }
    }
}
   

    return $response;
    
}

public function edit_hotel_create_profile(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'type' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_info_type = $request->input('type');
        // $home_info_background_type = $request->input('background_type');
        $data = [];
        
        $home_info = HotelCreateProfileModel::where('type', $home_info_type)->first();
       

    

        if ($home_info) {
            $home_info_detail = json_decode($home_info['details'], true);
            // dd($home_info_detail);
      


            if($home_info['type']==1){
                    $home_info_detail['background_image'] = asset("storage/app/".$home_info_detail['background_image']);
               

                $home_info['details'] = $home_info_detail;  
         
            }

            if($home_info['type']==2){

                $details = [];
            foreach ($home_info_detail as $detail) {
                // dd($detail);
                $details[] = [
                     'number' => $detail['number'],
                     'number_sufix' => $detail['number_sufix'],
                     'text' => $detail['text'],
                ];
            }
            
            $home_info['details'] = $details;
         
            }

            if($home_info['type']==3){
                // $home_info_detail['you_tybe_link'] = asset("storage/app/".$home_info_detail['you_tybe_link']);
                $home_info['details'] = $home_info_detail;  
                
         
            }
          
            
            $data = $home_info;
            $response = response()->json(['status' => true, 'message' => 'Home Info Data Found', 'data' => $data]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Data Not Found','data'=>$data]);
        }
    }

      return $response;
}

public function delete_hotel_create_profile(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'type' => 'required',
        'key' => 'required',
    ];
    $requestData = $request->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_info_type = $requestData['type'];
    
        $key = $requestData['key'];
    
    
        $home_info = HotelCreateProfileModel::where('type',$home_info_type)->first();
        // dd($home_info['type']);
        
    
    
            if (!$home_info) {
                return response()->json(['message' => 'Hotel Created Profile not found'], 404);
            }
            if ($home_info['type'] == 3) 
            {
               
    
            $home_detail = json_decode($home_info['details']);
            // dd($home_detail);
    
            if (array_key_exists($key, $home_detail)) {
                // Remove the file using the provided key
                unset($home_detail[$key]);
                // Reindex array keys
                $home_detail = array_values($home_detail);
    
                // Update the 'file_pdf' array in the database
                $home_info->details = json_encode($home_detail);
                $home_info->save();
    
                $response['status'] = true;
                $response['message'] = 'Hotel Created Profile deleted successfully.';
            } else {
                $response['message'] = 'Invalid key provided.';
            }
        }
    
    
    }
    return response()->json($response);
    
    
}
    




}
