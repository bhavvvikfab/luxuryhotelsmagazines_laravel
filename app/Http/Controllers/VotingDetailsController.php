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
use App\Models\VotingDetailsModel;
use App\Helpers\Helpers;




class VotingDetailsController extends Controller
{
    public function add_voting_details(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all();
        // if($requestData['type']==1)
        // {
            $rules = [
                 'type' => 'required',
                'hotel_id' => 'nullable',
                'name' => 'required',
                'email' => 'required',
                'description' => 'required',
            ];


        // }
        // else if($requestData['type']==2)
        // {
        //     $rules = [
        //         'type' => 'required',
        //         'news_id' => 'nullable',
        //         'name' => 'required',
        //         'email' => 'required',
        //         'description' => 'required',
        //     ];

        // }
        
   
     $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $voting_details = new VotingDetailsModel();
        // dd($voting_details);

        $voting_details->name = $requestData['name'];
        $voting_details->type = $requestData['type'];
        $voting_details->email = $requestData['email'];
        $voting_details->description = $requestData['description'];
        // if($requestData['type']==1){
            $voting_details->hotel_id = $requestData['hotel_id'];
            $hotel_dt = HotelModel::where('id', $requestData['hotel_id'])->first();
            $hotel_name = $hotel_dt['hotel_title'];

        // }
        // else if($requestData['type']==2) {
        //     $voting_details->news_id = $requestData['news_id'];
        //     $voting_details->hotel_id = $requestData['hotel_id'];
        //     $news_dt = News::where('id', $requestData['hotel_id'])->first();
        //     $news_name = $news_dt['news_title'];
        // }


        $voting_details->save();

        // if($requestData['type']==1){
    
            $subject = 'Voting User Data';
    
            $key = 7;
  
            $body = "Voting Details!\nHotel Name: {$hotel_name}\nName: {$requestData['name']}\nEmail: {$requestData['email']}";
            
            // Email data
            $to = $requestData['email'];
    
            $data = [
                'name' => $requestData['name'],
                'email' => $to,
                'key' => $key,
                'hotel_name' => $hotel_name,
                'description name' => $requestData['description'],
            ];
          
        // }

        // if($requestData['type']==2){
    
        //     $subject = 'Voting User Data';
    
        //     $key = 8;
  
        //     $body = "Voting Details!\nNews Name: {$news_name}\nName: {$requestData['name']}\nEmail: {$requestData['email']}";
            
        //     // Email data
        //     $to = $requestData['email'];
    
        //     $data = [
        //         'name' => $requestData['name'],
        //         'email' => $to,
        //         'key' => $key,
        //         'news_name' => $news_name,
        //         'description' => $requestData['description'],
        //     ];
          
        // }


            $helpers = new Helpers();
            $result = $helpers->sendEmail($to, $subject, $body, $key, $data);

        if ($result) {
            $response = response()->json(['status' => true, 'message' => 'Voting Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add Voting!']);
        }
    }

    return $response;
}

public function all_voting_details(Request $request)
{
    $data = VotingDetailsModel::with('hotels')->orderBy('id','DESC')->get();

    $data->transform(function ($item) {
        
        // Convert news images to full URLs
        if (!empty($item->hotel_images)) {
            $imagePaths = json_decode($item->hotel_images, true);
            $fullImagePaths = [];

            foreach ($imagePaths as $image) {
                $fullImagePaths[] = asset("storage/app/" . $image);
            }

            $item->hotel_images = $fullImagePaths;
        } else {
            $item->hotel_images = [];
        }

      

        return $item;
    });

  

    return response()->json(['status' => true, 'data' => $data]);
}
}


