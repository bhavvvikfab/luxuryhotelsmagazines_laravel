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





class HotelController extends Controller
{
    // public function HotelRegister(Request $request)
    // {
    //     // echo "fii";
    //     // die;

        


    //    $response = array("status"=>false,'message' => '');
    //     $rules = [

    //         'user_id' => 'required',
    //         'hotel_title' => 'required',
    //         'address' => 'required',
    //         'hotel_images' => 'required',
    //         'rooms_and_suites' => 'required',
    //         'other_facilities' => 'required',
    //         'youtube_link' => 'required',
    //         'website' => 'required',
    //         'contact_no' => 'required',
    //         'hotel_id' => 'required',
    //         "subdata": {
    //             "name": "required",
    //             "email": "required|email|unique",
    //             "contact_no": "required"
    //           },
    //     ];
    //     $requestData = $request->json()->all();
       

    //     $validator = Validator::make($requestData, $rules);
        
    //         if ($validator->fails()) {
         

    //             $response['message'] = $validator->messages();
    //           } else {
    //             // if (HotelModel::where('email', $requestData['email'])->exists()) {
    //             //     return response()->json(['status' => false,'message' => 'Email already exists']);
    //             //     exit;
    //             // }

                


    //             $hotel = new HotelModel();
           
    //             $hotel->user_id = $requestData['user_id'];
    //             $hotel->hotel_title = $requestData['hotel_title'];
    //             $hotel->address = $requestData['address'];
    //             $hotel->hotel_images = $requestData['hotel_images'];
    //             $hotel->youtube_link = $requestData['youtube_link'];
    //             $hotel->rooms_and_suites = $requestData['rooms_and_suites'];
    //             $hotel->other_facilities = $requestData['other_facilities'];
    //             $hotel->website = $requestData['website'];
    //             $hotel->contact_no = $requestData['contact_no'];

    //             $hotel->save();

    //             $last_insert_id = $hotel->id;
                
    //             $hotel_contacts = new HotelContactsModel();
           
    //             $last_insert_id = $requestData['hotel_id'];
    //             $hotel_contacts->name = $requestData['name'];
    //             $hotel_contacts->email = $requestData['email'];
    //             $hotel_contacts->contact_no = $requestData['contact_no'];

    //             $hotel_contacts->save();
               

    //             if ($hotel && $hotel_contacts) {
    //                 $response =  response()->json(['status' => true,'message' => 'Hotel Created Successfully']);
    //             } else {
    //                 $response = response()->json(['status' => false,'message' => 'Failed to create hotel']);
    //             }
        
    //           }

    //           return $response;
    // }
    public function HotelRegister(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'user_id' => 'required',
        'coutry_id' => 'required',
        'hotel_title' => 'required',
        'address' => 'required',
        'hotel_images' => 'required',
        'rooms_and_suites' => 'required',
        'other_facilities' => 'required',
        'youtube_link' => 'required',
        'website' => 'required',
        'contact_no' => 'required:hotels',
        'hotel_id' => 'required',
        'offer_title' => 'required',
        'contact_no' => 'required:special_offer',
        'from_date' => 'required',
        'to_date' => 'required',
        'home_page_latest_news' => 'required',
        'hotel_latest_news' => 'required',
        'special_offer_to_homepage' => 'required',
        'home_page_spotlight' => 'required',
        // 'subdata.hotel_id' => 'required',
        'subdata.name' => 'required',
        'subdata.email' => 'required|unique:hotel_contacts,email',
        'subdata.contact_no' => 'required',
    ];

    $requestData = $request->json()->all();

    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $hotel = new HotelModel();

        $hotel->user_id = $requestData['user_id'];
        $hotel->coutry_id = $requestData['coutry_id'];
        //  $hotel->coutry_id = 1;
        $hotel->hotel_title = $requestData['hotel_title'];
        $hotel->address = $requestData['address'];
        $hotel->hotel_images = $requestData['hotel_images'];
        $hotel->youtube_link = $requestData['youtube_link'];
        $hotel->rooms_and_suites = $requestData['rooms_and_suites'];
        $hotel->other_facilities = $requestData['other_facilities'];
        $hotel->website = $requestData['website'];
        $hotel->contact_no = $requestData['contact_no'];

        $hotel->save();

        $last_insert_id = $hotel->id;

        $hotel_contacts = new HotelContactsModel();

        $hotel_contacts->hotel_id = $last_insert_id; // Assuming you want to link the contacts to the hotel
        $hotel_contacts->name = json_encode($requestData['subdata']['name']);
        $hotel_contacts->email = json_encode($requestData['subdata']['email']);
        $hotel_contacts->contact_no = json_encode($requestData['subdata']['contact_no']);

        $hotel_contacts->save();

        $hotel_special_offer = new HotelSpecialOfferModel();

        $hotel_special_offer->hotel_id = $last_insert_id; // Assuming you want to link the contacts to the hotel
        $hotel_special_offer->offer_title = $requestData['offer_title'];
        $hotel_special_offer->type = $requestData['type'];
        $hotel_special_offer->contact_no = $requestData['contact_no'];
        $hotel_special_offer->description = $requestData['description'];
        $hotel_special_offer->from_date = $requestData['from_date'];
        $hotel_special_offer->to_date = $requestData['to_date'];

        $hotel_special_offer->save();

        
        $hotel_page_addon = new HotelPageAddonModel();

        $hotel_page_addon->hotel_id = $last_insert_id; // Assuming you want to link the contacts to the hotel
        $hotel_page_addon->home_page_latest_news = $requestData['home_page_latest_news'];
        $hotel_page_addon->hotel_latest_news = $requestData['hotel_latest_news'];
        $hotel_page_addon->special_offer_to_homepage = $requestData['special_offer_to_homepage'];
        $hotel_page_addon->home_page_spotlight = $requestData['home_page_spotlight'];

        $hotel_page_addon->save();


        if ($hotel && $hotel_contacts && $hotel_special_offer && $hotel_page_addon) {
            $response = response()->json(['status' => true, 'message' => 'Hotel Created Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to create hotel']);
        }
    }

    return $response;
}



public function searchHotel(Request $request)
{
    try {
        $requestData = $request->all(); // Use $request->all() instead of $request->json()->all()

        $coutry_id = isset($requestData['coutry_id'])?$requestData['coutry_id']:''; // Fix typo in variable name

        $hotel_name = isset($requestData['hotel_keyword'])?$requestData['hotel_keyword']:'';
        
        // dd($coutry_id, $hotel_name);

        if (!empty($coutry_id) && empty($hotel_name)) {
            $hotel_data = HotelModel::where('coutry_id', $coutry_id)->get();
        } elseif (!empty($hotel_name) && empty($coutry_id)) {
            $hotel_data = HotelModel::where('hotel_title', 'like', '%' . $hotel_name . '%')
                ->orWhere('address', 'like', '%' . $hotel_name . '%')
                ->get();
        } elseif (!empty($coutry_id) && !empty($hotel_name)) {
            $hotel_data = HotelModel::where('coutry_id', $coutry_id)
                ->where(function ($query) use ($hotel_name) {
                    $query->where('hotel_title', 'like', '%' . $hotel_name . '%')
                        ->orWhere('address', 'like', '%' . $hotel_name . '%');
                })
                ->get();
        } else {
            $hotel_data = HotelModel::all();
        }

        if ($hotel_data->isEmpty()) {
            return response()->json(['message' => 'No data found']);
        }

        return response()->json(['hotel_data' => $hotel_data]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function AddHotelAmeties(Request $request)
{


    $response = array("status" => false, 'message' => '');

    $rules = [
        'title' => 'required|unique:hotel_amieties',
        'type' => 'required',
        'image' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $hotel_ameties = new HotelAmetiesModel();
        $hotel_ameties->title = $request['title'];
        $hotel_ameties->type = $request['type'];
        $hotel_ameties->image = $request->file('image')->store('uploads');

        $hotel_ameties->save();


        if ($hotel_ameties) {
            $response = response()->json(['status' => true, 'message' => 'Hotel Ameties Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add hotel ameties!']);
        }
    }

    return $response;
}

public function UpdateHotelAmeties(Request $request)
{



    $response = array("status" => false, 'message' => '');
    // $requestData = $request->all();
    $ameties_id = $request['ameties_id'];

    $rules = [
        'ameties_id' => 'required',
        'title' => 'required|unique:hotel_amieties,title,'. $ameties_id ,
        'type' => 'required',
        // 'image' => 'required',
    ];

   
 
    $validator = Validator::make($request->all(), $rules);
  
 
    // $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();

    } else {

        // Validation passed, proceed with the update logic

        // Assuming you have a model for HotelAmenity
        
 
        $amenity = HotelAmetiesModel::find($ameties_id);



        if (!$amenity) {
            $response['message'] = 'Amenity not found';
        } else {
            // Update the fields
            $amenity->title = $request['title'];
            $amenity->type = $request['type'];
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $amenity->image = $request->file('image')->store('uploads');
            }
            // $amenity->amenities_id = $requestData->input('amenities_id');
            // Handle image update logic here

            // Save the changes
            $amenity->save();

            if ($amenity) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Ameties Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update hotel ameties!']);
            }
        }
    
    }
    return $response;
}

public function DeleteHotelAmeties(Request $request)
{

    // echo "rtrt";

    $response = array("status" => false, 'message' => '');

    $rules = [
        'ameties_id' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {


    

    $ameties_id = $request['ameties_id'];


    $hotel_ameties = HotelAmetiesModel::find($ameties_id);

        if (!$hotel_ameties) {
            return response()->json(['message' => 'Hotel Ameties not found'], 404);
        }

        $hotel_ameties->delete();

        return response()->json(['message' => 'Hotel Ameties deleted successfully']);

    
    }
   
}

public function AllHotelAmeties()
{


    $data = HotelAmetiesModel::all();



        return response()->json(['status' => true,'data'=>$data]);

}

public function EditHotelAmeties(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'ameties_id' => 'required',
        
    ];

    // $requestData = $request->json()->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $request->messages();
    } else {
        $ameties_id = $request['ameties_id'];

        $hotel_amenity = HotelAmetiesModel::find($ameties_id);
    


        if ($hotel_amenity) {
            $response['status'] = true;
            $response['message'] = $hotel_amenity;
            // Do something with $hotel_amenity
     
        } else {
            $response['message'] = 'Hotel amenity not found';
        }
    }

    // You might want to return the response at the end of your function
    return response()->json($response);
}


}
