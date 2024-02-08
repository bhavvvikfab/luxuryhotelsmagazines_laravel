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






class HomeInfoController extends Controller
{
    

    public function single_page_details(Request $request)
    {
        // dd($request);
    
        $response = array("status" => false, 'message' => '');

    $type = $request->input('type');

    if ($type == "team") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
        'content' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'Team Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update team page data!']);
            }

    }
    
}
else if ($type == "package") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'Package Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update package page data!']);
            }

    }
    
}

else if ($type == "mediakit") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'Media kit Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to media kit update page data!']);
            }

    }
    
}
else if ($type == "news") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'News Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update news page data!']);
            }

    }
    
}

else if ($type == "hotel") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
        'content' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update hotel page data!']);
            }

    }
    
}

else if ($type == "hotelmagazine") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
        'title' => 'required',
        'content' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        

        $homeaboutDetails = [
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ];


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
            // dd($all_page_details);

            $all_page_details->details = $homeaboutDetails;
            $all_page_details->save();
                          
            if ($all_page_details) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Magazine Page Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update hotel magazine page data!']);
            }

    }
    
}

return $response;


    }

    public function get_single_page_details(Request $request)
    {
        // dd($request);
    
        $response = array("status" => false, 'message' => '');

    $type = $request->input('type');

    if ($type == "team") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {


            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
          

           
                          
            if ($all_page_details) {
                $all_page_detail = json_decode($all_page_details['details'], true);
                // dd($all_page_detail);

                $all_page_details['details'] = $all_page_detail;
      
                // $all_page_details->details = json_encode($all_page_detail);


                $response = response()->json(['status' => true, 'message' => 'Team Page Data get Successfully,','data'=> $all_page_details]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to get team page data!']);
            }

    }
    
}
else if ($type == "package") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

            $all_page_details = AllpagedetailsModel::where('type', $type)->first();
         
                          
            if ($all_page_details) {
                $all_page_detail = json_decode($all_page_details['details'], true);
                // dd($all_page_detail);

                $all_page_details['details'] = $all_page_detail;
      
                // $all_page_details->details = json_encode($all_page_detail);


                $response = response()->json(['status' => true, 'message' => 'Package Page Data get Successfully,','data'=> $all_page_details]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to get Package page data!']);
            }

    }
    
}

else if ($type == "mediakit") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $all_page_details = AllpagedetailsModel::where('type', $type)->first();
     
                      
        if ($all_page_details) {
            $all_page_detail = json_decode($all_page_details['details'], true);
            // dd($all_page_detail);

            $all_page_details['details'] = $all_page_detail;
  
            // $all_page_details->details = json_encode($all_page_detail);


            $response = response()->json(['status' => true, 'message' => 'Media kit Page Data get Successfully,','data'=> $all_page_details]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to get Media kit page data!']);
        }

}

}
else if ($type == "news") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $all_page_details = AllpagedetailsModel::where('type', $type)->first();
     
                      
        if ($all_page_details) {
            $all_page_detail = json_decode($all_page_details['details'], true);
            // dd($all_page_detail);

            $all_page_details['details'] = $all_page_detail;
  
            // $all_page_details->details = json_encode($all_page_detail);


            $response = response()->json(['status' => true, 'message' => 'News Page Data get Successfully,','data'=> $all_page_details]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to get News page data!']);
        }

}

}

else if ($type == "hotel") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $all_page_details = AllpagedetailsModel::where('type', $type)->first();
     
                      
        if ($all_page_details) {
            $all_page_detail = json_decode($all_page_details['details'], true);
            // dd($all_page_detail);

            $all_page_details['details'] = $all_page_detail;
  
            // $all_page_details->details = json_encode($all_page_detail);


            $response = response()->json(['status' => true, 'message' => 'Hotel Page Data get Successfully,','data'=> $all_page_details]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to get Hotel page data!']);
        }

}

}

else if ($type == "hotelmagazine") 
{
    // dd('hbfrhfrh');

    $rules = [
        'type' => 'required',
       
    ];

    $requestData =  $request->all();
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $all_page_details = AllpagedetailsModel::where('type', $type)->first();
     
                      
        if ($all_page_details) {
            $all_page_detail = json_decode($all_page_details['details'], true);
            // dd($all_page_detail);

            $all_page_details['details'] = $all_page_detail;
  
            // $all_page_details->details = json_encode($all_page_detail);


            $response = response()->json(['status' => true, 'message' => 'Hotel Magazine Page Data get Successfully,','data'=> $all_page_details]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to get Hotel Magazine page data!']);
        }

}

}

return $response;


    }


    public function add_home_info(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $hotel_type = $request->input('type');

if ($hotel_type == 1) 
{

    $rules = [
        'type' => 'required',
        'name' => 'required',
        'hotel_type' => 'required',
        'category' => 'required',
        'pos' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        
        

            $headerSliderDetails = [
                'name' => $request->input('name'),
                'hotel_type' => $request->input('hotel_type'),
                // 'category' => $request->file('category')->store('uploads'),
                'pos' => $request->input('pos'),
            ];

            if ($request->hasFile('category')) {
                $headerSliderDetails['category'] = $request->file('category')->store('uploads');
            }
            

            $home_info = HomeInfoModel::where('type', $request->input('type'))->first();

            if (!$home_info) {
                $home_info = new HomeInfoModel();
                $home_info->type = $request->input('type');
                $home_info->details = json_encode([$headerSliderDetails]);
            } else {
                $detailsArray = json_decode($home_info->details, true);
                $detailsArray[] = $headerSliderDetails;
                $home_info->details = json_encode($detailsArray);
            }

            $home_info->save();

            if ($home_info) {
                $response = response()->json(['status' => true, 'message' => 'Home Info Added Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
            }
    
    }
}

// else if ($hotel_type == 2) 
// {

//         $rules = [
//             // 'user_id' => 'required',
//             'type' => 'required',
//             'background_type' => 'required',
//             'background' => 'required',
//             'content' => 'required',
//             // 'overlay_image_1' => 'required',
//             // 'overlay_image_2' => 'required',
            
//         ];


//         $requestData = $request->all();


//         $validator = Validator::make($request->all(), $rules);

//         if ($validator->fails()) {
//                     $response['message'] = $validator->messages();
//                 } else {
                    
                   
//                     // if($hotel_type == 2)
//                     // {
                    

//                         if($requestData['background_type'] == 1){
                     

                      
                       
//                         $homeaboutDetails = [

//                             'background_type' => $requestData['background_type'],
//                             // 'background' => $request->file('background')->store('uploads'),
//                             'content' => $requestData['content'],
//                             // 'overlay_image_1' => $request->file('overlay_image_1')->store('uploads'),
//                             // 'overlay_image_2' => $request->file('overlay_image_2')->store('uploads'),
                            
//                         ];

//                         // dd($homeaboutDetails);

                  
//                         if ($request->hasFile('background')) {
//                             $homeaboutDetails['background'] = $request->file('background')->store('uploads');
//                         }
//                     }
//                     else if($requestData['background_type'] == 2){

//                         $homeaboutDetails = [

//                             'background_type' => $requestData['background_type'],
                           
//                             'content' => $requestData['content'],
//                            'background' => $requestData['background'],
//                             // 'overlay_image_1' => $request->file('overlay_image_1')->store('uploads'),
//                             // 'overlay_image_2' => $request->file('overlay_image_2')->store('uploads'),
                            
//                         ];

//                     }

//                         // if ($request->hasFile('overlay_image_1')) {
//                         //     $homeaboutDetails['overlay_image_1'] = $request->file('overlay_image_1')->store('uploads');
//                         // }

//                         // if ($request->hasFile('overlay_image_2')) {
//                         //     $homeaboutDetails['overlay_image_2'] = $request->file('overlay_image_2')->store('uploads');
//                         // }

                        
//                         // $home_info  = new HomeInfoModel();
//                         $home_info = HomeInfoModel::where('type',$requestData['type'])->first();
//                         // dd($home_info);


//                           $home_info->details = $homeaboutDetails;
//                         //    dd($homeaboutDetails);
//                           $home_info->save();
                          
//             if ($home_info) {
//                 $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
//             } else {
//                 $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
//             }
//         // }
//     }

            
// }

else if ($hotel_type == 2) {
    $rules = [
        'type' => 'required',
        'background_type' => 'required',
        'content' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $requestData = $request->all();

        $homeaboutDetails = [
            'background_type' => $requestData['background_type'],
            'content' => $requestData['content'],
        ];

        if ($requestData['background_type'] == 1 && $request->hasFile('background')) {
            $homeaboutDetails['background'] = $request->file('background')->store('uploads');
        } elseif ($requestData['background_type'] == 2) {
            $homeaboutDetails['background'] = $requestData['background'];
        }

        $home_info = HomeInfoModel::where('type', $requestData['type'])->first();

        if (!$home_info) {
            $home_info = new HomeInfoModel();
            $home_info->type = $requestData['type'];
        }

        $home_info->details = $homeaboutDetails;
        $home_info->save();

        if ($home_info) {
            $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
        }
    }
}


 else if ($hotel_type == 3) {
    // Validation rules
    $rules = [
        'type' => 'required',
        // 'image.*' => 'required', // Change 'image' to 'image.*' to validate each uploaded image
        'link.*' => 'required', // Change 'link' to 'link.*' to validate each corresponding link
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        // Retrieve existing home info
        $home_info = HomeInfoModel::where('type', $request->input('type'))->first();
        $details = json_decode($home_info->details);
        $details_image =array_column($details,'image');

        // Initialize empty array to store image and link details
        $digitalCampaignDetails = [];

        // dd();
        // Loop through uploaded images and links
        foreach ($_FILES['image']['name'] as $key => $image) {
            if ($image == null) {
                $digitalCampaignDetailstmp=[];
                if(isset($details_image[$key])){
                    $digitalCampaignDetailstmp["image"] = $details_image[$key];
                }
                $digitalCampaignDetailstmp["link"] = $request->input('link')[$key];
                $digitalCampaignDetails[] = $digitalCampaignDetailstmp;
                continue;
            }

            $image = $request->file('image')[$key];
                $filename = $image->store('uploads'); // Store the image in the storage/images directory
                $digitalCampaignDetails[] = [
                    "image" => $filename,
                    "link" => $request->input('link')[$key]
                ];
                
        }

        if ($home_info) {
            // Decode existing details from JSON
            $detailsArray = json_decode($home_info->details, true);
            // Append new details to existing details
            // $detailsArray = array_merge($detailsArray, $digitalCampaignDetails);
            
            // Update home info
            $home_info->details = json_encode($digitalCampaignDetails);
            $home_info->save();
            $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
        } else {
            // Create new home info if not exists
            $home_info = new HomeInfoModel();
            $home_info->type = $request->input('type');
            $home_info->details = json_encode($digitalCampaignDetails);
            $home_info->save();
            $response = response()->json(['status' => true, 'message' => 'Home Info Added Successfully']);
        }
    }
}

// {
// // print_r(json_encode($request->input('section')));

//     $rules = [
//         'type' => 'required',
//         'image' => 'required',
//         'link' => 'required',
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//     } else {

//         $home_info = HomeInfoModel::where('type', $request->input('type'))->first();
//         // $bg_data = json_decode($home_info['details'], true);
//         $img = [];
//         $digitalCampaignDetails = [

     
//             foreach ($request->file('image') as $key=>$image) {

//                 $filename = $image->store('uploads'); // Store the image in the storage/images directory
//             //   dd($filename);
//             $img []= array("image"=>$filename,"link"=>$request->input('link')[$key]);
                
//             }

//             // 'image' => $request->file('image')->store('uploads'), // Adjust the storage path as needed
//             // 'link' => $requestData['link'],
            
//         ];

//         // // dd($bg_data);

//         // $details = [];
//         // foreach ($bg_data as $detail) {
//         //      dd($detail);
//         //     $details[] = [
//         //         'image' => asset('storage/app/'.$detail['image']),
//         //          'link' => $detail['link'],
//         //     ];
//         // }
        
//         // $home_info['details'] = $details;


        

        
//         // if ($hotel_type == 1) {
            

//             // $headerSliderDetails = [
//             //     // 'image' => $request->file('image')->store('uploads'),
//             //     'link' => $request->input('link'),
//             // ];

//             $img = [];
//             foreach ($request->file('image') as $key=>$image) {

//                 $filename = $image->store('uploads'); // Store the image in the storage/images directory
//             //   dd($filename);
//             $img []= array("image"=>$filename,"link"=>$request->input('link')[$key]);
                
//             }
// // print_r(json_encode($img));
// // die;
//                 // $post->images()->create(['filename' => $filename]);
//             // }

//             // if ($request->hasFile('image')) {
//             //     $headerSliderDetails['image'] = $request->file('image')->store('uploads');
//             // }


       

//             // if (!$home_info) {
//             //     $home_info = new HomeInfoModel();
//             //     $home_info->type = $request->input('type');
//             //     $home_info->details = json_encode([$headerSliderDetails]);
//             // } else {
//             //     // $detailsArray = json_decode($home_info->details, true);
//             //     // $detailsArray[] = $headerSliderDetails;
//             //     // $home_info->details = json_encode($detailsArray);
              
//             // }

           

//             if ($home_info) {
//                 $detailsArray = json_decode($home_info->details, true);
//                 //  $detailsArray = $homeaboutDetails;
//                 $detailsArray[] = $digitalCampaignDetails;
//                 $home_info->details = json_encode($detailsArray);
//                 // $home_info->details = json_encode($img);
//                 $home_info->save();
//                 $response = response()->json(['status' => true, 'message' => 'Home Info Added Successfully']);
//             } else {
//                 $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
//             }
//         // }
//     }
// }

else if ($hotel_type == 4) 
{
            $rules = [
                'type' => 'required',
                'you_tybe_link' => 'required',
                'title' => 'required',
                'content' => 'required',
                
            ];

            $requestData = $request->all();
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {

                           
                    $homevideoDetails = [

                        'you_tybe_link' => $requestData['you_tybe_link'],
                        'title' => $requestData['title'],
                        'content' => $requestData['content'],
                        
                    ];
                    $home_info = HomeInfoModel::where('type',$requestData['type'])->first();
                    // dd($home_info);


                        $home_info->details = $homevideoDetails;
                        $home_info->save();
                        if ($home_info) {
                        $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
                    } else {
                        $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
                    }
                      

                    
                    }

}
else if ($hotel_type == 5) 
{

$rules = [
    'title' => 'required',
    'image' => 'required|image', // Add appropriate validation for image uploads
    'country' => 'required',
    'you_tube_link' => 'required',
    'content' => 'required',
    'background_title' => 'required',
    'background_sub_title' => 'required',

];

$requestData = $request->all();
$validator = Validator::make($requestData, $rules);

if ($validator->fails()) {
    $response['status'] = false;
    $response['message'] = $validator->messages();
} else {

    $homeInfo = HomeInfoModel::where('type', $requestData['type'])->first();
    $detail = $homeInfo['details'];
    $bg_data = json_decode($detail);

        
    

    $digitalCampaignDetails = [
        'title' => $requestData['title'],
        'image' => $request->file('image')->store('uploads'), // Adjust the storage path as needed
        'country' => $requestData['country'],
        'you_tube_link' => $requestData['you_tube_link'],
        'content' => $requestData['content'],
    ];

   
    if (!$homeInfo) {
        // $home_info->details = json_encode([$homeaboutDetails]);
        $homeInfo->details = json_encode([$homeaboutDetails],['sections' => [$digitalCampaignDetails]]);
    } else {
        $detailsArray = json_decode($homeInfo->details, true);
        //  $detailsArray = $homeaboutDetails;
        $detailsArray['sections'][] = $digitalCampaignDetails;

        // $homeInfo = new HomeInfoModel();
        $homeInfo->type = $requestData['type'];

        $detailsArray['background_title'] =  $requestData['background_title'];
        $detailsArray['background_sub_title'] = $requestData['background_sub_title'];
        if ($request->hasFile('background_image') && $request->file('background_image')->isValid()) {
            $detailsArray['background_image'] = $request->file('background_image')->store('uploads');
        }
        $homeInfo->details = json_encode($detailsArray);
    }

    $homeInfo->save();

    if ($homeInfo) {
    $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
} else {
    $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
}
        
   
}
}

else if ($hotel_type == 6) 
// {

//     // $rules = [
//     //     'type' => 'required',
//     //     'title' => 'required',
//     //     'hotel_type' => 'required',
//     //     'category' => 'required',
//     //     'pos' => 'required',
//     // ];

//     $rules = [
//         'type' => 'required',
//         'magazine_id' => 'required',
//     ];



//     $requestData = $request->all();
//     // dd($requestData);

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//     } else {
       
//             $home_info = HomeInfoModel::where('type', $requestData['type'])->first();
            
//             if($home_info){
//                 $detailsArray = json_decode($home_info->details, true);
//                 //   dd($detailsArray);
     
//                 // $detailsArray2 = $digitalCampaignDetails;
//                 //  $detailsArray = $homeaboutDetails;
//                 // $detailsArray = $digitalCampaignDetails;
//                 $home_info->details = $requestData['magazine_id'];
        
//                 // dd($home_info->details);
          


//             $home_info->save();

  
//                 $response = response()->json(['status' => true, 'message' => 'Home Info Added Successfully']);
//             } else {
//                 $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
//             }
     
//     }
// }

{
    $rules = [
        'type' => 'required',
        'magazine_id' => 'required',
    ];
    
    $requestData = $request->all();
    $validator = Validator::make($request->all(), $rules);
    
    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_info = HomeInfoModel::where('type', $requestData['type'])->first();

        
        if ($home_info) {
            $existingIds = json_decode($home_info->details, true) ?? [];
     
             
              
                $newIds = $requestData['magazine_id']; 
                $combinedIds = array_merge($existingIds, $newIds);
    
                // Convert the combined array back to JSON
                $requestData['magazine_id'] = json_encode($combinedIds);
      
    
            $home_info->details = $requestData['magazine_id'];
            $home_info->save();
    
            $response = response()->json(['status' => true, 'message' => 'Home Info Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
        }
    }
    
}


    return $response;
    
}

// public function delete_magazine_cover(Request $request)
// {
//     $response = array("status" => false, 'message' => '');

//     $rules = [
//         'type' => 'required',
//         'magazine_id' => 'required',
//     ];
    

//      $requestData = $request->all();

//     // $validator = Validator::make($requestData, $rules);

//     $validator = Validator::make($request->all(), $rules);
//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//     } else {

//         $magazine_id = $requestData['magazine_id'];



//         // $ids = array_map('intval', explode(',', $magazine_id));
//         //  dd($ids);

        
//         // $type = $requestData['type'];


//          $home_info = HomeInfoModel::where('type', $requestData['type'])->first();
//          if (!$home_info) {
//             return response()->json(['message' => 'Magazine cover data not found'], 404);
//         }
//          $magazine_ids = $home_info->details;
//         //  dd(json_decode($magazine_ids));
//          unset($magazine_ids[$key]);

//          $magazine_ids = array_values($magazine_ids);
//          // dd($hotel_images);

//          // Update the 'file_pdf' array in the database
//          $home_info->details = json_encode($magazine_ids);
//          $hotel_data->save();

//         // $ids = array_map('intval', $magazine_ids);

//         //   dd($magazine_ids);

       

       

    
       

//         return response()->json(['message' => 'Magazine Cover data deleted Successfully!']);
        

//     }


// }
public function delete_magazine_cover(Request $request)
{
    $response = ['status' => false, 'message' => ''];

    $rules = [
        'type' => 'required',
        'magazine_id' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
        return response()->json($response, 400);
    }

    $magazineId = $request->input('magazine_id');
    $type = $request->input('type');

    $homeInfo = HomeInfoModel::where('type', $type)->first();

    if (!$homeInfo) {
        return response()->json(['message' => 'Magazine cover data not found'], 404);
    }

    $magazineIds = json_decode($homeInfo->details, true);

    // Find the index of the magazine_id in the array
    $key = array_search($magazineId, $magazineIds);

    // Check if the magazine_id exists in the array
    if ($key !== false) {
        // Unset the element at the specified key
        unset($magazineIds[$key]);

        // Re-index the array
        $magazineIds = array_values($magazineIds);

        // Update the 'details' field in the database
        $homeInfo->details = json_encode($magazineIds);
        $homeInfo->save();

        return response()->json(['message' => 'Magazine Cover data deleted successfully']);
    } else {
        return response()->json(['message' => 'Invalid magazine_id for the given type'], 400);
    }
}


public function edit_magazine_cover(Request $request)
{
    $response = ['status' => false, 'message' => ''];

    $rules = [
        'type' => 'required',
        // 'magazine_id' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
        return response()->json($response, 400);
    }

    // $magazineId = $request->input('magazine_id');
    $type = $request->input('type');

    $homeInfo = HomeInfoModel::where('type', $type)->first();



    if (!$homeInfo) {
        return response()->json(['message' => 'Magazine cover data not found'], 404);
    }

    $magazineIds = json_decode($homeInfo->details, true);
    // dd($magazineIds);
   

    $magazine_dt = MagazinesModel::whereIn('id', $magazineIds)->get();
    //  dd($magazine_dt);

    if ($magazine_dt) {
        foreach ($magazine_dt as $magazine) {
        $magazine->thumbnail = asset("storage/app/".$magazine->thumbnail);
        $pdf_files = $magazine->file_pdf;
        $pdfarr = []; // Initialize $pdfarr here
        if(!empty($pdf_files)){

           $pdf  = json_decode($pdf_files);
           foreach ($pdf as $key => $val) {
               $fullImagePath = asset("storage/app/".$val);
               $pdfarr[] = $fullImagePath;
           }
           
   
        }
        $magazine->file_pdf = $pdfarr; 
        
        // $hotel_magazine->file_pdf = asset("storage/app/".$hotel_magazine->file_pdf);
        $response['status'] = true;
        $response['message'] = $magazine_dt;
        // Do something with $hotel_amenity
 
    }
}
    else {
        $response['message'] = 'Magazine not found';
    }
    return response()->json($response);
}


  
public function edit_home_info(Request $request)
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
        
        $home_info = HomeInfoModel::where('type', $home_info_type)->first();
       


        if ($home_info) {
            $home_info_detail = json_decode($home_info['details'], true);
            
            if($home_info['type']==1){
                // $home_info_detail = json_decode($home_info['details'], true);
            // dd($home_info_detail);

            $details = [];
            foreach ($home_info_detail as $detail) {
                // dd($detail);
                $details[] = [
                    'category' => asset('storage/app/'.$detail['category']),
                     'name' => $detail['name'],
                     'pos' => $detail['pos'],
                     'hotel_type'=> $detail['hotel_type'],
                ];
            }
            
            $home_info['details'] = $details;
           

            }

            if($home_info['type']==2){
                

                if ($home_info_detail['background_type'] == 1) {
                    $home_info_detail['background'] = asset("storage/app/".$home_info_detail['background']);
                } elseif ($home_info_detail['background_type'] == 2) {
                    
                }


                $home_info['details'] = $home_info_detail;  
         
            }

            if($home_info['type']==3){

                $details = [];
            foreach ($home_info_detail as $detail) {
                // dd($detail);
                $details[] = [
                    'image' => asset('storage/app/'.$detail['image']),
                     'link' => $detail['link'],
                ];
            }
            
            $home_info['details'] = $details;
         
            }

            if($home_info['type']==4){
                $home_info_detail['you_tybe_link'] = asset("storage/app/".$home_info_detail['you_tybe_link']);
                $home_info['details'] = $home_info_detail;  
                
         
            }
          

            if ($home_info['type'] == 5) {
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
            


            if($home_info['type']==6){
            $details = [];
            foreach ($home_info_detail as $detail) {
             
                 if($detail['hotel_type'] == 1){
                    $details[] = [
                        'category' => asset('storage/app/'.$detail['category']),
                         'title' => $detail['title'],
                         'pos' => $detail['pos'],
                         'hotel_type'=> $detail['hotel_type'],
                    ];
                 }
                 else if($detail['hotel_type'] == 2){
                    $details[] = [
                        'category' => $detail['category'],
                         'title' => $detail['title'],
                         'pos' => $detail['pos'],
                         'hotel_type'=> $detail['hotel_type'],
                    ];
                 }
                
            }
            
            $home_info['details'] = $details;
           

            }

            
            $data = $home_info;
            $response = response()->json(['status' => true, 'message' => 'Home Info Data Found', 'data' => $data]);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Data Not Found','data'=>$data]);
        }
    }

      return $response;
}



public function delete_home_info(Request $request)
{

    // echo "rtrt";

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


    $home_info = HomeInfoModel::where('type',$home_info_type)->first();
    // dd($home_info['type']);
    


        if (!$home_info) {
            return response()->json(['message' => 'Home Info not found'], 404);
        }
        if ($home_info['type'] == 3 || $home_info['type'] == 6 || $home_info['type'] == 1) 
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
            $response['message'] = 'Home Info deleted successfully.';
        } else {
            $response['message'] = 'Invalid key provided.';
        }
    }

    if ($home_info['type'] == 5) {
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
            $response['message'] = 'Home Info deleted successfully.';
        } else {
            $response['message'] = 'Invalid key provided.';
        }
    }

}
return response()->json($response);


}

public function delete_single_hotel_image(Request $request)
{

    // echo "rtrt";

    $response = array("status" => false, 'message' => '');

    $rules = [
        'hotel_id' => 'required',
        'key' => 'required'
        
    ];

     $requestData = $request->all();

    $validator = Validator::make($requestData, $rules);

    // $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

    $hotel_id = $requestData['hotel_id'];
    $key = $requestData['key'];


    $hotel_data = HotelModel::find($hotel_id);
       
       
        if ($hotel_data) {
            $hotel_images = json_decode($hotel_data['hotel_images'], true);
         
        if (array_key_exists($key, $hotel_images)) {
     
            // Remove the file using the provided key
            unset($hotel_images[$key]);
            // Reindex array keys
            $hotel_images = array_values($hotel_images);
            // dd($hotel_images);

            // Update the 'file_pdf' array in the database
            $hotel_data->hotel_images = json_encode($hotel_images);
            $hotel_data->save();

            $response['status'] = true;
            $response['message'] = 'Hotel Image deleted successfully.';
        } else {
            $response['message'] = 'Invalid key provided.';
        }
    }

    
    }
    return response()->json($response);
   
}

public function update_single_home_info(Request $request)
{

    //  echo "rtrt";

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


    $home_info = HomeInfoModel::where('type',$home_info_type)->first();
    // dd($home_info['type']);
    


        if (!$home_info) {
            return response()->json(['message' => 'Home Info not found'], 404);
        }
       

    if ($home_info['type'] == 5) {
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
            $response['message'] = 'Home Info deleted successfully.';
        } else {
            $response['message'] = 'Invalid key provided.';
        }
    }

}
return response()->json($response);


}





    }


