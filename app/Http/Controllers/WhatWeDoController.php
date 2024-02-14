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


public function edit_what_we_do(Request $request)
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
        
        $home_info = WhatWeDoModel::where('type', $home_info_type)->first();
    

        if ($home_info) {
            $home_info_detail = json_decode($home_info['details'], true);

            if ($home_info['type'] == 1) {
                $home_info_detail = json_decode($home_info['details'], true);
               
                // Create an array to store details
                $details = [];
            
                // Extract background_image from $home_info_detail
                $background_image = asset("storage/app/" . $home_info_detail['background_image']);
                $background_content = $home_info_detail['background_content'];
              
            
                // Loop through sections and populate $details array
                foreach ($home_info_detail['sections'] as $detail) {
                    $details[] = [
                        'image' => asset('storage/app/' . $detail['image']),
                        'title' => $detail['title'],
                        'sub_title' => $detail['sub_title'],
                    ];
                }
            
                // Add background_image to $details array
                $home_info_detail['background_image'] = $background_image;
                $home_info_detail['background_content'] = $background_content;
                $home_info_detail['sections'] = array_values($details);
            
                // Update $home_info['details'] with the modified $details array
                $home_info['details'] = $home_info_detail;
            }

           
            if($home_info['type']==2){
                // $home_info_detail = json_decode($home_info['details'], true);
                // dd($home_info_detail);

                $home_info_detail['background_image'] = asset("storage/app/".$home_info_detail['background_image']);
                $home_info['details'] = $home_info_detail;  
                
         
            }
            if ($home_info['type'] == 3) {
                $home_info_detail = json_decode($home_info['details'], true);
               
                // Create an array to store details
                $details = [];
            
                // Extract background_image from $home_info_detail
                // $background_image = asset("storage/app/" . $home_info_detail['background_image']);
                $background_content = $home_info_detail['background_content'];
              
            
                // Loop through sections and populate $details array
                foreach ($home_info_detail['sections'] as $detail) {
                    $details[] = [
                        'image' => asset('storage/app/' . $detail['image']),
                        'title' => $detail['title'],
                        'content' => $detail['content'],
                    ];
                }
            
                // Add background_image to $details array
                // $home_info_detail['background_image'] = $background_image;
                $home_info_detail['background_content'] = $background_content;
                $home_info_detail['sections'] = array_values($details);
            
                // Update $home_info['details'] with the modified $details array
                $home_info['details'] = $home_info_detail;
            }
            if ($home_info['type'] == 4) {
                $home_info_detail = json_decode($home_info['details'], true);
               
                // Create an array to store details
                $details = [];
            
                // Extract background_image from $home_info_detail
                // $background_image = asset("storage/app/" . $home_info_detail['background_image']);
                $background_content = $home_info_detail['background_content'];
              
            
                // Loop through sections and populate $details array
                foreach ($home_info_detail['sections'] as $detail) {
                    $details[] = [
                        'image' => asset('storage/app/' . $detail['image']),
                        'title' => $detail['title'],
                        'content' => $detail['content'],
                    ];
                }
            
                // Add background_image to $details array
                // $home_info_detail['background_image'] = $background_image;
                $home_info_detail['background_content'] = $background_content;
                $home_info_detail['sections'] = array_values($details);
            
                // Update $home_info['details'] with the modified $details array
                $home_info['details'] = $home_info_detail;
            }

            if ($home_info['type'] == 5) {
                $home_info_detail = json_decode($home_info['details'], true);
               
                // Create an array to store details
                $details = [];
            
                // Extract background_image from $home_info_detail
                // $background_image = asset("storage/app/" . $home_info_detail['background_image']);
                $background_content = $home_info_detail['background_content'];
              
            
                // Loop through sections and populate $details array
                foreach ($home_info_detail['sections'] as $detail) {
                    $details[] = [
                        'image' => asset('storage/app/' . $detail['image']),
                        'title' => $detail['title'],
                        'content' => $detail['content'],
                    ];
                }
            
                // Add background_image to $details array
                // $home_info_detail['background_image'] = $background_image;
                $home_info_detail['background_content'] = $background_content;
                $home_info_detail['sections'] = array_values($details);
            
                // Update $home_info['details'] with the modified $details array
                $home_info['details'] = $home_info_detail;
            }
            if($home_info['type']==6){
            

                $home_info_detail['background_image'] = asset("storage/app/".$home_info_detail['background_image']);
                $home_info_detail['overlay_image'] = asset("storage/app/".$home_info_detail['overlay_image']);
                $home_info['details'] = $home_info_detail;  
                
         
            }
            

            
            $data = $home_info;
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us Data Found', 'data' => $data]);
    } else {
        $response = response()->json(['status' => false, 'message' => 'Data Not Found','data'=>$data]);
    }
}

  return $response;
}

public function delete_what_we_do(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'type' => 'required',
        'key' => 'required',
        
    ];

     $requestData = $request->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

    $home_info_type = $requestData['type'];
    
    $key = $requestData['key'];


    $home_info = WhatWeDoModel::where('type',$home_info_type)->first();
   
        if (!$home_info) {
            return response()->json(['message' => 'Home Info not found'], 404);
        }
        


        if ($home_info['type'] == 1 || $home_info['type'] == 3 || $home_info['type'] == 4)  {
        $home_detail = json_decode($home_info['details'], true);
    
        // Get all sections data
        $sections = $home_detail['sections'];
    
        if (array_key_exists($key, $sections)) {
            // Remove the section using the provided key
            unset($sections[$key]);
    
            // Reindex array keys
            $sections = array_values($sections);
    
            // Update detailsArray with the modified sections
            $detailsArray = json_decode($home_info->details, true);
            $detailsArray['sections'] = $sections;
    
            $home_info->details = json_encode($detailsArray);
            $home_info->save();
    
            $response['status'] = true;
            $response['message'] = 'Advertising With Us deleted successfully.';
        } else {
            $response['message'] = 'Invalid key provided.';
        }
    }

}
return response()->json($response);

}


// public function update_what_we_do(Request $request)
// {
//     $response = array("status" => false, 'message' => '');

//     $rules = [
//         'type' => 'required',
//         'key' => 'required',
//     ];

//     $requestData = $request->all();
//     $validator = Validator::make($requestData, $rules);

//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//     } else {

//         $home_info_type = $requestData['type'];
//     $key = $requestData['key'];

//     $home_info = WhatWeDoModel::where('type', $home_info_type)->first();

//         if ($home_info) {
//             if ($home_info['type'] == 3 || $home_info['type'] == 4)  {
//             $home_detail = json_decode($home_info['details'], true);
//             // dd($home_detail['sections']);

         

//             $digitalCampaignDetails = [];

//             if (array_key_exists($key, $home_detail['sections'])) {
//              // Handle image update logic here
//              if ($request->hasFile('image')) {
//                  $image = $request->file('image');
              
//                  $imagePath = $image->store('uploads');
 
//                  $digitalCampaignDetails[] = [
//                      "image" => $imagePath,
//                      "title" => $request->input('title')[$key],
//                      "content" => $request->input('content')[$key]
//                  ];
//                  // dd($digitalCampaignDetails);
     
                 
//          }

//          $details->sections = $digitalCampaignDetails;
    
//             // Append new details to existing details
//             //  $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
            
//             // Update home info
//             $home_info->details = json_encode($details);

             
             
//                 // $home_info['details'] = json_encode($dtt);
//                 $home_info->save();

//                 $response['status'] = true;
//                 $response['message'] = 'Hotel Image Updated successfully.';
            
//         } else {
//             $response['message'] = 'Invalid key provided.';
//         }
      

//     }
    
// }
// return response()->json($response);
   
// }

// }


public function update_what_we_do(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'type' => 'required',
        'key' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image
        // Add more validation rules as needed
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_info_type = $requestData['type'];
        $key = $requestData['key'];

        $home_info = WhatWeDoModel::where('type', $home_info_type)->first();

        if ($home_info) {
            $home_detail = json_decode($home_info['details'], true);

            if (($home_info['type'] == 3 || $home_info['type'] == 4) && isset($home_detail['sections'][$key])) {

                $section = $home_detail['sections'][$key];

                // Handle image update logic here
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imagePath = $image->store('uploads');
                    $section["image"] = $imagePath;
                }

                // Update title and content
                $section["title"] = $request->input('title');
                $section["content"] = $request->input('content');

                // Update the section in home_detail
                $home_detail['sections'][$key] = $section;

                // Update home_info with updated details
                $home_info->details = json_encode($home_detail);
                $home_info->save();

                $response['status'] = true;
                $response['message'] = 'Data updated successfully.';
            } else {
                $response['message'] = 'Invalid key provided.';
            }
        } else {
            $response['message'] = 'Home info not found.';
        }
    }

    return response()->json($response);
}


   

// public function update_what_we_do(Request $request)
// {
//     $response = ["status" => false, "message" => ""];
    
//     $rules = [
//         'type' => 'required',
//         'key' => 'required',
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//         return response()->json($response);
//     }

//     $requestData = $request->all();
//     $home_info_type = $requestData['type'];
//     $key = $requestData['key'];

//     $home_info = WhatWeDoModel::where('type', $home_info_type)->first();
//     //   dd($home_info);
   
//     if($home_info){
//         if ($home_info['type'] == 3 || $home_info['type'] == 4)  {
//         $home_detail = json_decode($home_info['details'], true);
//         //  dd($home_detail);
       
//         //  $dtt = $home_detail['sections'];
    

//         $digitalCampaignDetails = [];

//            if (array_key_exists($key, $home_detail)) {
//             // Handle image update logic here
//             if ($request->hasFile('image')) {
//                 $image = $request->file('image');
             
//                 $imagePath = $image->store('uploads');

//                 $digitalCampaignDetails[] = [
//                     "image" => $imagePath,
//                     "title" => $request->input('title')[$key],
//                     "content" => $request->input('content')[$key]
//                 ];
//                 // dd($digitalCampaignDetails);
    
                
//         }
    
     
//             // Decode existing details from JSON
//             // $detailsArray = json_decode($home_info->details, true);
//             // dd($digitalCampaignDetails);
    
//             // $detailsArray['sections'][] = $digitalCampaignDetails[];
//             // $details['sections'][] = $digitalCampaignDetails;
//             $details->sections = $digitalCampaignDetails;
    
//             // Append new details to existing details
//             //  $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
            
//             // Update home info
//             $home_info->details = json_encode($details);

             
             
//                 // $home_info['details'] = json_encode($dtt);
//                 $home_info->save();

//                 $response['status'] = true;
//                 $response['message'] = 'Hotel Image Updated successfully.';
            
//         } else {
//             $response['message'] = 'Invalid key provided.';
//         }
      

//     }
    
// }
// return response()->json($response);
   
// }

}