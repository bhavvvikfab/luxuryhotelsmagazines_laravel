<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\User;
use App\Models\SubscribersModel;
use App\Models\HotelSpecialOfferModel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;

class NewsController extends Controller
{

    public function index($type=null)
    {
        $data = News::with('special_offer');
        if(isset($type) && !empty($type)){
            $data->whereHas('special_offer', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }
        $result = $data->get();

        if (empty($result)) {
            return response()->json(["status" => false, "message" => "News Data Not Found", "data" => []]);
        } else {
            $result->transform(function ($item) {
                // Convert news images to full URLs
                
                if (!empty($item->news_image)) {
                   
                    $imagePaths = json_decode($item->news_image, true);
                   
                    $fullImagePaths = [];
                    foreach ($imagePaths as $image) {
                        $fullImagePaths[] = asset("storage/app/".$image);
                    }
    
                    $item->news_image = $fullImagePaths;
                } else {
                    $item->news_image = [];
                }
    
                return $item;
            });
            return response()->json(["status" => true, "message" => "News Data Found", "data" => $result]);
        }
    }
    
    // 1 = no 
    // 2 = display for 1 week(+10 euro)
    // 3 = display for 1 month(+25 euro)
    // 4 = display for 2 month(+40 euro)
    // 5 = display for 3 month(+50 euro)

    public function CreateNews(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $user = Auth::guard('api')->user();

        if(isset($request['user_id']) && !empty($request['user_id']))
        {
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
                'news_type' => 'required',
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                'news_images' => 'required',
                // 'status' => 'required',
                'catagory' => 'required',
                'editor_choice' => 'required',
                // 'news_views' => 'required',
                // 'news_likes' => 'required',
                'youtube_link' => 'nullable',
                'offer_title' => 'required',
                'phone_number' => 'required',
                'contact_no' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'special_offers' => 'required',
                'redeem_link' => 'required',
                
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                // if (User::where('email', $requestData['email'])->exists()) {
                //     return response()->json(['status' => false,'message' => 'Email already exists']);
                //     exit;
                // }

                $news = new News();
                $news->user_id = $user['id'];
                // $news->user_id = $requestData['user_id'];
                $news->news_type = $requestData['news_type'];
                $news->bussiness_name = $requestData['bussiness_name'];
                $news->country = $requestData['country'];
                $news->full_name = $requestData['full_name'];
                $news->email_address = $requestData['email_address'];
                $news->news_title = $requestData['news_title'];
                $news->news_desc = $requestData['news_desc'];
                $news->editor_choice = $requestData['editor_choice'];
                
                // $news->news_image = $requestData['news_image'];

                // if ($request->hasFile('news_image')) {
                //     $image = $request->file('news_image');
                //     $imageName = time().'.'.$image->getClientOriginalExtension();
                //     $image->storeAs('news_images', $imageName, 'public'); 
                //     $news->news_image = $imageName;
                // }

                // if ($request->hasFile('news_images')) {
                //     $news->news_image = $request->file('news_image')->store('uploads');
                // }
                if ($request->hasFile('news_images')) {
                    $images = $request->file('news_images');
                
                    $imagePaths = [];
                
                    foreach ($images as $image) {
                        $imagePath = $image->store('uploads');
                        $imagePaths[] = $imagePath;
                    }
                
                    $news->news_image = json_encode($imagePaths);
                }

                // if ($request->hasFile('youtube_shorts')) {
                //     $news->youtube_shorts = $request->file('youtube_shorts')->store('uploads');
                // }
                
                // $news->status = $requestData['status'];
                $news->catagory = $requestData['catagory'];
              //  $news->editor_choice = $requestData['editor_choice'];
                // $news->phone_number = $requestData['phone_number'];
                // $news->news_views = $requestData['news_views'];
                // $news->news_likes = $requestData['news_likes'];
                if(!empty($requestData['youtube_link'])){
                    $news->youtube_link = $requestData['youtube_link'];
                }
                else{
                    $news->youtube_link = NULL;
                }
               
                // $news->special_offers = $requestData['special_offers'];
                $news->save();

                $lastInsertId = $news->id;
                $HotelSpecialOfferModel = new HotelSpecialOfferModel();
				
                $HotelSpecialOfferModel->news_id = $lastInsertId;
                $HotelSpecialOfferModel->type = 2;
                $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
                $HotelSpecialOfferModel->phone_number = $requestData['phone_number'];
                $HotelSpecialOfferModel->contact_no = $requestData['contact_no'];
                $HotelSpecialOfferModel->from_date = $requestData['from_date'];
                $HotelSpecialOfferModel->to_date = $requestData['to_date'];
                $HotelSpecialOfferModel->description = $requestData['description'];
                $HotelSpecialOfferModel->special_offer = $requestData['special_offers'];
                $HotelSpecialOfferModel->reedem_link = $requestData['redeem_link'];
               

                $HotelSpecialOfferModel->save();
				
                if ($news && $HotelSpecialOfferModel) {
                    $response =  response()->json(['status' => true,'message' => 'News Created Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create news']);
                }
        
          }

              return $response;
    }


    public function EditNews(Request $request)
    {
        $response = array("status"=>false,'message' => '');
        $rules = [
                'news_id' => 'required'
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        $data = [];

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                $newsId = $requestData['news_id'];

                
                $newsData = News::with('special_offer')->find($newsId);
                if ($newsData) {
                    // $newsData['news_image'] = asset('storage/app/'.$newsData['news_image']);
                    // $data = $newsData;
                    $newsImages = [];
                    
                    if(!empty($newsData['news_image'])){
                        $news_images = json_decode($newsData['news_image']);
                        
                        foreach ($news_images as $image) {
                            $newsImages[] = asset('storage/app/'.$image);
                        }
                      
                        $newsData['news_image'] = $newsImages;

                        $data = $newsData;
                    }
                        
                    $response = response()->json(['status' => true, 'message' => 'News Data Found', 'data' => $data]);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'News Data Not Found','data'=>$data]);
                }
            }

              return $response;
    }
    
    public function UpdateNews(Request $request)
    {

       
        $response = array("status" => false, 'message' => '');
        $user = Auth::guard('api')->user();

        if(isset($request['user_id']) && !empty($request['user_id']))
        {
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

            $rules = [
                'news_type' => 'required',
                'news_id' => 'required',
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                'news_images' => 'required',
                //'status' => 'required',
                'catagory' => 'required',
                'editor_choice' => 'required',
                'news_views' => 'required',
                'news_likes' => 'required',
                'youtube_link' => 'nullable',
                'offer_title' => 'required',
                'phone_number' => 'required',
                'contact_no' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'special_offers' => 'required',
                'redeem_link' => 'required',
               
            ];


            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);
        
                if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                }else {
                    $newsId = $requestData['news_id'];
                    $news = News::find($newsId);
            
                    if ($news) {
                    
                    $news->user_id = $user['id'];
                    $news->news_type = $requestData['news_type'];
                    $news->bussiness_name = $requestData['bussiness_name'];
                    $news->country = $requestData['country'];
                    $news->full_name = $requestData['full_name'];
                    $news->email_address = $requestData['email_address'];
                    $news->news_title = $requestData['news_title'];
                    $news->news_desc = $requestData['news_desc'];


                    if ($request->hasFile('news_images')) {
                        $images = $request->file('news_images');
                    
                        $imagePaths = [];
                    
                        foreach ($images as $image) {
                            $imagePath = $image->store('uploads');
                            $imagePaths[] = $imagePath;
                        }
                    
                        $news->news_image = json_encode($imagePaths);
                    }

                    // $news->news_image = $requestData['news_image'];
                    // if ($request->hasFile('news_image')) {
                    //     $news->news_image = $request->file('news_image')->store('uploads');
                    // }
                    // if ($request->hasFile('youtube_shorts')) {
                    //     $news->youtube_shorts = $request->file('youtube_shorts')->store('uploads');
                    // }
                    
                    //$news->status = $requestData['status'];
                    $news->catagory = $requestData['catagory'];
                    $news->editor_choice = $requestData['editor_choice'];
                    // $news->phone_number = $requestData['phone_number'];
                    $news->news_views = $requestData['news_views'];
                    $news->news_likes = $requestData['news_likes'];
                    
                    if(!empty($requestData['youtube_shorts'])){
                        $news->youtube_shorts = $requestData['youtube_shorts'];
                    }
                    else{
                        $news->youtube_shorts = NULL;
                    }

                    if(!empty($requestData['youtube_link'])){
                        $news->youtube_link = $requestData['youtube_link'];
                    }
                    else{
                        $news->youtube_link = NULL;
                    }

                    // $news->special_offers = $requestData['special_offers'];
                    
                    
                    $news->save();
        
                   
                    $HotelSpecialOfferModel = HotelSpecialOfferModel::where('news_id', $newsId)->first();
        
                    if (!$HotelSpecialOfferModel) {
                        $HotelSpecialOfferModel = new HotelSpecialOfferModel();
                        $HotelSpecialOfferModel->news_id = $newsId;
                    }
        
                    $HotelSpecialOfferModel->type = 2;
                    $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
                    $HotelSpecialOfferModel->phone_number = $requestData['phone_number'];
                    $HotelSpecialOfferModel->contact_no = $requestData['contact_no'];
                    $HotelSpecialOfferModel->from_date = $requestData['from_date'];
                    $HotelSpecialOfferModel->to_date = $requestData['to_date'];
                    $HotelSpecialOfferModel->description = $requestData['description'];
                    $HotelSpecialOfferModel->special_offer = $requestData['special_offers'];
                    $HotelSpecialOfferModel->reedem_link = $requestData['redeem_link'];
                    
                    $HotelSpecialOfferModel->save();
        
                    $response = response()->json(['status' => true, 'message' => 'News Updated Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'News not found']);
                }
            }

              return $response;
    }

    
    //  public function ViewNews(Request $request)
    //  {
        
    //     $response = array("status"=>false,'message' => '');
    //     $rules = [
    //             'news_id' => 'required'
    //         ];

    //     $requestData = $request->all();
    //     $validator = Validator::make($requestData, $rules);
    //     $data = [];

    //         if ($validator->fails()) {
    //             $response['message'] = $validator->messages();
    //           } else {
    //             $newsId = $requestData['news_id'];

                
    //             $newsData = News::with('special_offer')->find($newsId);
    //             if ($newsData) {
    //                 $newsData['news_image'] = asset('storage/app/'.$newsData['news_image']);
    //                 $data = $newsData;
        
    //                 $response = response()->json(['status' => true, 'message' => 'News Data Found', 'data' => $data]);
    //             } else {
    //                 $response = response()->json(['status' => false, 'message' => 'News Data Not Found','data'=>$data]);
    //             }
    //         }

    //           return $response;
    // }

    public function DeleteNews(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'news_id' => 'required',
        ];
    
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $news_id = $requestData['news_id'];
    
    
        $news_data = News::with('special_offer')->find($news_id);
       
            if (!$news_data) {
                return response()->json(['status'=>false,'message'=>'News not found'], 404);
            }
    
            $news_data->special_offer()->delete();
    
            $news_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'News Deleted Successfully!']);
    
        
        }
    
        return $response;
    }

    
            public function update_single_news_image(Request $request)
         {
            $response = array("status" => false, 'message' => '');

            $rules = [
                'news_id' => 'required',
                'key' => 'required',
                'news_image' => 'required',
            ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {
                $news_id = $requestData['news_id'];
                $key = $requestData['key'];

                $news_data = News::find($news_id);

                if ($news_data) {
                    $news_images = json_decode($news_data['news_image'], true);

                    if (array_key_exists($key, $news_images)) {
                        // Handle image update logic here
                        if ($request->hasFile('news_image')) {
                            $image = $request->file('news_image');
                            $imagePath = $image->store('uploads');

                            // Update the image path for the specified key
                            $news_images[$key] = $imagePath;

                            // Update the 'hotel_images' array in the database
                            $news_data->news_image = json_encode($news_images);
                            $news_data->save();

                            $response['status'] = true;
                            $response['message'] = 'News Image Updated successfully.';
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
        public function delete_single_news_image(Request $request)
        {

            $response = array("status" => false, 'message' => '');

            $rules = [
                'news_id' => 'required',
                'key' => 'required'
                
            ];

            $requestData = $request->all();

            $validator = Validator::make($requestData, $rules);

            // $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

            $news_id = $requestData['news_id'];
            $key = $requestData['key'];


            $news_data = News::find($news_id);
            
            
                if ($news_data) {
                    $news_images = json_decode($news_data['news_image'], true);
                
                if (array_key_exists($key, $news_images)) {
            
                    // Remove the file using the provided key
                    unset($news_images[$key]);
                    // Reindex array keys
                    $news_images = array_values($news_images);
                    // dd($hotel_images);

                    // Update the 'file_pdf' array in the database
                    $news_data->news_image = json_encode($news_images);
                    $news_data->save();

                    $response['status'] = true;
                    $response['message'] = 'News Image deleted successfully.';
                } else {
                    $response['message'] = 'Invalid key provided.';
                }
            }

            
            }
            return response()->json($response);
        
        }

        public function add_multiple_images_news(Request $request)
        {
            $response = array("status" => false, 'message' => '');

            $rules = [
                'news_id' => 'required',
                'news_image' => 'required',
            ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

                $news_id = $requestData['news_id'];
        
                $news_data = News::find($news_id);
                // dd($hotel_data);


                if ($news_data) {
                    // $hotel_images = json_decode($hotel_data['hotel_images'], true);
                    $currentImages = json_decode($news_data->news_image, true) ?? [];
                    if ($request->hasFile('news_image')) {
                        $images = $request->file('news_image');
                        // dd($images);

                        $imagePaths = [];
                    
                        foreach ($images as $image) {
                            $imagePath = $image->store('uploads');
                            $imagePaths[] = $imagePath;
                        }

                        $updatedImages = array_merge($currentImages, $imagePaths);
                        $news_data->news_image = json_encode($updatedImages);
                        // $news_data->news_image = json_encode($updatedImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        // dd($hotel_data->hotel_images);

                    }
                    $news_data->save();
                    $response = response()->json(['status' => true, 'message' => 'News Images Added Successfully!']);
                }
                else {
                    $response = response()->json(['status' => false, 'message' => 'Failed to upload images!']);
                }

            
        }
        return $response;


        }

        public function CreateNewsLetter(Request $request){
            $response = array("status" => false, 'message' => '');

            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:subscribers,email' 
            ];

            $messages = [
                'email.unique' => 'This user has already subscribed to the newsletter.'
            ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules, $messages);
           
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
            } else {

                $SubscribersModel = new SubscribersModel();
    
               
                $SubscribersModel->name = $request->input('name');
                $SubscribersModel->email = $request->input('email');
               
                $SubscribersModel->save();
    
                $subject = 'New Subscriber';
               
                $body = "Subscriber Details!\nName: {$request->input('name')}\nEmail: {$request->input('email')}";
    
                // Email data
                $to = $request->input('email');
                $key=5;

                $data = [
                    'name' => $request->input('name'),
                    'email' => $to,
                    'key' => $key
                ];

               
                // Use the helper function to send the email
                $helpers = new Helpers();
                $result = $helpers->sendEmail($to, $subject, $body, $key, $data);
  
                if ($result) {
                    $response = response()->json(['status' => true, 'message' => 'Subscriber Created Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Failed to create subscriber']);
                }
             
            }

            return $response;
        }

        public function All_Newsletter(){
            $subscribers = SubscribersModel::orderBy('id','DESC')->get();

            if ($subscribers->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No subscribers found']);
            }
        
            return response()->json(['status' => true, 'data' => $subscribers]);
        }
}
