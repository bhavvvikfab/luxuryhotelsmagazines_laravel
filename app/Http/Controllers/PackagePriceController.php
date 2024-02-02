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
use App\Models\QueriesModel;
use App\Models\TeamModel;
use App\Models\VotedHotelModel;
use App\Models\PropertiesModel;
use App\Models\PackagePriceModel;
use App\Models\PackageModel;




class PackagePriceController extends Controller
{
    public function AddPackageTitle(Request $request)

{
    $response = array("status" => false, 'message' => '');

    $rules = [
        'title' => 'required',
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $package_data = new PackageModel();

        $package_data->title = $requestData['title'];
        $package_data->save();
    
        if ($package_data) {
            $response =  response()->json(['status' => true, 'message' => 'Package Title Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add Package title']);
        }
    }

    return $response;
}

    public function AddPackagePrice(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');

        $rules = [
            'package_catagory' => 'required',
            'package_name' => 'required',
            'package_position' => 'required',
            'package_original_price' => 'required',
            'package_price' => 'nullable',
            'hotel_package_price' => 'nullable',
            'package_validity' => 'required',
            'package_validity_title' => 'required',
            'package_inner_title' => 'nullable',
            'package_inner_sub_title' => 'nullable',
            'package_inner_content' => 'nullable',
            'package_expiry_date' => 'required',
            'package_action' => 'required',
        ];

        $requestData = $request->all();
      

        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
            $package_data = new PackagePriceModel();
   
            $package_data->package_catagory = $requestData['package_catagory'];
            $package_data->package_name = $requestData['package_name'];
            $package_data->package_position = $requestData['package_position'];
            $package_data->package_original_price = $requestData['package_original_price'];
            $package_data->package_validity = $requestData['package_validity'];
            $package_data->package_validity_title = $requestData['package_validity_title'];
            $package_data->package_expiry_date = $requestData['package_expiry_date'];
            $package_data->package_action = $requestData['package_action'];

            if (isset($requestData['package_price'])) {
                $package_data->package_price = $requestData['package_price'];
            }
            if (isset($requestData['hotel_package_price'])) {
                $package_data->hotel_package_price = $requestData['hotel_package_price'];
            }
            if (isset($requestData['package_inner_title'])) {
                $package_data->package_inner_title = $requestData['package_inner_title'];
            }
            if (isset($requestData['package_inner_sub_title'])) {
                $package_data->package_inner_sub_title = $requestData['package_inner_sub_title'];
            }
            if (isset($requestData['package_inner_content'])) {
                $package_data->package_inner_content = $requestData['package_inner_content'];
            }
    
            $package_data->save();
    
            if ($package_data) {
                $response =  response()->json(['status' => true, 'message' => 'Package Added Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add Package']);
            }
        }
    
        return $response;
    }

    public function UpdatePackagePrice(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');

        $rules = [
            'package_catagory' => 'required',
            'package_name' => 'required',
            'package_position' => 'required',
            'package_original_price' => 'required',
            'package_price' => 'nullable',
            'hotel_package_price' => 'nullable',
            'package_validity' => 'required',
            'package_validity_title' => 'required',
            'package_inner_title' => 'nullable',
            'package_inner_sub_title' => 'nullable',
            'package_inner_content' => 'nullable',
            'package_expiry_date' => 'required',
            'package_action' => 'required',
        ];

        $requestData = $request->all();
      

        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {

            $package_price_id = $requestData['package_price_id'];
            $package_data = PackagePriceModel::find($package_price_id);
        

            if($package_data)
            {

            $package_data->package_catagory = $requestData['package_catagory'];
            $package_data->package_name = $requestData['package_name'];
            $package_data->package_position = $requestData['package_position'];
            $package_data->package_original_price = $requestData['package_original_price'];
            $package_data->package_validity = $requestData['package_validity'];
            $package_data->package_validity_title = $requestData['package_validity_title'];
            $package_data->package_expiry_date = $requestData['package_expiry_date'];
            $package_data->package_action = $requestData['package_action'];

            if (isset($requestData['package_price'])) {
                $package_data->package_price = $requestData['package_price'];
            }
            if (isset($requestData['hotel_package_price'])) {
                $package_data->hotel_package_price = $requestData['hotel_package_price'];
            }
            if (isset($requestData['package_inner_title'])) {
                $package_data->package_inner_title = $requestData['package_inner_title'];
            }
            if (isset($requestData['package_inner_sub_title'])) {
                $package_data->package_inner_sub_title = $requestData['package_inner_sub_title'];
            }
            if (isset($requestData['package_inner_content'])) {
                $package_data->package_inner_content = $requestData['package_inner_content'];
            }
    
            $package_data->save();
    
         
                $response =  response()->json(['status' => true, 'message' => 'Package Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update Package']);
            }
        }
    
        return $response;
    }

    public function AllPackagePrice()
    {
    
        $data = PackagePriceModel::all();
      
            return response()->json(['status' => true,'data'=>$data]);
    
    }

    public function EditPackagePrice(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
       
        $rules = [
            'package_price_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $package_price_id = $request['package_price_id'];
            $package_price_data = PackagePriceModel::find($package_price_id);
    
            if ($package_price_data) {
               

                $response['status'] = true;
                $response['message'] = $package_price_data;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Package Price not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }

    public function DeletePackagePrice(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'package_price_id' => 'required',
        ];

        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $package_price_id = $requestData['package_price_id'];
    
    
        $package_price_data = PackagePriceModel::find($package_price_id);
       
            if (!$package_price_data) {
                return response()->json(['status'=>false,'message'=>'Package Price not found'], 404);
            }
    
    
            $package_price_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Package Price Deleted Successfully!']);
    
        
        }
    
        return $response;
    }
}
