<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\HotelSpecialOfferModel;
class NewsController extends Controller
{

    public function index($type=null){
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
    
    public function CreateNews(Request $request){

        $response = array("status"=>false,'message' => '');
        $rules = [
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                'news_image' => 'required',
                'youtube_link' => 'required',
                'offer_title' => 'required',
                'contact_no' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'redeem_link' => 'required'
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
                $news->user_id = $requestData['user_id'];
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
                
                $news->youtube_link = $requestData['youtube_link'];
                $news->save();

                $lastInsertId = $news->id;
                $HotelSpecialOfferModel = new HotelSpecialOfferModel();
				
                $HotelSpecialOfferModel->news_id = $lastInsertId;
                $HotelSpecialOfferModel->type = 2;
                $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
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

    public function EditNews(Request $request){
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
    
    public function UpdateNews(Request $request){

        $response = array("status"=>false,'message' => '');
        $rules = [
                'news_id' => 'required',
                'bussiness_name' => 'required',
                'country' => 'required',
                'full_name' => 'required',
                'email_address' => 'required|email',
                'news_title' => 'required',
                'news_desc' => 'required',
                // 'news_image' => 'required',
                'youtube_link' => 'required',
                'offer_title' => 'required',
                'contact_no' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'description' => 'required',
                'redeem_link' => 'required'
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              }else {
                $newsId = $requestData['news_id'];
                $news = News::find($newsId);
        
                if ($news) {
                    
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
                    
                    $news->youtube_link = $requestData['youtube_link'];
                    $news->save();
        
                   
                    $HotelSpecialOfferModel = HotelSpecialOfferModel::where('news_id', $newsId)->first();
        
                    if (!$HotelSpecialOfferModel) {
                        $HotelSpecialOfferModel = new HotelSpecialOfferModel();
                        $HotelSpecialOfferModel->news_id = $newsId;
                    }
        
                    $HotelSpecialOfferModel->type = 2;
                    $HotelSpecialOfferModel->offer_title = $requestData['offer_title'];
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

    
     public function ViewNews(Request $request){
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
}
