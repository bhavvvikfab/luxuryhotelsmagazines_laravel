<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\User;
use App\Models\HotelSpecialOfferModel;
use Illuminate\Support\Facades\Auth;

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
                $item->fullImagePath = asset("storage/app/".$item->news_image);
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
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                'news_image' => 'required',
                'status' => 'required',
                'catagory' => 'required',
                'editor_choice' => 'required',
                'phone_number' => 'required',
                'news_views' => 'required',
                'news_likes' => 'required',
                'youtube_link' => 'required',
                'special_offers' => 'required',
                'offer_title' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'redeem_link' => 'required',
                'contact_no' => 'required',
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
                $news->bussiness_name = $requestData['bussiness_name'];
                $news->country = $requestData['country'];
                $news->full_name = $requestData['full_name'];
                $news->email_address = $requestData['email_address'];
                $news->news_title = $requestData['news_title'];
                $news->news_desc = $requestData['news_desc'];
                // $news->news_image = $requestData['news_image'];

                // if ($request->hasFile('news_image')) {
                //     $image = $request->file('news_image');
                //     $imageName = time().'.'.$image->getClientOriginalExtension();
                //     $image->storeAs('news_images', $imageName, 'public'); 
                //     $news->news_image = $imageName;
                // }

                if ($request->hasFile('news_image')) {
                    $news->news_image = $request->file('news_image')->store('uploads');
                }
                if ($request->hasFile('youtube_shorts')) {
                    $news->youtube_shorts = $request->file('youtube_shorts')->store('uploads');
                }
                
                $news->status = $requestData['status'];
                $news->catagory = $requestData['catagory'];
                $news->editor_choice = $requestData['editor_choice'];
                // $news->phone_number = $requestData['phone_number'];
                $news->news_views = $requestData['news_views'];
                $news->news_likes = $requestData['news_likes'];
                $news->youtube_link = $requestData['youtube_link'];
                $news->special_offers = $requestData['special_offers'];
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
                $HotelSpecialOfferModel->redeem_link = $requestData['redeem_link'];
                
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
                    $newsData['news_image'] = asset('storage/app/'.$newsData['news_image']);
                    $data = $newsData;
        
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
                'news_id' => 'required',
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                'news_image' => 'required',
                'status' => 'required',
                'catagory' => 'required',
                'editor_choice' => 'required',
                'phone_number' => 'required',
                'news_views' => 'required',
                'news_likes' => 'required',
                'youtube_link' => 'required',
                'special_offers' => 'required',
                'offer_title' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'redeem_link' => 'required',
                'contact_no' => 'required',
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
                    $news->bussiness_name = $requestData['bussiness_name'];
                    $news->country = $requestData['country'];
                    $news->full_name = $requestData['full_name'];
                    $news->email_address = $requestData['email_address'];
                    $news->news_title = $requestData['news_title'];
                    $news->news_desc = $requestData['news_desc'];

                    // $news->news_image = $requestData['news_image'];
                    if ($request->hasFile('news_image')) {
                        $news->news_image = $request->file('news_image')->store('uploads');
                    }
                    if ($request->hasFile('youtube_shorts')) {
                        $news->youtube_shorts = $request->file('youtube_shorts')->store('uploads');
                    }
                    
                    $news->status = $requestData['status'];
                    $news->catagory = $requestData['catagory'];
                    $news->editor_choice = $requestData['editor_choice'];
                    // $news->phone_number = $requestData['phone_number'];
                    $news->news_views = $requestData['news_views'];
                    $news->news_likes = $requestData['news_likes'];
                    $news->youtube_link = $requestData['youtube_link'];
                    $news->special_offers = $requestData['special_offers'];
                    
                    
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
                    $HotelSpecialOfferModel->redeem_link = $requestData['redeem_link'];
                    $HotelSpecialOfferModel->save();
        
                    $response = response()->json(['status' => true, 'message' => 'News Updated Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'News not found']);
                }
            }

              return $response;
    }

    
     public function ViewNews(Request $request)
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
                    $newsData['news_image'] = asset('storage/app/'.$newsData['news_image']);
                    $data = $newsData;
        
                    $response = response()->json(['status' => true, 'message' => 'News Data Found', 'data' => $data]);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'News Data Not Found','data'=>$data]);
                }
            }

              return $response;
    }

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
}
