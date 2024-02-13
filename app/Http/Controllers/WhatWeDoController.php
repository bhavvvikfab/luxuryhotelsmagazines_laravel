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
use App\Models\MediaKitModel;
use App\Models\DistributionModel;
use App\Models\DistributionDataModel;
use App\Models\DistributionDetailsModel;
use App\Models\BannerModel;
use App\Models\QueriesModel;
use App\Models\TeamModel;
use App\Models\VotedHotelModel;
use App\Models\PropertiesModel;
use App\Models\PackagePriceModel;
use App\Models\PackageModel;
use App\Models\WhatWeDoModel;

class WhatWeDoController extends Controller
{
    public function add_what_we_do(Request $request)
    {
        $response = array("status" => false, 'message' => '');

        $hotel_type = $request->input('type');

if ($hotel_type == 1)
    {

    $rules = [
        'type' => 'required',
        'background_image' => 'required',
        'background_content' => 'required',
        // 'image' => 'required',
        'title' => 'required',
        'sub_title' => 'required',
    
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        $home_info = WhatWeDoModel::where('type', $requestData['type'])->first();
        if($home_info) {
            $details = json_decode($home_info->details);
   
    $detail_dt = $details->sections;
    // dd($detail_dt);
    // print_r($_FILES['image']['name']);


    $details_image =array_column($detail_dt,'image');
    // dd($details_image);


    // Initialize empty array to store image and link details
    $digitalCampaignDetails = [];

    // dd();
    // Loop through uploaded images and links
    foreach ($_FILES['image']['name'] as $key => $image) {
        // dd($image);

        if ($image == null) {
            // echo "trg";

            $digitalCampaignDetailstmp=[];
            if(isset($details_image[$key])){
                $digitalCampaignDetailstmp["image"] = $details_image[$key];
            }
            $digitalCampaignDetailstmp["title"] = $request->input('title')[$key];
            $digitalCampaignDetailstmp["sub_title"] = $request->input('sub_title')[$key];
            $digitalCampaignDetails[] = $digitalCampaignDetailstmp;
            continue;
        }

        $image = $request->file('image')[$key];
        //   dd($image);
            $filename = $image->store('uploads'); // Store the image in the storage/images directory
            $digitalCampaignDetails[] = [
                "image" => $filename,
                "title" => $request->input('title')[$key],
                "sub_title" => $request->input('sub_title')[$key]
            ];
            // dd($digitalCampaignDetails);

            
    }
  
        // Decode existing details from JSON
        // $detailsArray = json_decode($home_info->details, true);
        // dd($digitalCampaignDetails);

        // $detailsArray['sections'][] = $digitalCampaignDetails[];
        // $details['sections'][] = $digitalCampaignDetails;
        $details->sections = $digitalCampaignDetails;

        // Append new details to existing details
        //  $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
        
        // Update home info
        $home_info->details = json_encode($details);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully!']);
  

        }
        else{
            $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
        }
        
}

}
else if ($hotel_type == 2) 
{
            $rules = [
                'type' => 'required',
                'background_image' => 'required',
                'title' => 'required',
               
            ];

            $requestData = $request->all();
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {

                           
                    $homevideoDetails = [
                       
                        'title' => $requestData['title'],
                    
                        
                    ];
                    if ($request->hasFile('background_image')) {
                        $homevideoDetails['background_image'] = $request->file('background_image')->store('uploads');
                    }
                    $home_info = WhatWeDoModel::where('type',$requestData['type'])->first();
                    // dd($home_info);
                    if($home_info) {
                        $home_info->details = $homevideoDetails;
                                            $home_info->save();
                                            $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully']);
                                        }
                                        else {
                                            $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
                                        }
                                    }
                                    
}

else if ($hotel_type == 3)
    {
        // dd('rtghrft');

    $rules = [
        'type' => 'required',
        'background_content' => 'required',
        // 'image' => 'required',
        'title' => 'required',
        'content' => 'required',
    
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        $home_info = WhatWeDoModel::where('type', $requestData['type'])->first();
     
        if ($home_info) {
        
    $details = json_decode($home_info->details);
   
    $detail_dt = $details->sections;
    // dd($detail_dt);
    // print_r($_FILES['image']['name']);


    $details_image =array_column($detail_dt,'image');
    // dd($details_image);


    // Initialize empty array to store image and link details
    $digitalCampaignDetails = [];

    // dd();
    // Loop through uploaded images and links
    foreach ($_FILES['image']['name'] as $key => $image) {
        // dd($image);

        if ($image == null) {
            // echo "trg";

            $digitalCampaignDetailstmp=[];
            if(isset($details_image[$key])){
                $digitalCampaignDetailstmp["image"] = $details_image[$key];
            }
            $digitalCampaignDetailstmp["title"] = $request->input('title')[$key];
            $digitalCampaignDetails[] = $digitalCampaignDetailstmp;
            continue;
        }

        $image = $request->file('image')[$key];
        //   dd($image);
            $filename = $image->store('uploads'); // Store the image in the storage/images directory
            $digitalCampaignDetails[] = [
                "image" => $filename,
                "title" => $request->input('title')[$key],
                "content" => $request->input('content')[$key]
            ];
            // dd($digitalCampaignDetails);

            
    }

 
        // Decode existing details from JSON
        // $detailsArray = json_decode($home_info->details, true);
        // dd($digitalCampaignDetails);

        // $detailsArray['sections'][] = $digitalCampaignDetails[];
        // $details['sections'][] = $digitalCampaignDetails;
        $details->sections = $digitalCampaignDetails;

        // Append new details to existing details
        //  $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
        
        // Update home info
        $home_info->details = json_encode($details);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully']);
    }
    else {
        $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
    }
}

}


else if ($hotel_type == 4)
    {

    $rules = [
        'type' => 'required',
        'background_content' => 'required',
        // 'image' => 'required',
        'title' => 'required',
        'content' => 'required',
    
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        $home_info = WhatWeDoModel::where('type', $requestData['type'])->first();
        if ($home_info) {
        

   
    $details = json_decode($home_info->details);
   
    $detail_dt = $details->sections;
    // dd($detail_dt);
    // print_r($_FILES['image']['name']);


    $details_image =array_column($detail_dt,'image');
    // dd($details_image);


    // Initialize empty array to store image and link details
    $digitalCampaignDetails = [];

    // dd();
    // Loop through uploaded images and links
    foreach ($_FILES['image']['name'] as $key => $image) {
        // dd($image);

        if ($image == null) {
            // echo "trg";

            $digitalCampaignDetailstmp=[];
            if(isset($details_image[$key])){
                $digitalCampaignDetailstmp["image"] = $details_image[$key];
            }
            $digitalCampaignDetailstmp["title"] = $request->input('title')[$key];
            $digitalCampaignDetails[] = $digitalCampaignDetailstmp;
            continue;
        }

        $image = $request->file('image')[$key];
        //   dd($image);
            $filename = $image->store('uploads'); // Store the image in the storage/images directory
            $digitalCampaignDetails[] = [
                "image" => $filename,
                "title" => $request->input('title')[$key],
                "content" => $request->input('content')[$key]
            ];
            // dd($digitalCampaignDetails);

            
    }

 
        // Decode existing details from JSON
        // $detailsArray = json_decode($home_info->details, true);
        // dd($digitalCampaignDetails);

        // $detailsArray['sections'][] = $digitalCampaignDetails[];
        // $details['sections'][] = $digitalCampaignDetails;
        $details->sections = $digitalCampaignDetails;

        // Append new details to existing details
        //  $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
        
        // Update home info
        $home_info->details = json_encode($details);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully']);
    }
    else {
        $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
    }
}

}



else if ($hotel_type == 5)
    {

    $rules = [
        'type' => 'required',
        'background_content' => 'required',
        'background_image' => 'required',
        // 'image' => 'required',
        'title' => 'required',
        'content' => 'required',
    
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        $home_info = WhatWeDoModel::where('type', $requestData['type'])->first();
    
        if ($home_info) {
    $details = json_decode($home_info->details);
    $detail_dt = $details->sections;
   
    $details_image =array_column($detail_dt,'image');
    // Initialize empty array to store image and link details
    $digitalCampaignDetails = [];

    // dd();
    // Loop through uploaded images and links
    foreach ($_FILES['image']['name'] as $key => $image) {
        // dd($image);

        if ($image == null) {
            // echo "trg";

            $digitalCampaignDetailstmp=[];
            if(isset($details_image[$key])){
                $digitalCampaignDetailstmp["image"] = $details_image[$key];
            }
            $digitalCampaignDetailstmp["title"] = $request->input('title')[$key];
            $digitalCampaignDetails[] = $digitalCampaignDetailstmp;
            continue;
        }

        $image = $request->file('image')[$key];
        //   dd($image);
            $filename = $image->store('uploads'); // Store the image in the storage/images directory
            $digitalCampaignDetails[] = [
                "image" => $filename,
                "title" => $request->input('title')[$key],
                "content" => $request->input('content')[$key]
            ];
            // dd($digitalCampaignDetails);

    }

        $details->sections = $digitalCampaignDetails;
        // Update home info
        $home_info->details = json_encode($details);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully']);
    }
    else {
        $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
    }
}

}

else if ($hotel_type == 6) 
{
            $rules = [
                'type' => 'required',
                'background_image' => 'required',
                'background_title' => 'required',
                'overlay_image' => 'required',
                'overlay_content' => 'required',
               
            ];

            $requestData = $request->all();
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {

                    $homevideoDetails = [
                        'background_title' => $requestData['background_title'],
                        'overlay_content' => $requestData['overlay_content'],
                    ];

                    if ($request->hasFile('background_image')) {
                        $homevideoDetails['background_image'] = $request->file('background_image')->store('uploads');
                    }
                    if ($request->hasFile('overlay_image')) {
                        $homevideoDetails['overlay_image'] = $request->file('overlay_image')->store('uploads');
                    }
                    $home_info = WhatWeDoModel::where('type',$requestData['type'])->first();
                    // dd($home_info);
                    if($home_info) {
                        $home_info->details = $homevideoDetails;
                                            $home_info->save();
                                            $response = response()->json(['status' => true, 'message' => 'What We Do Updated Successfully']);
                                        }
                                        else {
                                            $response = response()->json(['status' => false, 'message' => 'What We Do not found!']);
                                        }
                                    }
                                    
}



return $response;

    
}
}
