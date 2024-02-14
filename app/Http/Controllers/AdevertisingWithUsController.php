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
use App\Models\AdevertiseWithUsModel;

class AdevertisingWithUsController extends Controller
{
    public function add_advertise_with_us(Request $request)
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
    
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        $home_info = AdevertiseWithUsModel::where('type', $requestData['type'])->first();
        

   
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
                "title" => $request->input('title')[$key]
            ];
            // dd($digitalCampaignDetails);

            
    }

    if ($home_info) {
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
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us Updated Successfully']);
    }


    else {
        // Create new home info if not exists
        $home_info = new AdevertiseWithUsModel();
        $home_info->type = $request->input('type');
        $home_info->details = json_encode($digitalCampaignDetails);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us Added Successfully']);
    }
}

}
else if ($hotel_type == 2) 
{
            $rules = [
                'type' => 'required',
                'background_image' => 'required',
                'title' => 'required',
                'content' => 'required',
                
            ];

            $requestData = $request->all();
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {

                           
                    $homevideoDetails = [
                       
                        'title' => $requestData['title'],
                        'content' => $requestData['content'],
                        
                    ];
                    if ($request->hasFile('background_image')) {
                        $homevideoDetails['background_image'] = $request->file('background_image')->store('uploads');
                    }
                    $home_info = AdevertiseWithUsModel::where('type',$requestData['type'])->first();
                    // dd($home_info);


                        $home_info->details = $homevideoDetails;
                        $home_info->save();
                        if ($home_info) {
                        $response = response()->json(['status' => true, 'message' => 'Advertise With Us Updated Successfully']);
                    } else {
                        $response = response()->json(['status' => false, 'message' => 'Failed to add Advertise With Us!']);
                    }
                      
                    
                    }

}

else if ($hotel_type == 3) 
{

$rules = [
    // 'title' => 'required',
    // 'image' => 'required|image', // Add appropriate validation for image uploads
    // 'country' => 'required',
    // 'you_tube_link' => 'required',
    // 'content' => 'required',
    'background_title' => 'required',
    'background_sub_title' => 'required',
    'background_image' => 'required',
    'sections' => 'required',
];

$requestData = $request->all();
$validator = Validator::make($requestData, $rules);

if ($validator->fails()) {
    $response['status'] = false;
    $response['message'] = $validator->messages();
} else {


    $home_info = AdevertiseWithUsModel::where('type', $request->input('type'))->first();
    // dd($home_info);
    if ($home_info) {

        $details = json_decode($home_info->details);
        //   dd($details);
        $detail_dt = $details->sections;
        $sections = $request->sections;
        $digitalCampaignDetails = [];
        if(count($sections) >= count($detail_dt)){
            foreach($sections as $key=>$val){
                // dd($val);
                if (!isset($val['image'])) {
                    if(isset($detail_dt[$key]->image)){
                        $filename = $detail_dt[$key]->image;
                    }
                }else{
                    $image = $request->file('sections')[$key]['image'];
                    $filename = $image->store('uploads');
                }


                $digitalCampaignDetails[] = [
                    "title" => $sections[$key]['title'],
                    "image" => isset($filename) && !empty($filename)?$filename:'',
                    "country" => $sections[$key]['country'],
                    "you_tube_link" => $sections[$key]['you_tube_link'],
                    "content" => $sections[$key]['content'],
                ];
            }
        }
 

        $home_info->type = $requestData['type'];

        $detailtmp['background_title'] =  $requestData['background_title'];
        $detailtmp['background_sub_title'] = $requestData['background_sub_title'];
        if ($request->hasFile('background_image') && $request->file('background_image')->isValid()) {
            $detailtmp['background_image'] = $request->file('background_image')->store('uploads');
        }
        $detailtmp['sections'] = array_values($digitalCampaignDetails);
        $home_info->details = json_encode($detailtmp);


    $home_info->save();

  
            $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
        }
                
           
        }

    }
else if ($hotel_type == 4)
    {

    $rules = [
        'type' => 'required',
        'background_image' => 'required',
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
        $home_info = AdevertiseWithUsModel::where('type', $requestData['type'])->first();
        

   
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

    if ($home_info) {
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
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us Updated Successfully']);
    }


    else {
        // Create new home info if not exists
        $home_info = new AdevertiseWithUsModel();
        $home_info->type = $request->input('type');
        $home_info->details = json_encode($digitalCampaignDetails);
        $home_info->save();
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us Added Successfully']);
    }
}

}




// Check if hotel_type is set, you can change it as per your requirement
else if ($hotel_type == 5) {
    // Validation rules
    $rules = [
        'type' => 'required',
        'background_image' => 'required',
        'background_title' => 'required',
        'background_content' => 'required',
        'section_title' => 'required|array', // Ensure section_title is an array
        'section_number' => 'required|array', // Ensure section_number is an array
        'title' => 'required',
        // 'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image file
    ];

    // Validate request data
    $requestData =  $request->all();
    //  dd($requestData);

    

    $validator = Validator::make($request->all(), $rules);
    // dd($request);

    if ($validator->fails()) {
        $response['status'] = false;
        $response['message'] = $validator->messages();
    } else {
        // Retrieve or create a new AdevertiseWithUsModel instance
        $home_info = AdevertiseWithUsModel::where('type',$requestData['type'])->first();
        //   dd($home_info);


        // Initialize empty array to store section details
        $sections = [];

        // dd($requestData['section_title']);
        // Loop through section titles and numbers
        foreach ($requestData['section_title'] as $key => $title) {
            $sections[] = [
                'section_title' => $title,
                'section_number' => $requestData['section_number'][$key],
            ];
        }

        // Initialize empty array to store image details
        $images = [];

        // dd($request->hasFile('image'));
        // Check if images were uploaded
        if ($request->hasFile('image')) {
            // Loop through uploaded images
            foreach ($request->file('image') as $image) {
                // Store the image in the storage/uploads directory
                $filename = $image->store('uploads');
                // Add the image path to the images array
                $images[] = [
                    "image_path" => $filename,
                ];
            }
        }
        
        // Construct data array
        $data = [
            'title' => $requestData['title'],
            'images' => $images,
        ];
        //  dd($data);
        
        // Construct details array
        $details = [
            'background_image' => $request->file('background_image')->store('uploads'),
            'background_title' => $requestData['background_title'],
            'background_content' => $requestData['background_content'],
            'sections' => $sections,
            'data' => $data,
        ];
        //  dd($details);
        

        // Update home info
        $home_info->details = json_encode($details);
        $home_info->save();

        // Prepare response
        $response = response()->json(['status' => true, 'message' => 'Advertising With Us ' . ($home_info->wasRecentlyCreated ? 'Added' : 'Updated') . ' Successfully']);
    }
}


return $response;

    
}


    


    public function edit_advertise_with_us(Request $request)
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
            
            $home_info = AdevertiseWithUsModel::where('type', $home_info_type)->first();
        
    
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
                    $background_image = asset("storage/app/" . $home_info_detail['background_image']);
                    $background_title = $home_info_detail['background_title'];
                    $background_sub_title = $home_info_detail['background_sub_title'];
                
                    // Loop through sections and populate $details array
                    foreach ($home_info_detail['sections'] as $detail) {
                        $details[] = [
                            'image' => asset('storage/app/' . $detail['image']),
                            'title' => $detail['title'],
                            'country' => $detail['country'],
                            'you_tube_link' => $detail['you_tube_link'],
                            'content' => $detail['content'],
                        ];
                    }
                
                    // Add background_image to $details array
                    $home_info_detail['background_image'] = $background_image;
                    $home_info_detail['background_title'] = $background_title;
                    $home_info_detail['background_sub_title'] = $background_sub_title;
                    $home_info_detail['sections'] = array_values($details);
                
                    // Update $home_info['details'] with the modified $details array
                    $home_info['details'] = $home_info_detail;
                }

                if ($home_info['type'] == 4) {
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
                            'content' => $detail['content'],
                        ];
                    }
                
                    // Add background_image to $details array
                    $home_info_detail['background_image'] = $background_image;
                    $home_info_detail['background_content'] = $background_content;
                    $home_info_detail['sections'] = array_values($details);
                
                    // Update $home_info['details'] with the modified $details array
                    $home_info['details'] = $home_info_detail;
                }
              
              
                if ($home_info['type'] == 5) {
                    $home_info_detail = json_decode($home_info['details'], true);
                    
                    // Extract the base URL for the images
                    $base_url = asset("storage/app/");
                    
                    // Loop through images and append the base URL to image_path
                    foreach ($home_info_detail['data']['images'] as &$image) {
                        $image['image_path'] = $base_url . $image['image_path'];
                    }
                
                    // Update the background_image with the base URL
                    $home_info_detail['background_image'] = $base_url . $home_info_detail['background_image'];
                
                    // Reconstruct the sections array with modified details
                    $details = [];
                    foreach ($home_info_detail['sections'] as $detail) {
                        $details[] = [
                            'section_title' => $detail['section_title'],
                            'section_number' => $detail['section_number'],
                        ];
                    }
                    $home_info_detail['sections'] = $details;
                
                    // Update $home_info['details'] with the modified $home_info_detail
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

public function delete_advertise_with_us(Request $request)
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


    $home_info = AdevertiseWithUsModel::where('type',$home_info_type)->first();
   
        if (!$home_info) {
            return response()->json(['message' => 'Home Info not found'], 404);
        }
        

    if ($home_info['type'] == 1) {
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
    
    else if ($home_info['type'] == 4) {
        $home_detail = json_decode($home_info['details'], true);
        // dd($home_detail);

    
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


public function update_advertise_with_us(Request $request)
{
    $response = ["status" => false, "message" => ""];
    
    $rules = [
        'type' => 'required',
        'key' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
        return response()->json($response);
    }

    $requestData = $request->all();
    $home_info_type = $requestData['type'];
    $key = $requestData['key'];

    $home_info = AdevertiseWithUsModel::where('type', $home_info_type)->first();

    if (!$home_info) {
        $response['message'] = 'Invalid type provided.';
        return response()->json($response);
    }

    $home_detail = json_decode($home_info['details'], true);

    if ($home_info['type'] == 4 && isset($home_detail['sections'][$key])) {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads');
            $home_detail['sections'][$key]['image'] = $imagePath;
        }

        if ($request->filled('title')) {
            $home_detail['sections'][$key]['title'] = $requestData['title'];
        }

        if ($request->filled('content')) {
            $home_detail['sections'][$key]['content'] = $requestData['content'];
        }

        $home_info['details'] = json_encode($home_detail);
        $home_info->save();

        $response['status'] = true;
        $response['message'] = 'Advertising With Us Updated successfully.';
    } else {
        $response['message'] = 'Invalid type or key provided.';
    }

    return response()->json($response);
}

}