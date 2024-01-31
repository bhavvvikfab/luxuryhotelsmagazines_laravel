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


        if (!$home_info) {
            return response()->json(['message' => 'Hotel Info not found'], 404);
        }

        $home_detail = json_decode($home_info['details']);
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

return response()->json($response);
}

    }


