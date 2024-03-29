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
use App\Models\Review_Category_Model;
use App\Models\Review_Topics_Model;
use App\Models\Guest_Review_Model;

use Illuminate\Support\Facades\Storage;




class HotelController extends Controller
{

   
    
    public function AllHomeData(Request $request)
{
    

    // Retrieve data from MagazinesModel
    $magazine_count = 10;
    if(isset($request['magazine_count'])){
        $magazine_count = $request['magazine_count'];
    }
    $magazine_data = MagazinesModel::orderBy('id', 'desc')->limit($magazine_count)->get();
    $magazine_data->transform(function ($item) {

        $item->thumbnail = asset("storage/app/".$item->thumbnail);
        $pdf_files = $item->file_pdf;
        $pdf_images = $item->file_image;

        $pdfarr = []; // Initialize $pdfarr here
        $imagearr = [];
     if(!empty($pdf_files)){

        $pdf  = json_decode($pdf_files);
        foreach ($pdf as $key => $val) {
            $fullImagePath = asset("storage/app/".$val);
            $pdfarr[] = $fullImagePath;
        }
        

     }
     if(!empty($pdf_images)){

        $pdf  = json_decode($pdf_images);
        foreach ($pdf as $key => $val) {
            $fullImagePath = asset("storage/app/".$val);
            $imagesarr[] = $fullImagePath;
        }
        

     }

     $item->file_pdf = isset($pdfarr)?$pdfarr:''; 
     $item->file_image = isset($imagesarr)?$imagesarr:''; 

        return $item;
    });

    // Retrieve data from HotelModel
    $Hotel_count = 10;
    if(isset($request['Hotel_count'])){
        $Hotel_count = $request['Hotel_count'];
    }
    $hotel_data = HotelModel::orderBy('id', 'desc')->limit($Hotel_count)->get();
    $hotel_data->transform(function ($item) {
        

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


    // Retrieve data from News model
    $News_count = 10;
    if(isset($request['News_count'])){
        $News_count = $request['News_count'];
    }
    $news_data = News::orderBy('id', 'desc')->limit($News_count)->get();
    $news_data->transform(function ($item) {
        // dd($item);

        

        // Convert news images to full URLs
        if (!empty($item->news_image)) {
            $imagePaths = json_decode($item->news_image, true);
            $fullImagePaths = [];
            if(!empty($imagePaths)){
                foreach ($imagePaths as $image) {
                    $fullImagePaths[] = asset("storage/app/" . $image);
                }
            }

            $item->news_image = $fullImagePaths;
        } else {
            $item->news_image = [];
        }

        return $item;
    });

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

public function add_special_offer(Request $request)
{
 
    $response = array("status" => false, 'message' => '');
    // $user = Auth::guard('api')->user();
    // if(isset($request['user_id']) && !empty($request['user_id'])){
    //     $userExists = User::where('id', $request['user_id'])->exists();
    //     if ($userExists) {
    //         $user = User::where('id', $request['user_id'])->first();
    //     } else {
    //         $response['message'] = 'User does not exist.';
    //         return $response;
    //     }
    // }

            $rules = [
                // 'user_id' => 'required',
                'hotel_id' => 'required',
                'phone_number' => 'required',
                 'contact_no' => 'required',
                 'offer_title' => 'required',
                 'type' => 'required',
                 'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'nullable',
                'reedem_link' => 'nullable', 
            ];

            $validator = Validator::make($request->all(), $rules);



            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

                // $hotel_data  = HotelModel::where()
                $hotel_special_offer = new HotelSpecialOfferModel();
                $hotel_special_offer->hotel_id = $request->input('hotel_id');
                $hotel_special_offer->offer_title = $request->input('offer_title');
                $hotel_special_offer->type = $request->input('type');
                $hotel_special_offer->phone_number = $request->input('phone_number');
                $hotel_special_offer->contact_no = $request->input('contact_no');
                $hotel_special_offer->description = $request->input('description');
                $hotel_special_offer->from_date = $request->input('from_date');
                $hotel_special_offer->to_date = $request->input('to_date');
                $hotel_special_offer->reedem_link = $request->input('reedem_link');
                
                $hotel_special_offer->save();

                

                $response = response()->json(['status' => true, 'message' => 'Special Offer Created Successfully','data'=>$hotel_special_offer]);
            
        }

            return $response;
}

public function all_special_offer()
{
    // $data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->get();
    $data = HotelSpecialOfferModel::with('hotels')->get();
    //  dd($data);


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

public function edit_special_offer(Request $request)
        {

            $response = array("status" => false, 'message' => '');

            $rules = [
                'special_offer_id' => 'required',
                
            ];

            $requestData = $request->all();

            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {
                $special_offer_id = $requestData['special_offer_id'];
                $data = [];

            // $hotel_data = HotelSpecialOfferModel::with('hotels')->find($special_offer_id);
            $special_offer_data = HotelSpecialOfferModel::find($special_offer_id);
            $hotel_data = HotelModel::where('id',$special_offer_data['hotel_id'])->first();
            // dd($hotel_data);
            $hotel_name  = $hotel_data['hotel_title'];
  


            if ($special_offer_data) {
                

                // $hotel_images_data = json_decode($hotel_data['hotel_images'], true);
                
                // $details = [];
                // foreach ($hotel_images_data as $detail) {
                //     $details[] = asset('storage/app/'.$detail);
                // }
                // $hotel_data['hotel_images'] = $details;
                
         

                $data = $special_offer_data;
                $response = response()->json(['status' => true, 'message' => 'Special Offer Data Found', 'data' => $data,'hotel_name' => $hotel_name]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Special Offer Data Not Found','data'=>$data]);
            }
        }
    
          return $response;
    }

    public function update_special_offer(Request $request)
{

    $response = array("status" => false, 'message' => '');
    $rules = [
        // 'user_id' => 'required',
        'special_offer_id' => 'required',
        'phone_number' => 'required',
         'contact_no' => 'required',
         'offer_title' => 'required',
         'type' => 'required',
         'from_date' => 'required',
        'to_date' => 'required',
        'description' => 'nullable',
        'reedem_link' => 'nullable', 
    ];


    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
          }else {
            $special_offer_id = $requestData['special_offer_id'];
         $HotelSpecialOfferData = HotelSpecialOfferModel::where('id', $special_offer_id)->first();
        //  dd($HotelSpecialOfferData);

    if($HotelSpecialOfferData){
        
        $HotelSpecialOfferData->offer_title = $requestData['offer_title'];
        $HotelSpecialOfferData->type = $requestData['type'];
        $HotelSpecialOfferData->from_date =$requestData['from_date'];
        $HotelSpecialOfferData->to_date =$requestData['to_date'];
      
       
       
        $HotelSpecialOfferData->phone_number =$requestData['phone_number'];
        $HotelSpecialOfferData->contact_no =$requestData['contact_no'];

        if (isset($requestData['description'])) {
            $HotelSpecialOfferData->description =$requestData['description'];
        }

        // if (isset($requestData['special_offer'])) {
        //     $HotelSpecialOfferData->special_offer =$requestData['special_offer'];
        // }

        
        $HotelSpecialOfferData->save();

        $response = response()->json(['status' => true, 'message' => 'Special Offer Data Updated Successfully!']);
    } else {
        $response = response()->json(['status' => false, 'message' => 'Special Offer Data Not Found!']);
    }
}

  return $response;
}




public function delete_special_offer(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'special_offer_id' => 'required',
        
    ];

    $requestData = $request->all();

    $validator = Validator::make($requestData, $rules);

    // $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {


    

    $special_offer_id = $requestData['special_offer_id'];


    // $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);
    $special_offer_data = HotelSpecialOfferModel::find($special_offer_id);


        if (!$special_offer_data) {
            return response()->json(['message' => 'Special Offer not found'], 404);
        }

    

        // Now, delete the hotel itself
        $special_offer_data->delete();

        return response()->json(['message' => 'Special Offer Deleted Successfully!']);

    
    }


}


public function HotelRegister(Request $request)
{
 
    $response = array("status" => false, 'message' => '');
    $user = Auth::guard('api')->user();
    if(isset($request['user_id']) && !empty($request['user_id'])){
        $userExists = User::where('id', $request['user_id'])->exists();
        if ($userExists) {
            $user = User::where('id', $request['user_id'])->first();
        } else {
            $response['message'] = 'User does not exist.';
            return $response;
        }
    }

            $rules = [
                // 'user_id' => 'required',
                'country' => 'required',
                'hotel_title' => 'required',
                'address' => 'required',
                'about_hotel' => 'required',
                'restaurent_bars' => 'nullable',
                'spa_wellness' => 'nullable',
                'hotel_images' => 'required',
                'rooms_and_suites' => 'required',
                'amities' => 'required',
                'facilities' => 'required',
                'other_facilities' => 'nullable',
                'aditional_information' => 'nullable',
                'subscription_package' => 'nullable',
                'hotel_news' => 'nullable',
                'youtube_link' => 'required',
                'otherInformation1' => 'required',
                'otherInformation2' => 'required',
                'website' => 'required',
                'contact_no' => 'required',
                'name' => 'required',
                'email' => 'required',
                // 'contact_no' => 'required',
                // 'offer_title' => 'required',
                // 'type' => 'required',
                // 'from_date' => 'required',
                // 'to_date' => 'required',
                'home_page_latest_news' => 'required',
                'hotel_latest_news' => 'required',
                'special_offer_to_homepage' => 'required',
                'home_page_spotlight' => 'required',
                // 'reedem_link' => 'nullable', 
            ];

            $validator = Validator::make($request->all(), $rules);



            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

                
                $hotel_data = HotelModel::where('user_id',$user['id'])->first();
                if($hotel_data)
                {
                    $response = response()->json(['status' => false, 'message' => 'Hotel Already added by this user!']);

                }
                else {
               
                $hotel = new HotelModel($request->only([
                    'user_id',
                    'country',
                     'hotel_title',
                      'address',
                       'about_hotel',
                        'amities',
                        'facilities', 
                        'rooms_and_suites',
                        'restaurent_bars', 
                        'spa_wellnessc',
                         'other_facilities', 
                         'aditional_information',
                          'subscription_package', 
                          'hotel_news',
                           'youtube_link', 
                           'website',
                        ]));
               
                        $hotel->user_id = $user['id'];

               

                if ($request->hasFile('hotel_images')) {
                    $images = $request->file('hotel_images');

                    $imagePaths = [];
                
                    foreach ($images as $image) {

                        $imagePath = $image->store('uploads');
                        $imagePaths[] = $imagePath;
                      
                    }

                    $hotel->hotel_images = json_encode($imagePaths);
                }

                $hotel->save();
        
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

                // $hotel_special_offer = new HotelSpecialOfferModel([
                //     'hotel_id' => $hotel->id,
                //     'offer_title' => $request->input('offer_title'),
                //     'type' => $request->input('type'),
                //     'phone_number' => $request->input('phone_number'),
                //     'contact_no' => $request->input('contact_no'),
                //     'description' => $request->input('description'),
                //     'from_date' => $request->input('from_date'),
                //     'to_date' => $request->input('to_date'),
                //     'description' => $request->input('description'),
                //     'reedem_link' => $request->input('reedem_link'),
                // ]);
                // $hotel_special_offer->save();

                $hotel_page_addon = new HotelPageAddonModel([
                    'hotel_id' => $hotel->id,
                    'home_page_latest_news' => $request->input('home_page_latest_news'),
                    'hotel_latest_news' => $request->input('hotel_latest_news'),
                    'special_offer_to_homepage' => $request->input('special_offer_to_homepage'),
                    'home_page_spotlight' => $request->input('home_page_spotlight'),
                    'reedem_link' => $request->input('reedem_link'),
                ]);
                $hotel_page_addon->save();

                $response = response()->json(['status' => true, 'message' => 'Hotel Created Successfully']);
            }
        }

            return $response;
}


//         public function AllHotels()
//         {

//             $data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->get();
// // dd($data);


//             $data->transform(function ($item) {
//                 // dd($item['hotel_contacts']);

        
//                 $name = json_decode($item['hotel_contacts']['name'], true);
//                 $email = json_decode($item['hotel_contacts']['email'], true);
//                 $contact = json_decode($item['hotel_contacts']['contact'], true);
             
//                 // Convert news images to full URLs
                
//                 if (!empty($item->hotel_images)) {
                   
//                     $imagePaths = json_decode($item->hotel_images, true);
                   
//                     $fullImagePaths = [];
//                     foreach ($imagePaths as $image) {
//                         $fullImagePaths[] = asset("storage/app/".$image);
//                     }
    
//                     $item->hotel_images = $fullImagePaths;
//                 } else {
//                     $item->hotel_images = [];
//                 }
    
//                 $item->hotel_contacts->name = $name;
//                 $item->hotel_contacts->email = $email;
//                 $item->hotel_contacts->contact_no = $contact_no;

//                 return $item;
//             });

//             // $data->transform(function ($item) {
//             //     $item->fullImagePath = asset("storage/app/".$item->hotel_images);
//             //     return $item;
//             // });

//                 return response()->json(['status' => true,'data'=>$data]);

//         }

public function AllHotels()
{
    $data = HotelModel::with('hotel_contacts')->with('home_page_addon')
                        ->orderBy('id', 'desc') // Sort by ID in descending order
                        ->get();

    $data->transform(function ($item) {
        if (!empty($item['hotel_contacts'])) {
            $name = json_decode($item['hotel_contacts']['name'], true);
            $email = json_decode($item['hotel_contacts']['email'], true);
            $contact = json_decode($item['hotel_contacts']['contact'], true);

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

            $item->hotel_contacts->name = $name;
            $item->hotel_contacts->email = $email;
            $item->hotel_contacts->contact_no = $contact;
        }

        return $item;
    });

    return response()->json(['status' => true, 'data' => $data]);
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
                $data = [];

            // $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);
            $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->find($hotel_id);
// dd($hotel_data);

            //  dd($hotel_data['hotel_contacts']['email']);


            // $details = [];
            // foreach ($home_info_detail as $detail) {
            //     // dd($detail);
            //     $details[] = [
            //         'image' => asset('storage/app/'.$detail['image']),
            //          'link' => $detail['link'],
            //     ];
            // }
            
            // $home_info['details'] = $details;
            


            if ($hotel_data) {
                

                $hotel_images_data = json_decode($hotel_data['hotel_images'], true);
                // dd($hotel_images_data);
                $hotel_contacts_name= json_decode($hotel_data['hotel_contacts']['name'], true);
            

                $hotel_contacts_email= json_decode($hotel_data['hotel_contacts']['email'], true);
                $hotel_contacts_contact_no= json_decode($hotel_data['hotel_contacts']['contact_no'], true);

                // $hotel_images_data = array_column($hotel_images_data,"hotel_images");
                $details = [];
                foreach ($hotel_images_data as $detail) {
                    $details[] = asset('storage/app/'.$detail);
                }
                $hotel_data['hotel_images'] = $details;
                $hotel_data['hotel_contacts']['name'] = $hotel_contacts_name;
                $hotel_data['hotel_contacts']['email'] = $hotel_contacts_email;
                $hotel_data['hotel_contacts']['contact_no'] = $hotel_contacts_contact_no;
                // dd($hotel_data);
         
                

                // $details = [];
                // foreach ($hotel_images_data as $detail) {
                  
                //     $details[] = [
                //         'hotel_images' => asset('storage/app/'.$detail['hotel_images']),
                //         //  'country' => $detail['country'],
                //         //  'hotel_title' => $detail['hotel_title'],
                //         //  'address'=> $detail['address'],
                //         //  'about_hotel' => $detail['about_hotel'],
                //         //  'amities' => $detail['amities'],
                //         //  'rooms_and_suites'=> $detail['rooms_and_suites'],
                //         //  'restaurent_bars' => $detail['restaurent_bars'],
                //         //  'spa_wellness' => $detail['spa_wellness'],
                //         //  'other_facilities'=> $detail['other_facilities'],
                //     ];
                // }
                
                // $hotel_data['details'] = $hotel_images_data;

                $data = $hotel_data;
                $response = response()->json(['status' => true, 'message' => 'Hotel Data Found', 'data' => $data]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Hotel Data Not Found','data'=>$data]);
            }
        }
    
          return $response;
    }

    public function user_hotel(Request $request)
        {

            $response = array("status" => false, 'message' => '');

            $rules = [
                'user_id' => 'required',
                
            ];

            $requestData = $request->all();

            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {
                $user_id = $requestData['user_id'];
                $data = [];

            // $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);
            $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->where('user_id', $user_id)->first();

//  dd($hotel_data);

            //  dd($hotel_data['hotel_contacts']['email']);


            // $details = [];
            // foreach ($home_info_detail as $detail) {
            //     // dd($detail);
            //     $details[] = [
            //         'image' => asset('storage/app/'.$detail['image']),
            //          'link' => $detail['link'],
            //     ];
            // }
            
            // $home_info['details'] = $details;
            


            if ($hotel_data) {
                

                $hotel_images_data = json_decode($hotel_data['hotel_images'], true);
                // dd($hotel_images_data);
                $hotel_contacts_name= json_decode($hotel_data['hotel_contacts']['name'], true);
            

                $hotel_contacts_email= json_decode($hotel_data['hotel_contacts']['email'], true);
                $hotel_contacts_contact_no= json_decode($hotel_data['hotel_contacts']['contact_no'], true);

                // $hotel_images_data = array_column($hotel_images_data,"hotel_images");
                $details = [];
                foreach ($hotel_images_data as $detail) {
                    $details[] = asset('storage/app/'.$detail);
                }
                $hotel_data['hotel_images'] = $details;
                $hotel_data['hotel_contacts']['name'] = $hotel_contacts_name;
                $hotel_data['hotel_contacts']['email'] = $hotel_contacts_email;
                $hotel_data['hotel_contacts']['contact_no'] = $hotel_contacts_contact_no;
                // dd($hotel_data);
         
                

                // $details = [];
                // foreach ($hotel_images_data as $detail) {
                  
                //     $details[] = [
                //         'hotel_images' => asset('storage/app/'.$detail['hotel_images']),
                //         //  'country' => $detail['country'],
                //         //  'hotel_title' => $detail['hotel_title'],
                //         //  'address'=> $detail['address'],
                //         //  'about_hotel' => $detail['about_hotel'],
                //         //  'amities' => $detail['amities'],
                //         //  'rooms_and_suites'=> $detail['rooms_and_suites'],
                //         //  'restaurent_bars' => $detail['restaurent_bars'],
                //         //  'spa_wellness' => $detail['spa_wellness'],
                //         //  'other_facilities'=> $detail['other_facilities'],
                //     ];
                // }
                
                // $hotel_data['details'] = $hotel_images_data;

                $data = $hotel_data;
                $response = response()->json(['status' => true, 'message' => 'Hotel Data Found', 'data' => $data]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Hotel Data Not Found','data'=>$data]);
            }
        }
    
          return $response;
    }


public function UpdateHotels(Request $request)
{

    $response = array("status" => false, 'message' => '');
    $user = Auth::guard('api')->user();

    if(isset($request['user_id']) && !empty($request['user_id'])){
        $userExists = User::where('id', $request['user_id'])->exists();
        if ($userExists) {
            $user = User::where('id', $request['user_id'])->first();
      
        } else {
            $response['message'] = 'User does not exist.';
            return $response;
        }
      
    // $user = User::find($request['user_id']);
    }
       $u_id = $user['id'];
    // dd($u_id);


     $rules = [
                // 'user_id' => 'required',
                'country' => 'required',
                'hotel_title' => 'required',
                'address' => 'required',
                'about_hotel' => 'required',
                'restaurent_bars' => 'nullable',
                'spa_wellness' => 'nullable',
                //  'hotel_images' => 'required',
                'rooms_and_suites' => 'required',
                'amities' => 'required',
                'facilities' => 'required',
                'other_facilities' => 'nullable',
                'aditional_information' => 'nullable',
                'subscription_package' => 'nullable',
                'hotel_news' => 'nullable',
                'youtube_link' => 'required',
                'otherInformation1' => 'required',
                'otherInformation2' => 'required',
                'website' => 'required',
                'contact_no' => 'required',
                'name' => 'required',
                'email' => 'required',
                'contact_no' => 'required',
                // 'offer_title' => 'required',
                // 'type' => 'required',
                // 'from_date' => 'required',
                // 'to_date' => 'required',
                'home_page_latest_news' => 'required',
                'hotel_latest_news' => 'required',
                'special_offer_to_homepage' => 'required',
                'home_page_spotlight' => 'required',
                 'reedem_link' => 'nullable', 
            ];


    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
          }else {
            $hotel_id = $requestData['hotel_id'];
            $hotel_data = HotelModel::find($hotel_id);
            //  dd($hotel_data);

         

    
            if ($hotel_data) {
                // if (isset($user['id'])) {
                //     $hotel->user_id = $user['id'];
                // }
                $hotel_data->user_id = $user['id'];
                $hotel_data->country = $requestData['country'];
                $hotel_data->hotel_title =$requestData['hotel_title'];
                $hotel_data->address = $requestData['address'];
                $hotel_data->about_hotel = $requestData['about_hotel'];
                $hotel_data->amities =$requestData['amities'];
                $hotel_data->facilities =$requestData['facilities'];
                $hotel_data->rooms_and_suites = $requestData['rooms_and_suites'];
                
               
                $hotel_data->youtube_link =$requestData['youtube_link'];
                $hotel_data->website = $requestData['website'];

                if (isset($requestData['spa_wellness'])) {
                    $hotel_data->spa_wellness = $requestData['spa_wellness'];
                }
                if (isset($requestData['restaurent_bars'])) {
                    $hotel_data->restaurent_bars =$requestData['restaurent_bars'];
                }
                if (isset($requestData['other_facilities'])) {
                    $hotel_data->other_facilities =$requestData['other_facilities'];
                }



                if (isset($requestData['aditional_information'])) {
                    $hotel_data->aditional_information = $requestData['aditional_information'];
                }

                if (isset($requestData['subscription_package'])) {
                    $hotel_data->subscription_package =$requestData['subscription_package'];
                }
                if (isset($requestData['hotel_news'])) {
                    $hotel_data->hotel_news =$requestData['hotel_news'];
                }
               



                // $hotel_data->otherInformation2 = $requestData['otherInformation2'];

                
                // $img = [];
                // foreach ($request->file('hotel_images') as $key=>$image) {
    
                //     $filename = $image->store('uploads'); // Store the image in the storage/images directory
                // //   dd($filename);
                // $img []= array("hotel_images"=>$filename);
                    
                // }
                if ($request->hasFile('hotel_images')) {
                    $images = $request->file('hotel_images');
                
                    $imagePaths = [];
                
                    foreach ($images as $image) {
                        $imagePath = $image->store('uploads');
                        $imagePaths[] = $imagePath;
                    }
                
                    $hotel_data->hotel_images = json_encode($imagePaths);
                }

                // $img = [];
                // foreach ($request->file('hotel_images') as $key => $image) {
                //     $filename = $image->store('uploads');
                //     $img[] = $filename;
                // }
                
                // // Convert the array to JSON
                // $jsonImages = json_encode(["hotel_images" => $img]);
                
                // Now $jsonImages will have the desired JSON structure
                
                
                // $hotel_data->hotel_images = json_encode($img);
                // $hotel_data->hotel_images = $jsonImages;
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


                // $HotelSpecialOfferModel = HotelSpecialOfferModel::where('hotel_id', $hotel_id)->first();
    
                // if (!$HotelSpecialOfferModel) {
                //     $HotelSpecialOfferModel = new HotelSpecialOfferModel();
                //     $HotelSpecialOfferModel->hotel_id = $hotel_id;
                // }


                // $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
                // $HotelSpecialOfferModel->type = $requestData['type'];
                // $HotelSpecialOfferModel->from_date =$requestData['from_date'];
                // $HotelSpecialOfferModel->to_date =$requestData['to_date'];
              
               
               
                // $HotelSpecialOfferModel->phone_number =$requestData['phone_number'];
                // $HotelSpecialOfferModel->contact_no =$requestData['contact_no'];

                // if (isset($requestData['description'])) {
                //     $HotelSpecialOfferModel->description =$requestData['description'];
                // }

                // if (isset($requestData['special_offer'])) {
                //     $HotelSpecialOfferModel->special_offer =$requestData['special_offer'];
                // }

                

                // $HotelSpecialOfferModel->save();


                $HotelPageAddonModel = HotelPageAddonModel::where('hotel_id', $hotel_id)->first();
    
                if (!$HotelPageAddonModel) {
                    $HotelPageAddonModel = new HotelPageAddonModel();
                    $HotelPageAddonModel->hotel_id = $hotel_id;
                }


                $HotelPageAddonModel->hotel_latest_news =$requestData['hotel_latest_news'];
                $HotelPageAddonModel->home_page_latest_news =$requestData['home_page_latest_news'];
                $HotelPageAddonModel->special_offer_to_homepage =$requestData['special_offer_to_homepage'];
                $HotelPageAddonModel->home_page_spotlight =$requestData['home_page_spotlight'];
               
                if (isset($requestData['reedem_link'])) {
                    $HotelPageAddonModel->reedem_link =$requestData['reedem_link'];
                }
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


    // $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->with('special_offer')->find($hotel_id);
    $hotel_data = HotelModel::with('hotel_contacts')->with('home_page_addon')->find($hotel_id);


        if (!$hotel_data) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $hotel_data->hotel_contacts()->delete();
        $hotel_data->home_page_addon()->delete();
        // $hotel_data->special_offer()->delete();

        // Now, delete the hotel itself
        $hotel_data->delete();

        return response()->json(['message' => 'Hotel Deleted Successfully!']);

    
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
        $last = HotelAmetiesModel::orderBy('sort_order','desc');
        if($last->count() > 0){
            $last = $last->first();
            $last->sort_order++;

            $hotel_ameties->sort_order = $last->sort_order;
        }else{
            $hotel_ameties->sort_order = 1;
        }
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
  
    if ($validator->fails()) {
        $response['message'] = $validator->messages();

    } else {

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

    $data = HotelAmetiesModel::orderBy('sort_order','DESC')->get();
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

// public function LoginUserHotels(Request $request)
// {
//     $user = Auth::guard('api')->user();
    
//     $u_id = $user['id'];
//     $hotel_data = HotelModel::where('user_id', $u_id)->orderBy('id', 'desc')->get();
//     //   dd($hotel_data);

//     if ($hotel_data) {
                

//         $hotel_images_data = json_decode($hotel_data['hotel_images'], true);
//          dd($hotel_images_data);
//         $hotel_contacts_name= json_decode($hotel_data['hotel_contacts']['name'], true);
    

//         $hotel_contacts_email= json_decode($hotel_data['hotel_contacts']['email'], true);
//         $hotel_contacts_contact_no= json_decode($hotel_data['hotel_contacts']['contact_no'], true);

//         // $hotel_images_data = array_column($hotel_images_data,"hotel_images");
//         $details = [];
//         foreach ($hotel_images_data as $detail) {
//             $details[] = asset('storage/app/'.$detail);
//         }
//         $hotel_data['hotel_images'] = $details;
//         $hotel_data['hotel_contacts']['name'] = $hotel_contacts_name;
//         $hotel_data['hotel_contacts']['email'] = $hotel_contacts_email;
//         $hotel_data['hotel_contacts']['contact_no'] = $hotel_contacts_contact_no;
//         // dd($hotel_data);
 
        

//         // $details = [];
//         // foreach ($hotel_images_data as $detail) {
          
//         //     $details[] = [
//         //         'hotel_images' => asset('storage/app/'.$detail['hotel_images']),
//         //         //  'country' => $detail['country'],
//         //         //  'hotel_title' => $detail['hotel_title'],
//         //         //  'address'=> $detail['address'],
//         //         //  'about_hotel' => $detail['about_hotel'],
//         //         //  'amities' => $detail['amities'],
//         //         //  'rooms_and_suites'=> $detail['rooms_and_suites'],
//         //         //  'restaurent_bars' => $detail['restaurent_bars'],
//         //         //  'spa_wellness' => $detail['spa_wellness'],
//         //         //  'other_facilities'=> $detail['other_facilities'],
//         //     ];
//         // }
        
//         // $hotel_data['details'] = $hotel_images_data;

//         $data = $hotel_data;
//         $response = response()->json(['status' => true, 'message' => 'Hotel Found', 'login_user_hotel_data' => $data]);
//     } else {
//         $response = response()->json(['status' => false, 'message' => 'Hotel Not Found','data'=>$data]);
//     }
//     return $response;
// }

public function LoginUserHotels(Request $request)
{
    $user = Auth::guard('api')->user();
    
    $u_id = $user['id'];
    $hotel_data = HotelModel::where('user_id', $u_id)->orderBy('id', 'desc')->get();

    if ($hotel_data->count() > 0) {
        $hotel_list = [];

        foreach ($hotel_data as $hotel) {
            $hotel_images_data = json_decode($hotel['hotel_images'], true);
            $details = [];

            foreach ($hotel_images_data as $detail) {
                $details[] = asset('storage/app/'.$detail);
            }

            $hotel_contacts_name = json_decode($hotel['hotel_contacts']['name'], true);
            $hotel_contacts_email = json_decode($hotel['hotel_contacts']['email'], true);
            $hotel_contacts_contact_no = json_decode($hotel['hotel_contacts']['contact_no'], true);

            $hotel['hotel_images'] = $details;
            $hotel['hotel_contacts']['name'] = $hotel_contacts_name;
            $hotel['hotel_contacts']['email'] = $hotel_contacts_email;
            $hotel['hotel_contacts']['contact_no'] = $hotel_contacts_contact_no;

            $hotel_list[] = $hotel;
        }

        $response = response()->json(['status' => true, 'message' => 'Hotels Found', 'login_user_hotel_data' => $hotel_list]);
    } else {
        $response = response()->json(['status' => false, 'message' => 'Hotels Not Found', 'data' => []]);
    }

    return $response;
}






//     return response()->json(['status' => true,'login_user_hotel_data'=>$data]);


// }


public function sort_order_ameties(Request $request)
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

        $hotel_amenity_sort_data = HotelAmetiesModel::where('id', $key)->first();

        if (!$hotel_amenity_sort_data) {
            $response['message'] = 'Amenity not found';
        } else {
            $hotel_amenity_sort_data->sort_order = $requestData['sort_order'];
            $hotel_amenity_sort_data->save();

            if ($hotel_amenity_sort_data) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Ameties Sort Order Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update hotel ameties sort order!']);
            }
        }
    
    }
    return $response;
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


// public function update_single_hotel_image(Request $request)
// {

//     // echo "rtrt";

//     $response = array("status" => false, 'message' => '');

//     $rules = [
//         'hotel_id' => 'required',
//         'key' => 'required',
//         'hotel_image' => 'required',
        
//     ];

//      $requestData = $request->all();

//     $validator = Validator::make($requestData, $rules);

//     // $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $response['message'] = $validator->messages();
//     } else {

//     $hotel_id = $requestData['hotel_id'];
//     $key = $requestData['key'];
    


//     $hotel_data = HotelModel::find($hotel_id);
//     // dd($hotel_data);




//     if($hotel_data){
//         $hotel_images = json_decode($hotel_data['hotel_images'], true);
//         // dd($hotel_images);


//         if (array_key_exists($key, $hotel_images)) {
     
//             // Remove the file using the provided key
//             unset($hotel_images[$key]);
//             // Reindex array keys
//             $hotel_images = array_values($hotel_images);
//             // dd($hotel_images);

//             // Update the 'file_pdf' array in the database
//             $hotel_data->hotel_images = json_encode($hotel_images);
         
//               if ($request->hasFile('hotel_images')) {
//                 $images = $request->file('hotel_images');
//                 // dd($images);
                
//               }
//               $hotel_data->hotel_images = json_encode($hotel_images);
//                 $hotel_data->save();

            

//             $response['status'] = true;
//             $response['message'] = 'Hotel Image Updated successfully.';
//         } else {
//             $response['message'] = 'Invalid key provided.';
//         }

     

//             //  if ($request->hasFile('hotel_images')) {
//             //     $images = $request->file('hotel_images');
//             //     dd($images);

            
//             //     $imagePaths = [];
            
//             //     foreach ($images as $image) {
//             //         $imagePath = $image->store('uploads');
//             //         $imagePaths[] = $imagePath;
//             //     }
            
//             //     $hotel_data->hotel_images = json_encode($imagePaths);
//             // }


//     }


//     }
//     return response()->json($response);
// }
public function update_single_hotel_image(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'hotel_id' => 'required',
        'key' => 'required',
        'hotel_image' => 'required',
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $hotel_id = $requestData['hotel_id'];
        $key = $requestData['key'];

        $hotel_data = HotelModel::find($hotel_id);

        if ($hotel_data) {
            $hotel_images = json_decode($hotel_data['hotel_images'], true);

            if (array_key_exists($key, $hotel_images)) {
                // Handle image update logic here
                if ($request->hasFile('hotel_image')) {
                    $image = $request->file('hotel_image');
                    $imagePath = $image->store('uploads');

                    // Update the image path for the specified key
                    $hotel_images[$key] = $imagePath;

                    // Update the 'hotel_images' array in the database
                    $hotel_data->hotel_images = json_encode($hotel_images);
                    $hotel_data->save();

                    $response['status'] = true;
                    $response['message'] = 'Hotel Image Updated successfully.';
                } else {
                    $response['message'] = 'Please provide a valid image file.';
                }
            } else {
                $response['message'] = 'Invalid key provided.';
            }
        }
    }

    return response()->json($response);
}

public function add_multiple_images_hotels(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'hotel_id' => 'required',
        'hotel_image' => 'required',
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $hotel_id = $requestData['hotel_id'];
   
        $hotel_data = HotelModel::find($hotel_id);
  


        if ($hotel_data) {
            // $hotel_images = json_decode($hotel_data['hotel_images'], true);
            $currentImages = json_decode($hotel_data->hotel_images, true) ?? [];
      
            if ($request->hasFile('hotel_image')) {
                $images = $request->file('hotel_image');
             

                $imagePaths = [];
            
                foreach ($images as $image) {
                    $imagePath = $image->store('uploads');
                    $imagePaths[] = $imagePath;
                }

                $updatedImages = array_merge($currentImages, $imagePaths);
                $hotel_data->hotel_images = json_encode($updatedImages);
                // $news_data->news_image = json_encode($updatedImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                // dd($hotel_data->hotel_images);

            }
            $hotel_data->save();
            $response = response()->json(['status' => true, 'message' => 'Hotel Image Added Successfully!']);
        }
        else {
                $response = response()->json(['status' => false, 'message' => 'Failed to upload hotel images!']);
            }


        // if ($hotel_data) {
        //     // $hotel_images = json_decode($hotel_data['hotel_images'], true);
        //     if ($request->hasFile('hotel_images')) {
        //         $images = $request->file('hotel_images');
        //         // dd($images);

        //         $imagePaths = [];
            
        //         foreach ($images as $image) {

        //             $imagePath = $image->store('uploads');
        //             $imagePaths[] = $imagePath;
                  
        //         }

        //         $hotel_data->hotel_images = json_encode($imagePaths);
        //         // dd($hotel_data->hotel_images);

        //     }
        //     $hotel_data->save();
        //     $response = response()->json(['status' => true, 'message' => 'Hotel Images Successfully!']);
        // }
        // else {
        //     $response = response()->json(['status' => false, 'message' => 'Failed to upload images!']);
        // }

       
}
return $response;


}

public function Create_Review_Category(Request $request)
{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'title' => 'required|unique:review_categories',
        'max_rating' => 'required',
    ];

    $requestData = $request->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $review_category = new Review_Category_Model();
        $review_category->title = $requestData['title'];
        $review_category->max_rating = $requestData['max_rating'];

        $last = Review_Category_Model::orderBy('sort_order','desc');
        if($last->count() > 0){
            $last = $last->first();
            $last->sort_order++;

            $review_category->sort_order = $last->sort_order;
        }else{
            $review_category->sort_order = 1;
        }

        $review_category->save();
        

        if ($review_category) {
            $response = response()->json(['status' => true, 'message' => 'Review Category Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add review category!']);
        }
    }

    return $response;
}

public function Create_Review_Topic(Request $request)
{

$response = array("status" => false, 'message' => '');

$rules = [
    'title' => 'required|unique:review_topics',
];

$messages = ['unique' => "this topic has already exists."];

$requestData = $request->all();

$validator = Validator::make($request->all(), $rules, $messages);

if ($validator->fails()) {
    $response['message'] = $validator->messages();
} else {
    $review_topics = new Review_Topics_Model();
    $review_topics->title = $requestData['title'];
    $last = Review_Topics_Model::orderBy('sort_order','desc');
    if($last->count() > 0){
        $last = $last->first();
        $last->sort_order++;

        $review_topics->sort_order = $last->sort_order;
    }else{
        $review_topics->sort_order = 1;
    }

    $review_topics->save();
    

    if ($review_topics) {
        $response = response()->json(['status' => true, 'message' => 'Review Topic Added Successfully']);
    } else {
        $response = response()->json(['status' => false, 'message' => 'Failed to add review topic!']);
    }
}

return $response;
}


public function All_Review_Categories()
{

$data = Review_Category_Model::orderBy('id','DESC')->get();

if ($data->isEmpty()) {
    return response()->json(['status' => false, 'message' => 'No categories found']);
}
    
return response()->json(['status' => true, 'data' => $data]);
}

public function All_Review_Topics(){

$data = Review_Topics_Model::orderBy('id','DESC')->get();

if ($data->isEmpty()) {
    return response()->json(['status' => false, 'message' => 'No topics found']);
}
    
return response()->json(['status' => true, 'data' => $data]);
}

public function Create_Guest_Review(Request $request){
$response = array("status" => false, 'message' => '');

$rules = [
    'hotel_id' => 'required',
    'category_id' => 'required',
    'rating' => 'required',
    'name' => 'required',
    'email' => 'required',
    'description' => 'required',
];

$requestData = $request->all();
// dd($requestData);


$validator = Validator::make($request->all(), $rules);

if ($validator->fails()) {
    $response['message'] = $validator->messages();
} else {
    $guest_review = new Guest_Review_Model();

    $guest_reviews = [];

    foreach ($requestData['category_id'] as $key => $categoryId) {
        // dd($key);
        $guest_reviews[] = [
            'category_id' => $categoryId,
            'rating' => $requestData['rating'][$key],
        ];
    }
    // dd($guest_reviews);

    $guest_review->hotel_id = $requestData['hotel_id'];
    $guest_review->type = 1;
    $guest_review->category_rating = json_encode($guest_reviews);
    $guest_review->name = $requestData['name'];
    $guest_review->email = $requestData['email'];
    $guest_review->description = $requestData['description'];

    $guest_review->save();
    
    if ($guest_review) {
        $response = response()->json(['status' => true, 'message' => 'Guest Review Added Successfully']);
    } else {
        $response = response()->json(['status' => false, 'message' => 'Failed to add guest review!']);
    }
}

return $response;
}

// public function Get_Reviews_By_Topics(Request $request)
// {

// $response = array("status" => false, 'message' => '');

// $rules = [
//    'hotel_id' => 'required',
// ];

// $requestData = $request->all();


// $validator = Validator::make($request->all(), $rules);

// if ($validator->fails()) {
//    $response['message'] = $validator->messages();
// } else {
    
//     if(!empty($requestData) && !empty($requestData['topic_title'])){
//         $topicTitles = $requestData['topic_title'];
    
//         $data = Guest_Review_Model::
//             where(function($query) use ($topicTitles) {
//             foreach ($topicTitles as $title) {
//                 $query->orWhere('description', 'LIKE', '%' . $title . '%');
//             }
//             $query->orWhere('hotel_id', '=', $requestData['hotel_id']);
//         })
//         ->orderBy('id', 'DESC')
//         ->get();

//         if($data->isEmpty()){
//             $all_reviews_data = Guest_Review_Model::where('hotel_id',$requestData['hotel_id'])->orderBy('id','DESC')->get();
//             if($all_reviews_data->isEmpty()){
//                 $response = json_encode(['status'=>false,"message"=>"Reviews Not Found","data"=>[]]);
//             }
//             else{
//                 $response = json_encode(['status'=>true,"message"=>"Reviews Found","data"=>$all_reviews_data]);
//             }
            
//         }
//         else{
//             $response = json_encode(['status'=>true,"message"=>"Reviews Found","data"=>$data]);
//         }
//   }
//   else{
//       $all_reviews_data = Guest_Review_Model::where('hotel_id',$requestData['hotel_id'])->orderBy('id','DESC')->get();
//         if($all_reviews_data->isEmpty()){
//             $response = json_encode(['status'=>false,"message"=>"Reviews Not Found","data"=>[]]);
//         }
//         else{
//             $response = json_encode(['status'=>true,"message"=>"Reviews Found","data"=>$all_reviews_data]);
//         }
//   }
// }

// return $response;
// }
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

        $hotel_data->transform(function ($item) {
          
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
    
        return response()->json(['hotel_data' => $hotel_data]);
    
    

        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function Get_Reviews_By_Topics(Request $request)
{
    try {
        $response = array("status" => false, 'message' => '');
        $rules = [
            'hotel_id' => 'required',
        ];

        $requestData = $request->all();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            if (!empty($requestData['hotel_id']) && !empty($requestData['topic_title'])) {
                $topicTitles = $requestData['topic_title'];
                $hotel_id = $requestData['hotel_id'];
                

                $data = Guest_Review_Model::where(function ($query) use ($topicTitles,$hotel_id) {
                    foreach ($topicTitles as $title) {
                        $query->orWhere('description', 'LIKE', '%' . $title . '%');
                    }
                    $query->Where('hotel_id', '=', $hotel_id);
                })
                
                    ->orderBy('id', 'DESC')
                    ->get();
                    // Fetch the results
                   

                if ($data->isEmpty()) {
                    $all_reviews_data = Guest_Review_Model::where('hotel_id', $requestData['hotel_id'])->orderBy('id', 'DESC')->get();
                    if ($all_reviews_data->isEmpty()) {
                        $response = json_encode(['status' => false, "message" => "Reviews Not Found", "data" => []]);
                    } else {
                        $response = json_encode(['status' => true, "message" => "Reviews Found", "data" => $all_reviews_data]);
                    }
                } else {
                    $response = json_encode(['status' => true, "message" => "Reviews Found", "data" => $data]);
                }
            } else {
                $all_reviews_data = Guest_Review_Model::where('hotel_id', $requestData['hotel_id'])->orderBy('id', 'DESC')->get();
                if ($all_reviews_data->isEmpty()) {
                    $response = json_encode(['status' => false, "message" => "Reviews Not Found", "data" => []]);
                } else {
                    $response = json_encode(['status' => true, "message" => "Reviews Found", "data" => $all_reviews_data]);
                }
            }
        }

        return $response;
    } catch (Exception $e) {
        // Handle exception
        return json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}

public function get_review_average(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all();
    
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $category_id = $requestData['category_id'];
            $category_data = Guest_Review_Model::whereJsonContains('category_rating', [['category_id' => $category_id]])->get();
    
            if ($category_data->isNotEmpty()) {
                $totalRating = 0;
                $ratingCount = 0;
    
                foreach ($category_data as $review) {
                    $ratings = json_decode($review->category_rating, true); // decode JSON string to array
                    foreach ($ratings as $rating) {
                        if (isset($rating['rating'])) {
                            $totalRating += (int)$rating['rating']; // sum up ratings
                            $ratingCount++; // increment count of ratings
                        }
                    }
                }
    
                if ($ratingCount > 0) {
                    // dd($totalRating);
                    // dd($ratingCount);
                    $averageRating = $totalRating / $ratingCount; // calculate average rating
                    $response['status'] = true;
                    $response['average_rating'] = $averageRating;
                    $response['message'] = 'Rating get successfully!';
                } else {
                    $response['message'] = 'No ratings found for the given category.';
                }
            } else {
                $response['message'] = 'No reviews found for the given category.';
            }
        }
    
        return $response;
    }

}
