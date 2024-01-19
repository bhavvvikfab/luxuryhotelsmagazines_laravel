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

class VotedHotelController extends Controller
{
    public function AddVotedHotel(Request $request)
    {

        $response = array("status" => false, 'message' => '');
       
            $rules = [
                'hotel_title' => 'required',
                'hotel_website' => 'required',
                'hotel_description' => 'required',
                'hotel_thumbnail' => 'required',
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
               
                $voted_hotel_data = new VotedHotelModel();
         
                $voted_hotel_data->hotel_title = $requestData['hotel_title'];
                $voted_hotel_data->hotel_website = $requestData['hotel_website'];
                $voted_hotel_data->hotel_description = $requestData['hotel_description'];

                if ($request->hasFile('hotel_thumbnail')) {
                    $pdfFiles = $request->file('hotel_thumbnail');
                    $path = $pdfFiles->store('uploads');
                    $voted_hotel_data->hotel_thumbnail = $path;
    
            }
            
                $voted_hotel_data->save();

                if ($voted_hotel_data) {
                    $response =  response()->json(['status' => true,'message' => 'Voted Hotel Created Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create voted hotel']);
                }
        
          }

              return $response;
    }


    public function AllVotedHotel()
{

    $data = VotedHotelModel::all();
    $data->transform(function ($item) {

        $item->hotel_thumbnail = asset("storage/app/".$item->hotel_thumbnail);
        return $item;
    });

        return response()->json(['status' => true,'data'=>$data]);

}


public function EditVotedHotel(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
       
        $rules = [
            'voted_hotel_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $voted_hotel_id = $request['voted_hotel_id'];
            $voted_hotel_data = VotedHotelModel::find($voted_hotel_id);
    
            if ($voted_hotel_data) {
                if ($request->hasFile('hotel_thumbnail')) {
                    $voted_hotel_data->hotel_thumbnail = $request->file('hotel_thumbnail')->store('uploads');
                }

                $response['status'] = true;
                $response['message'] = $voted_hotel_data;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Voted hotel not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }
    public function UpdateVotedHotel(Request $request)
    {

        $response = array("status" => false, 'message' => '');

        $rules = [
            'hotel_title' => 'required',
            'hotel_website' => 'required',
            'hotel_description' => 'required',
            'hotel_thumbnail' => 'required',
        ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);
        
                if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                }else {
                    $voted_hotel_id = $requestData['voted_hotel_id'];
                    $voted_hotel_data = VotedHotelModel::find($voted_hotel_id);
            
                    if ($voted_hotel_data) {
                  
                    $voted_hotel_data->hotel_title = $requestData['hotel_title'];
                    $voted_hotel_data->hotel_website = $requestData['hotel_website'];
                    $voted_hotel_data->hotel_description = $requestData['hotel_description'];
              
                    if ($request->hasFile('hotel_thumbnail')) {
                        $voted_hotel_data->hotel_thumbnail = $request->file('hotel_thumbnail')->store('uploads');
                    }
                    
                    $voted_hotel_data->save();
        
                    $response = response()->json(['status' => true, 'message' => 'Voted Hotel Updated Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Voted Hotel not found']);
                }
            }

              return $response;
    }

    public function DeleteVotedHotel(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'voted_hotel_id' => 'required',
        ];

        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $voted_hotel_id = $requestData['voted_hotel_id'];
    
    
        $voted_hotel_data = VotedHotelModel::find($voted_hotel_id);
       
            if (!$voted_hotel_data) {
                return response()->json(['status'=>false,'message'=>'Voted hotel not found'], 404);
            }
    
           
    
            $voted_hotel_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Voted Hotel Deleted Successfully!']);
    
        
        }
    
        return $response;
    }

}
