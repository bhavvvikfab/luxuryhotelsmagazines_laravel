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
use App\Models\HomeHotelModel;
use Illuminate\Support\Facades\Storage;

class HomeHotelController extends Controller
{
    public function AddHomeHotel(Request $request)
    {


        $response = array("status" => false, 'message' => '');
        // $requestData = $request->all(); 
        // dd($requestData);
    
        $rules = [
            // 'user_id' => 'required',
            'country_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'u_tube_link' => 'required',
            
        ];
    
        $validator = Validator::make($request->all(), $rules);

        
    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $home_hotel = new HomeHotelModel();
        $home_hotel->country_name = $request['country_name'];
        $home_hotel->title = $request['title'];
        $home_hotel->description = $request['description'];
   
        $home_hotel->u_tube_link = $request['u_tube_link'];

        $home_hotel->save();


        if ($home_hotel) {
            $response = response()->json(['status' => true, 'message' => 'Home Hotel Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add home hotel!']);
        }
    }

    return $response;
}

    public function AllHomeHotel()
    {
        $data = HomeHotelModel::all();
      
            return response()->json(['status' => true,'data'=>$data]);

    }

    public function LimitAllHomeHotel(Request $request)
    {
        
        $perPage = $request->input('paginate', 10); // Set a default value (e.g., 10) if 'paginate' is not provided

        $data = HomeHotelModel::paginate($perPage);
    
        return response()->json(['status' => true, 'data' => $data]);

    }
    

    public function EditHomeHotel(Request $request)
    {
       

        $response = array("status" => false, 'message' => '');

        $rules = [
            'home_hotel_id' => 'required',
            
        ];
    
        // $requestData = $request->json()->all();
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $home_hotel_id = $request['home_hotel_id'];
    
            $home_hotel_data = HomeHotelModel::find($home_hotel_id);
        
            if ($home_hotel_data) {
               
                $response['status'] = true;
                $response['message'] = $home_hotel_data;
                // Do something with $hotel_amenity
         
            } else {
                $response['message'] = 'Home hotel not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }

    public function UpdateHomeHotel(Request $request)
{



    $response = array("status" => false, 'message' => '');
    // $requestData = $request->all();
  

    $rules = [
        // 'user_id' => 'required',
        'country_name' => 'required',
        'title' => 'required',
        'description' => 'required',
        'u_tube_link' => 'required',
        
    ];
 
    $validator = Validator::make($request->all(), $rules);
  
 
    // $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();

    } else {

        // Validation passed, proceed with the update logic

        // Assuming you have a model for HotelAmenity
        
        $home_hotel_id = $request['home_hotel_id'];
        $home_hotel_data = HomeHotelModel::find($home_hotel_id);


        if (!$home_hotel_data) {
            $response['message'] = 'Amenity not found';
        } else {
            // Update the fields
            $home_hotel_data->country_name = $request['country_name'];
            $home_hotel_data->title = $request['title'];
            $home_hotel_data->description = $request['description'];
            $home_hotel_data->u_tube_link = $request['u_tube_link'];
           
           
            // $amenity->amenities_id = $requestData->input('amenities_id');
            // Handle image update logic here

            // Save the changes
            $home_hotel_data->save();

            if ($home_hotel_data) {
                $response = response()->json(['status' => true, 'message' => 'Home Hotel Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update home hotel!']);
            }
        }
    
    }
    return $response;
}


    public function DeleteHomeHotel(Request $request)
{

    // echo "rtrt";

    $response = array("status" => false, 'message' => '');

    $rules = [
        'home_hotel_id' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

    $home_hotel_id = $request['home_hotel_id'];


    $hotel_ameties = HomeHotelModel::find($home_hotel_id);

        if (!$hotel_ameties) {
            return response()->json(['message' => 'Home Hotel not found'], 404);
        }

        $hotel_ameties->delete();

        return response()->json(['message' => 'Home Hotel deleted successfully']);

    
    }
   
}


    }
    

