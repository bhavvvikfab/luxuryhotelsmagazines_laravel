<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReviewModel;



class ReviewController extends Controller
{
    public function index($type=null)
    {
        $query = ReviewModel::query();

        if (isset($type) && !empty($type)) {
            $query->where('review_type', $type);
        }
        
        $query->orderBy('id', 'desc');
        $result = $query->get();
    
        if (empty($result)) {
            return response()->json(["status" => false, "message" => "Reviews Data Not Found", "data" => []]);
        } else {
            return response()->json(["status" => true, "message" => "Reviews Data Found", "data" => $result]);
        }
    }
    
    public function CreateReview(Request $request)
    {

        $response = array("status"=>false,'message' => '');
        $rules = [
                'review_type' => 'required',
                'full_name' => 'required',
                'email' => 'required|email',
                'review' => 'required'
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
                
                $review = new ReviewModel();
                $review->review_type = $requestData['review_type'];
                $review->full_name = $requestData['full_name'];
                $review->email = $requestData['email'];
                $review->review = $requestData['review'];
                $review->save();

				if ($review) {
                    $response =  response()->json(['status' => true,'message' => 'Review Created Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create review']);
                }
        
              }

              return $response;
    }

    public function DeleteReview(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');

        $validator = Validator::make($request->all(), [
            'review_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        }
        else{
            $review_id = $request['review_id'];
            $review_data = ReviewModel::find($review_id);
        
            if ($review_data) {
                $review_data->delete();
                $response = response()->json(["status" => true, "message" => "Review deleted successfully"]);
            } else {
                $response = response()->json(["status" => false, "message" => "Review not found"]);
            }
        }
    
        return $response;
    }

    // public function get_review_average(Request $request)
    // {
    //     $response = array("status" => false, 'message' => '');
    //     $requestData = $request->all();

    //     $validator = Validator::make($request->all(), [
    //         'category_id' => 'required',
    //     ]);
    
    //     if ($validator->fails()) {
    //         $response['message'] = $validator->messages();
    //     }
    //     else{

    //         $category_id = $requestData['category_id'];
    //         $category_data = Guest_Review_Model::whereJsonContains('category_rating', [['category_id' => $category_id]])
    //             ->get();
            
    //           dd($category_data);
    //         if($category_data){
    //             foreach($category_data as  $key=>$val){
             
    //                 $cat_data = $val['category_rating'];
    //                 // dd($cat_data);

    //                 // dd(json_decode($cat_data));
    //                 $rating = json_decode($cat_data);
    //                  dd($rating);

                  
    //                 $dt = json_decode($rating['rating']);
    //                 dd($dt);

                    
    //             }
    //         }

    //     }
    //     return $response;


    // }
    

 

}
