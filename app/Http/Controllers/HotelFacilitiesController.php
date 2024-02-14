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
use App\Models\HotelFacilitiesModel;
use App\Models\MagazinesModel;
use App\Models\News;
// use App\Models\HotelFacilitiesModel;

use Illuminate\Support\Facades\Storage;

class HotelFacilitiesController extends Controller
{
    public function AddHotelFacilities(Request $request)
{


    $response = array("status" => false, 'message' => '');

    $rules = [
        'title' => 'required|unique:hotel_facilities',
        'type' => 'required',
        'image' => 'required',
    ];

    // $requestData = $request->json()->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $hotel_facilities = new HotelFacilitiesModel();
        $hotel_facilities->title = $request['title'];
        $hotel_facilities->type = $request['type'];
        $last = HotelFacilitiesModel::orderBy('sort_order','desc');
        if($last->count() > 0){
            $last = $last->first();
            $last->sort_order++;

            $hotel_facilities->sort_order = $last->sort_order;
        }else{
            $hotel_facilities->sort_order = 1;
        }
        $hotel_facilities->image = $request->file('image')->store('uploads');

        $hotel_facilities->save();

        if ($hotel_facilities) {
            $response = response()->json(['status' => true, 'message' => 'Hotel Facilities Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add Hotel Facilities!']);
        }
    }

    return $response;
}

public function UpdateHotelFacilities(Request $request)
{

    $response = array("status" => false, 'message' => '');
    // $requestData = $request->all();
    $facilities_id = $request['facilities_id'];

    $rules = [
        'facilities_id' => 'required',
        'title' => 'required|unique:hotel_facilities,title,'. $facilities_id ,
        'type' => 'required',
        //  'image' => 'required',
    ];

   
 
    $validator = Validator::make($request->all(), $rules);
  
    if ($validator->fails()) {
        $response['message'] = $validator->messages();

    } else {
 
        $hotel_facilities = HotelFacilitiesModel::find($facilities_id);

        if (!$hotel_facilities) {
            $response['message'] = 'Amenity not found';
        } else {
            // Update the fields
            $hotel_facilities->title = $request['title'];
            $hotel_facilities->type = $request['type'];
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $hotel_facilities->image = $request->file('image')->store('uploads');
            }
            // $hotel_facilities->amenities_id = $requestData->input('amenities_id');
            // Handle image update logic here

            // Save the changes
            $hotel_facilities->save();

            if ($hotel_facilities) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Facilities Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update Hotel Facilities!']);
            }
        }
    
    }
    return $response;
}

public function DeleteHotelFacilities(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'facilities_id' => 'required',
        
    ];

     $requestData = $request->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

    $facilities_id = $requestData['facilities_id'];
    $hotel_facilities = HotelFacilitiesModel::find($facilities_id);

        if (!$hotel_facilities) {
            return response()->json(['status'=>false,'message' => 'Hotel Facilities not found'], 404);
        }

        $hotel_facilities->delete();

        $response = response()->json(['status'=>true,'message' => 'Hotel Facilities deleted successfully']);

    
    }
    return $response;
   
}

public function AllHotelFacilities()
{

    $data = HotelFacilitiesModel::orderBy('id','DESC')->get();
    $data->transform(function ($item) {
        $item->fullImagePath = asset("storage/app/".$item->image);
        return $item;
    });


        return response()->json(['status' => true,'data'=>$data]);

}

public function EditHotelFacilities(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'facilities_id' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $request->messages();
    } else {
        $facilities_id = $request['facilities_id'];

        $hotel_facilities = HotelFacilitiesModel::find($facilities_id);
    
        if ($hotel_facilities) {
            $hotel_facilities->fullImagePath = asset("storage/app/".$hotel_facilities->image);
            $response['status'] = true;
            $response['message'] = $hotel_facilities;
            // Do something with $hotel_amenity
     
        } else {
            $response['message'] = 'Hotel Facilities not found';
        }
    }

    // You might want to return the response at the end of your function
    return response()->json($response);
}

public function sort_order_facilities(Request $request)
{
    // echo "fewrf";
    $response = array("status" => false, 'message' => '');

    $rules = [
        'sort_order' => 'required',
        'key' => 'required',
    ];

    $requestData =  $request->all();
    $validator = Validator::make($requestData, $rules);
    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    }
    else{
        $sort_order = $requestData['sort_order'];
        $key = $requestData['key'];

        $hotel_amenity_sort_data = HotelFacilitiesModel::where('id', $key)->first();

        if (!$hotel_amenity_sort_data) {
            $response['message'] = 'Facilities not found';
        } else {
            $hotel_amenity_sort_data->sort_order = $requestData['sort_order'];
            $hotel_amenity_sort_data->save();

            if ($hotel_amenity_sort_data) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Facilities Sort Order Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update hotel Facilities sort order!']);
            }
        }
    
    }
    return $response;
}

}
