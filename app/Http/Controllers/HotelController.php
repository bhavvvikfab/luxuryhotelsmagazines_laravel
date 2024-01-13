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
use Illuminate\Support\Facades\Storage;




class HotelController extends Controller
{


    public function AllHomeData()
{
   

    // Retrieve data from MagazinesModel
    $magazine_data = MagazinesModel::orderBy('id', 'desc')->limit(10)->get();



    // Retrieve data from HotelModel
    $hotel_data = HotelModel::orderBy('id', 'desc')->limit(10)->get();

    // Retrieve data from News model
    $news_data = News::orderBy('id', 'desc')->limit(10)->get();

    // Merge data into a single array
    $data = [
        'magazines' => $magazine_data,
        'hotels' => $hotel_data,
        'news' => $news_data,
    ];

    // Debug the data


    // Return the data as JSON
    return response()->json(['status' => true, 'data' => $data]);
}

public function HotelRegister(Request $request)
{
    

    $response = array("status" => false, 'message' => '');
    // $requestData = $request->all(); 
    // dd($requestData);

    $rules = [
        // 'user_id' => 'required',
        'country' => 'required',
        'hotel_title' => 'required',
        'address' => 'required',
        'about_hotel' => 'required',
        'restaurent_bars' => 'required',
        'spa_wellness' => 'required',
        // 'hotel_images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'rooms_and_suites' => 'required',
        'amities' => 'required',
        'other_facilities' => 'required',
        'youtube_link' => 'required',
        'otherInformation1' => 'required',
        'otherInformation2' => 'required',
        'website' => 'required',
        'contact_no' => 'required',
        'name' => 'required',
        'email' => 'required',
        'contact_no' => 'required',
        'offer_title' => 'required',
        'type' => 'required',
        'from_date' => 'required',
        'to_date' => 'required',
        'home_page_latest_news' => 'required',
        'hotel_latest_news' => 'required',
        'special_offer_to_homepage' => 'required',
        'home_page_spotlight' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);


    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
         $hotel = new HotelModel($request->only(['user_id', 'country', 'hotel_title', 'address', 'about_hotel', 'restaurent_bars', 'description','spa_wellness', 'rooms_and_suites', 'amities', 'other_facilities', 'youtube_link', 'otherInformation1', 'otherInformation2', 'website', 'contact_no']));
        // $hotel = new HotelModel($request->only(['country', 'hotel_title', 'address', 'about_hotel', 'restaurent_bars', 'description','spa_wellness', 'rooms_and_suites', 'amities', 'other_facilities', 'youtube_link', 'otherInformation1', 'otherInformation2', 'website', 'contact_no']));
        // $hotel->hotel_images = $this->uploadImage($request->file('hotel_images'));
        $hotel->hotel_images = $request->file('hotel_images')->store('uploads');
 

        $hotel->save();
  
// print_r($request->input('name'));

        $contact_name = json_encode($request->input('name'));
        $contact_email = json_encode($request->input('email'));
        $contact_no = json_encode($request->input('contact'));
       
        $hotel_contacts = new HotelContactsModel([
            'hotel_id' => $hotel->id,
            'name' => $contact_name,
            'email' => $contact_email,
            'contact_no' => $contact_no,
        ]);
        $hotel_contacts->save();

        $hotel_special_offer = new HotelSpecialOfferModel([
            'hotel_id' => $hotel->id,
            'offer_title' => $request->input('offer_title'),
            'type' => $request->input('type'),
            'contact_no' => $request->input('contact_no'),
            'description' => $request->input('description'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'redeem_link' => $request->input('redeem_link'),
        ]);
        $hotel_special_offer->save();

        $hotel_page_addon = new HotelPageAddonModel([
            'hotel_id' => $hotel->id,
            'home_page_latest_news' => $request->input('home_page_latest_news'),
            'hotel_latest_news' => $request->input('hotel_latest_news'),
            'special_offer_to_homepage' => $request->input('special_offer_to_homepage'),
            'home_page_spotlight' => $request->input('home_page_spotlight'),
        ]);
        $hotel_page_addon->save();

        $response = response()->json(['status' => true, 'message' => 'Hotel Created Successfully']);
    }

    return $response;
}


public function AllHotels()
{

    $data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->get();

    $data->transform(function ($item) {
        $item->fullImagePath = asset("storage/app/".$item->hotel_images);
        return $item;
    });

        return response()->json(['status' => true,'data'=>$data]);

}



public function EditHotels(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'hotel_id' => 'required',
        
    ];

    $requestData = $request->all();

    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $hotel_id = $requestData['hotel_id'];

    $data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);

}

return response()->json(['status' => true,'data'=>$data]);
}


public function UpdateHotels(Request $request){

    $response = array("status"=>false,'message' => '');

     $rules = [
        // 'user_id' => 'required',
        'hotel_id' => 'required',
        'country' => 'required',
        'hotel_title' => 'required',
        'address' => 'required',
        'about_hotel' => 'required',
        'restaurent_bars' => 'required',
        'spa_wellness' => 'required',
        // 'hotel_images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'rooms_and_suites' => 'required',
        'amities' => 'required',
        'other_facilities' => 'required',
        'youtube_link' => 'required',
        'otherInformation1' => 'required',
        'otherInformation2' => 'required',
        'website' => 'required',
        'contact_no' => 'required',
        'name' => 'required',
         'email' => 'required',
        'contact_no' => 'required',
        'offer_title' => 'required',
        'type' => 'required',
        'from_date' => 'required',
        'to_date' => 'required',
        'home_page_latest_news' => 'required',
        'hotel_latest_news' => 'required',
        'special_offer_to_homepage' => 'required',
        'home_page_spotlight' => 'required',
    ];


    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
          }else {
            $hotel_id = $requestData['hotel_id'];
            $hotel_data = HotelModel::find($hotel_id);
    
            if ($hotel_data) {
                
                $hotel_data->user_id = $requestData['user_id'];
                $hotel_data->country = $requestData['country'];
                $hotel_data->hotel_title =$requestData['hotel_title'];
                $hotel_data->address = $requestData['address'];
                $hotel_data->about_hotel = $requestData['about_hotel'];
                $hotel_data->restaurent_bars =$requestData['restaurent_bars'];
            
                $hotel_data->spa_wellness = $requestData['spa_wellness'];
                $hotel_data->rooms_and_suites = $requestData['rooms_and_suites'];
                $hotel_data->amities =$requestData['amities'];
                $hotel_data->other_facilities =$requestData['other_facilities'];
                $hotel_data->youtube_link =$requestData['youtube_link'];
                $hotel_data->otherInformation1 = $requestData['otherInformation1'];
                $hotel_data->otherInformation2 = $requestData['otherInformation2'];

                // $news->news_image = $requestData['news_image'];
                if ($request->hasFile('hotel_images')) {
                    $hotel_data->hotel_images = $request->file('hotel_images')->store('uploads');
                }
                
         
                $hotel_data->save();
    
               
                $HotelContactsModel = HotelContactsModel::where('hotel_id', $hotel_id)->first();
    
                if (!$HotelContactsModel) {
                    $HotelContactsModel = new HotelContactsModel();
                    $HotelContactsModel->hotel_id = $hotel_id;
                }

                $contact_name = json_encode($request->input('name'));
                $contact_email = json_encode($request->input('email'));
                $contact_no = json_encode($request->input('contact'));


                $HotelContactsModel->name = $contact_name;
                $HotelContactsModel->email = $contact_email;
                $HotelContactsModel->contact_no = $contact_no;
                
                $HotelContactsModel->save();


                $HotelSpecialOfferModel = HotelSpecialOfferModel::where('hotel_id', $hotel_id)->first();
    
                if (!$HotelSpecialOfferModel) {
                    $HotelSpecialOfferModel = new HotelSpecialOfferModel();
                    $HotelSpecialOfferModel->hotel_id = $hotel_id;
                }


                $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
            $HotelSpecialOfferModel->type = $requestData['type'];
            $HotelSpecialOfferModel->from_date =$requestData['from_date'];
            $HotelSpecialOfferModel->to_date =$requestData['to_date'];

            $HotelSpecialOfferModel->redeem_link =$requestData['redeem_link'];
            $HotelSpecialOfferModel->description =$requestData['description'];

            $HotelSpecialOfferModel->save();


            $HotelPageAddonModel = HotelPageAddonModel::where('hotel_id', $hotel_id)->first();
    
                if (!$HotelPageAddonModel) {
                    $HotelPageAddonModel = new HotelPageAddonModel();
                    $HotelPageAddonModel->hotel_id = $hotel_id;
                }


                $HotelPageAddonModel->hotel_latest_news =$requestData['hotel_latest_news'];
               
                $HotelPageAddonModel->home_page_latest_news =$requestData['home_page_latest_news'];
                $HotelPageAddonModel->special_offer_to_homepage =$requestData['special_offer_to_homepage'];
                $HotelPageAddonModel->home_page_spotlight =$requestData['home_page_spotlight'];

            $HotelPageAddonModel->save();




    
                $response = response()->json(['status' => true, 'message' => 'Hotel Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Hotel not found']);
            }
        }

          return $response;
}




public function DeleteHotels(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'hotel_id' => 'required',
        
    ];

    $requestData = $request->all();

    $validator = Validator::make($requestData, $rules);

    // $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {


    

    $hotel_id = $requestData['hotel_id'];


    $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);



        if (!$hotel_data) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $hotel_data->hotel_contacts()->delete();
        $hotel_data->home_page_addon()->delete();
        $hotel_data->special_offer()->delete();

        // Now, delete the hotel itself
        $hotel_data->delete();

        return response()->json(['message' => 'Hotel Deleted Successfully!']);

    
    }


}



public function searchHotel(Request $request)
{
    try {
        $requestData = $request->all(); // Use $request->all() instead of $request->json()->all()

        $country = isset($requestData['country'])?$requestData['country']:''; // Fix typo in variable name

        $hotel_name = isset($requestData['hotel_keyword'])?$requestData['hotel_keyword']:'';
        
        // dd($coutry_id, $hotel_name);

        if (!empty($country) && empty($hotel_name)) {
            $hotel_data = HotelModel::where('country', $country)->get();
        } elseif (!empty($hotel_name) && empty($country)) {
            $hotel_data = HotelModel::where('hotel_title', 'like', '%' . $hotel_name . '%')
                ->orWhere('address', 'like', '%' . $hotel_name . '%')
                ->get();
        } elseif (!empty($country) && !empty($hotel_name)) {
            $hotel_data = HotelModel::where('country', $country)
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
    $data->transform(function ($item) {
        $item->fullImagePath = asset("storage/app/".$item->image);
        return $item;
    });


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
            $hotel_amenity->fullImagePath = asset("storage/app/".$hotel_amenity->image);
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

public function LoginUserHotels(Request $request)
{
    $user = Auth::guard('api')->user();
    
    $u_id = $user['id'];
    $data = HotelModel::where('user_id', $u_id)->orderBy('id', 'desc')->get();

    return response()->json(['status' => true,'login_user_hotel_data'=>$data]);


}



}
