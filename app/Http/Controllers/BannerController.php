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

use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // banner_type = 0 = animataed (.gif files )
    // banner_type = 1 = video (insert video link) 

    public function AddBanner(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all();
    
        $rules = [
            'business_name' => 'required',
            'business_link' => 'required',
            'email' => 'required|email',
            'title' => 'required',
            'banner_catagory' => 'required',
            'banner_type' => 'required',
        ];
    
        if(isset($requestData['banner_type']) && $requestData['banner_type'] == 1){
            $rules['you_tube_link'] = 'required';
            $rules['image'] = 'required|image'; // Assuming image should be an image file
        } else {
            $rules['image'] = 'required|mimes:gif'; 
        }
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $banner = new BannerModel();
    
            $banner->business_name = $requestData['business_name'];
            $banner->business_link = $requestData['business_link'];
            $banner->email = $requestData['email'];
            $banner->title = $requestData['title'];
            $banner->banner_catagory = $requestData['banner_catagory'];
            $banner->banner_type = $requestData['banner_type'];
    
            // Only set you_tube_link if banner_type is 1
            if (isset($requestData['you_tube_link']) && $requestData['banner_type'] == 1) {
            $banner->you_tube_link = $requestData['you_tube_link'];
            } else {
           $banner->you_tube_link = null;
           }
    
            if ($request->hasFile('image')) {
                $pdfFiles = $request->file('image');
                $path = $pdfFiles->store('uploads');
                $banner->image = $path;
            }
    
            $banner->save();
    
            if ($banner) {
                $response = response()->json(['status' => true, 'message' => 'Banner Created Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to create banner']);
            }
        }
    
        return $response;
    }
    


    public function AllBanner()
    {
        $data = BannerModel::all();
    
        $data->transform(function ($item) {

        $item->image = asset("storage/app/".$item->image);
       
        
            return $item;
        });
    
            return response()->json(['status' => true,'data'=>$data]);
    
}

public function EditBanner(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
        
        $rules = [
            'banner_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $banner_id = $request['banner_id'];
            $banner = BannerModel::find($banner_id);
    
            if ($banner) {
                if ($request->hasFile('image')) {
                    $banner->image = $request->file('image')->store('uploads');
                }

                $response['status'] = true;
                $response['message'] = $banner;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Banner not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }


      // banner_type = 0 = animataed (.gif files )
    // banner_type = 1 = video (insert video link) 
    
    public function UpdateBanner(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
      
        $rules = [
            'business_name' => 'required',
            'business_link' => 'required',
            'email' => 'required|email',
            'title' => 'required',
            'banner_catagory' => 'required',
            'banner_type' => 'required',
        ];
    
        if(isset($requestData['banner_type']) && $requestData['banner_type'] == 1){
            $rules['you_tube_link'] = 'required';
            $rules['image'] = 'required|image'; // Assuming image should be an image file
        } else {
            $rules['image'] = 'required|mimes:gif'; 
        }

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $banner_id = $request->input('banner_id');
            $banner = BannerModel::find($banner_id);
    
            if ($banner) {
                if ($request->hasFile('image')) {
                    // Assuming 'uploads' is the storage directory; adjust as needed.
                    $imagePath = $request->file('image')->store('uploads');
                    $banner->image = $imagePath;
                }
    
                $banner->business_name = $request->input('business_name');
                $banner->business_link = $request->input('business_link');
                $banner->email = $request->input('email');
                $banner->title = $request->input('title');
                $banner->banner_catagory = $request->input('banner_catagory');
                $banner->banner_type = $request->input('banner_type');
                if (isset($requestData['you_tube_link']) && $requestData['banner_type'] == 1) {
                    $banner->you_tube_link = $requestData['you_tube_link'];
                    } else {
                   $banner->you_tube_link = null;
                   }
            
                if ($banner->save()) {
                    $response = ['status' => true, 'message' => 'Banner Updated Successfully'];
                } else {
                    $response = ['status' => false, 'message' => 'Failed to update banner!'];
                }
            } else {
                $response['message'] = 'Banner not found!';
            }
        }
    
        return response()->json($response);
    }

    public function DeleteBanner(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'banner_id' => 'required',
        ];
    
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $banner_id = $requestData['banner_id'];
    
    
        $banner_data = BannerModel::find($banner_id);
       
            if (!$banner_data) {
                return response()->json(['status'=>false,'message'=>'Banner not found'], 404);
            }
    
          
            $banner_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Banner Deleted Successfully!']);
    
        
        }
    
        return $response;
    }
    
}
