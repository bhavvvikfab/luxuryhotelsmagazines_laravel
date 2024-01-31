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



class HomeInfoController extends Controller
{
    


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
        
        // if ($hotel_type == 1) {
            

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
        // }
    }
}

else if ($hotel_type == 2) 
{

        $rules = [
            // 'user_id' => 'required',
            'type' => 'required',
            'background_type' => 'required',
            'background' => 'required',
            // 'overlay_image_1' => 'required',
            // 'overlay_image_2' => 'required',
            
        ];

        $requestData = $request->all();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                } else {
                    
                   
                    // if($hotel_type == 2)
                    // {
                       
                        $homeaboutDetails = [

                            'background_type' => $requestData['background_type'],
                            // 'background' => $request->file('background')->store('uploads'),
                            'content' => $requestData['content'],
                            // 'overlay_image_1' => $request->file('overlay_image_1')->store('uploads'),
                            // 'overlay_image_2' => $request->file('overlay_image_2')->store('uploads'),
                            
                        ];
                        if ($request->hasFile('background')) {
                            $homeaboutDetails['background'] = $request->file('background')->store('uploads');
                        }

                        // if ($request->hasFile('overlay_image_1')) {
                        //     $homeaboutDetails['overlay_image_1'] = $request->file('overlay_image_1')->store('uploads');
                        // }

                        // if ($request->hasFile('overlay_image_2')) {
                        //     $homeaboutDetails['overlay_image_2'] = $request->file('overlay_image_2')->store('uploads');
                        // }

                        
                        // $home_info  = new HomeInfoModel();
                        $home_info = HomeInfoModel::where('type',$requestData['type'])->first();
                        // dd($home_info);


                          $home_info->details = $homeaboutDetails;
                          $home_info->save();
                          
            if ($home_info) {
                $response = response()->json(['status' => true, 'message' => 'Home Info Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add home info!']);
            }
        // }
    }

            
}

if ($hotel_type == 3) 
{

    $rules = [
        'type' => 'required',
        'image' => 'required',
        'link' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        
        // if ($hotel_type == 1) {
            

            $headerSliderDetails = [
                // 'image' => $request->file('image')->store('uploads'),
                'link' => $request->input('link'),
            ];
            if ($request->hasFile('image')) {
                $headerSliderDetails['image'] = $request->file('image')->store('uploads');
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
        // }
    }
}

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

                                'you_tybe_link' => $request->file('you_tybe_link')->store('uploads'),
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
        
    // if ($homeInfo) {
    //     $response['status'] = true;
    //     $response['message'] = 'Home Info Updated Successfully';
    // } else {
    //     $response['status'] = false;
    //     $response['message'] = 'Failed to add home info!';
    // }
}
}

else if ($hotel_type == 6) 
{

    $rules = [
        'type' => 'required',
        'title' => 'required',
        'hotel_type' => 'required',
        'category' => 'required',
        'pos' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        
        // if ($hotel_type == 1) {
            

            $headerSliderDetails = [
                'title' => $request->input('title'),
                'hotel_type' => $request->input('hotel_type'),
                // 'category' => $request->file('category')->store('uploads'),
                'pos' => $request->input('pos'),
            ];

            if ($request->hasFile('category')) {
                $headerSliderDetails['category'] = $request->file('category')->store('uploads');
            }
            

            $home_info = HomeInfoModel::where('type', $request->input('type'))->first();
            // dd($home_info);


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
        // }
    }
}


// return response()->json($response);


    return $response;
    
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
                $home_info_detail['background'] = asset("storage/app/".$home_info_detail['background']);
                // $home_info_detail['overlay_image_1'] = asset("storage/app/".$home_info_detail['overlay_image_1']);
                // $home_info_detail['overlay_image_2'] = asset("storage/app/".$home_info_detail['overlay_image_2']);

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

            // if($home_info['type']==5){
            //      $home_info_detail = json_decode($home_info['details'], true);
            // //    dd($home_info_detail);
            //   $details['background_image'] =  asset("storage/app/".$home_info_detail['background_image']);
            // // $home_info['details'] = $home_info_detail;  

            // $details = [];
            // foreach ($home_info_detail['sections'] as $detail) {
            //     // dd($detail);
            //     $details[] = [
            //         'image' => asset('storage/app/'.$detail['image']),
            //          'title' => $detail['title'],
            //          'country' => $detail['country'],
            //          'you_tube_link'=> $detail['you_tube_link'],
            //          'content'=> $detail['content'],
            //     ];
            // }
            
            // $home_info['details'] = $details;
           

            // }

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
                // $home_info_detail = json_decode($home_info['details'], true);
            // dd($home_info_detail);

            $details = [];
            foreach ($home_info_detail as $detail) {
                // dd($detail);
                $details[] = [
                    'category' => asset('storage/app/'.$detail['category']),
                     'title' => $detail['title'],
                     'pos' => $detail['pos'],
                     'hotel_type'=> $detail['hotel_type'],
                ];
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


//     else if($home_info['type'] == 5)
//     {
//         // echo "trhrth";
//         $home_detail = json_decode($home_info['details'], true);

// // Get all sections data
// $sections = $home_detail['sections'];
// // dd($sections);



// if (array_key_exists($key, $sections)) {
//     // Remove the file using the provided key
//     unset($sections[$key]);
//     // Reindex array keys
//     $section = array_values($sections);
//     // dd($section);

//     $detailsArray = json_decode($section->details, true);
//     $detailsArray['sections'][] = $home_detail;
//     $homeInfo->details = json_encode($detailsArray);


//     // Update the 'file_pdf' array in the database
//     // $home_info->details = json_encode($sections);
//     $home_info->save();

//     $response['status'] = true;
//     $response['message'] = 'Home Info deleted successfully.';
// } else {
//     $response['message'] = 'Invalid key provided.';
// }



// // foreach ($sections as $index => $section) {
// //     // dd($section);
// //     // Access data from each section
// //     $title = $section['title'];
// //     $image = $section['image'];
// //     $country = $section['country'];
// //     $you_tube_link = $section['you_tube_link'];
// //     $content = $section['content'];

// //     // Process or use the data as needed

// //     // Unset the current index
// //     unset($home_detail['sections'][$index]);
// //     // unset($sections[$index]);
// // }
// // $home_detail['sections'] = array_values($home_detail['sections']);

// // // $sections = array_values($sections);
// // dd($home_detail['sections']);

// // Optionally, update the original array with modified sections
// // $home_detail['sections'] = $sections;

// // If you want to encode it back to JSON
// // $updated_details_json = json_encode($home_detail);

// // Print or use the updated JSON
// // dd($updated_details_json);




//         // $response['status'] = true;

//     }

//     }


}

    }


