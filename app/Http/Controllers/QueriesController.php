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

use Illuminate\Support\Facades\Storage;

class QueriesController extends Controller
{
    public function AddQuery(Request $request)
    {

        $response = array("status" => false, 'message' => '');
       
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
                'status' => 'required',
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
               
                $queries_data = new QueriesModel();
         
                $queries_data->name = $requestData['name'];
                $queries_data->email = $requestData['email'];
                $queries_data->message = $requestData['message'];
                $queries_data->status = $requestData['status'];
             
                $queries_data->save();

                if ($queries_data) {
                    $response =  response()->json(['status' => true,'message' => 'Query Created Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create query']);
                }
        
          }

              return $response;
    }

    public function AllQuery()
    {
        $data = QueriesModel::all();

        return response()->json(['status' => true,'data'=>$data]);
    
    }

    public function EditQuery(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
        
        $rules = [
            'query_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $query_id = $request['query_id'];
            $query_data = QueriesModel::find($query_id);
    
            if ($query_data) {
               
                $response['status'] = true;
                $response['message'] = $query_data;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Query not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }


    public function UpdateQuery(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
      
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'status' => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
          
            $response['message'] = $validator->messages();
            
        } else {
            $query_id = $request->input('query_id');
            $query_data = QueriesModel::find($query_id);
    
            if ($query_data) {
                
                $query_data->name = $request->input('name');
                $query_data->email = $request->input('email');
                $query_data->message = $request->input('message');
                $query_data->status = $request->input('status');
                
                if ($query_data->save()) {
                    $response = ['status' => true, 'message' => 'Query Updated Successfully'];
                } else {
                    $response = ['status' => false, 'message' => 'Failed to update query!'];
                }
            } else {
                $response['message'] = 'Query not found!';
            }
        }
    
        return response()->json($response);
    }

    public function DeleteQuery(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'query_id' => 'required',
        ];
    
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $query_id = $requestData['query_id'];
    
    
        $query_data = QueriesModel::find($query_id);
       
            if (!$query_data) {
                return response()->json(['status'=>false,'message'=>'Query not found'], 404);
            }
    
          
            $query_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Query Deleted Successfully!']);
    
        
        }
    
        return $response;
    }

}
