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


class PropertiesController extends Controller
{
    public function AddHotelProperties(Request $request)
    {

        $response = array("status" => false, 'message' => '');
       
            $rules = [
                'country_name' => 'required',
                'hotel_thumbnail' => 'required',
                'property_title' => 'required',
                'property_website' => 'required',
                'hotel_video' => 'required',
                'you_tube_link' => 'required',
                'short_description' => 'required',
                'long_description' => 'required',
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
               
                $properties_data = new PropertiesModel();
         
                $properties_data->country_name = $requestData['country_name'];
                $properties_data->property_title = $requestData['property_title'];
                $properties_data->property_website = $requestData['property_website'];
                $properties_data->you_tube_link = $requestData['you_tube_link'];
                $properties_data->short_description = $requestData['short_description'];
                $properties_data->long_description = $requestData['long_description'];


                if ($request->hasFile('hotel_thumbnail')) {
                    $pdfFiles = $request->file('hotel_thumbnail');
                    $path = $pdfFiles->store('uploads');
                    $properties_data->hotel_thumbnail = $path;
    
            }

            if ($request->hasFile('hotel_video')) {
                $pdfFiles = $request->file('hotel_video');
                $path = $pdfFiles->store('uploads');
                $properties_data->hotel_video = $path;

        }
            
                $properties_data->save();

                if ($properties_data) {
                    $response =  response()->json(['status' => true,'message' => 'Property Created Successfully!']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create Property!']);
                }
        
          }

              return $response;
    }

    public function AllHotelProperties()
{

    $data = PropertiesModel::all();
    $data->transform(function ($item) {

        $item->hotel_thumbnail = asset("storage/app/".$item->hotel_thumbnail);
        $item->hotel_video = asset("storage/app/".$item->hotel_video);
        
        return $item;
    });

        return response()->json(['status' => true,'data'=>$data]);

}

public function UpdateHotelProperties(Request $request)
    {

        $response = array("status" => false, 'message' => '');

        $rules = [
            'country_name' => 'required',
            'hotel_thumbnail' => 'required',
            'property_title' => 'required',
            'property_website' => 'required',
            'hotel_video' => 'required',
            'you_tube_link' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
        ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);
        
                if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                }else {
                    $hotel_properties_id = $requestData['hotel_properties_id'];
                    $hotel_properties_data = PropertiesModel::find($hotel_properties_id);
            
                    if ($hotel_properties_data) {
                  
                    $hotel_properties_data->country_name = $requestData['country_name'];
                    $hotel_properties_data->property_title = $requestData['property_title'];
                    $hotel_properties_data->property_website = $requestData['property_website'];
                    $hotel_properties_data->you_tube_link = $requestData['you_tube_link'];
                    $hotel_properties_data->short_description = $requestData['short_description'];
                    $hotel_properties_data->long_description = $requestData['long_description'];
              
                    if ($request->hasFile('hotel_thumbnail')) {
                        $hotel_properties_data->hotel_thumbnail = $request->file('hotel_thumbnail')->store('uploads');
                    }
                    if ($request->hasFile('hotel_video')) {
                        $hotel_properties_data->hotel_video = $request->file('hotel_video')->store('uploads');
                    }
                    
                    $hotel_properties_data->save();
        
                    $response = response()->json(['status' => true, 'message' => 'Properties Updated Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Properties not found']);
                }
            }

              return $response;
    }

    public function EditHotelProperties(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
       
        $rules = [
            'hotel_properties_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $hotel_properties_id = $request['hotel_properties_id'];
            $hotel_properties_data = PropertiesModel::find($hotel_properties_id);
    
            if ($hotel_properties_data) {
                if ($request->hasFile('hotel_thumbnail')) {
                    $hotel_properties_data->hotel_thumbnail = $request->file('hotel_thumbnail')->store('uploads');
                }

                if ($request->hasFile('hotel_video')) {
                    $hotel_properties_data->hotel_video = $request->file('hotel_video')->store('uploads');
                }

                $response['status'] = true;
                $response['message'] = $hotel_properties_data;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Properties not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }

    public function DeleteHotelProperties(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'hotel_properties_id' => 'required',
        ];

        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $hotel_properties_id = $requestData['hotel_properties_id'];
    
    
        $hotel_properties_data = PropertiesModel::find($hotel_properties_id);
       
            if (!$hotel_properties_data) {
                return response()->json(['status'=>false,'message'=>'Properties not found'], 404);
            }
    
    
            $hotel_properties_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Properties Deleted Successfully!']);
    
        
        }
    
        return $response;
    }

}
