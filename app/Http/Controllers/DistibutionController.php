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
use Illuminate\Support\Facades\Storage;




class DistibutionController extends Controller
{
    public function AddDistributor(Request $request)
    {
        
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
     
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'hotel_image' => 'required',
            'hotel_description' => 'required',
            'link' => 'required',
        ];

    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $distributor = new DistributionModel();
    

            $distributor->title = $request['title'];
            $distributor->description = $request['description'];
            $distributor->hotel_description = $request['hotel_description'];
            $distributor->link = $request['link'];
           


            if ($request->hasFile('hotel_image')) {
                $pdfFiles = $request->file('hotel_image');
                $path = $pdfFiles->store('uploads');
                $distributor->hotel_image = $path;

        }
        
               $distributor->save();
            
            
                    if ($distributor) {
                        $response = response()->json(['status' => true, 'message' => 'Distributor Added Successfully']);
                    } else {
                        $response = response()->json(['status' => false, 'message' => 'Failed to add distributor!']);
                    }
                    return $response;
                }
            
            }


            public function AllDistributor()
            {
                $data = DistributionModel::all();
            
                $data->transform(function ($item) {
        
                $item->hotel_image = asset("storage/app/".$item->hotel_image);
               
                 $item->title = $item->title;
                 $item->description = $item->description;
                 $item->hotel_description = $item->hotel_description;

        
                    return $item;
                });
            
                    return response()->json(['status' => true,'data'=>$data]);
            
            }

            

            public function EditDistributor(Request $request)
            {
                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
             
                 
                $rules = [
                    'distributor_id' => 'required',
                 
                ];
        
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $response['message'] = $request->messages();
                } else {
                    $distributor_id = $request['distributor_id'];
            
                    $distributor = DistributionModel::find($distributor_id);
          


               
                    if ($distributor) {
                        if ($request->hasFile('hotel_image')) {
                            $distributor->hotel_image = $request->file('hotel_image')->store('uploads');
                        }

                        $distributor->title = $request['title'];
                        $distributor->description = $request['description'];
                        $distributor->hotel_description = $request['hotel_description'];

                        $response['status'] = true;
                        $response['message'] = $distributor;
                        // Do something with $hotel_amenity
                 
                    } else {
                        $response['message'] = 'Distributor not found';
                    }
                }
            
                // You might want to return the response at the end of your function
                return response()->json($response);
            }


            public function UpdateDistributor(Request $request)
            {
              

                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 


                
                $rules = [
                    'title' => 'required',
                    'description' => 'required',
                    'hotel_image' => 'required',
                    'hotel_description' => 'required',
                    'link' => 'required',
                ];
            
                $validator = Validator::make($request->all(), $rules); 
             


                if ($validator->fails()) {
                  

                    $response['message'] = $request->messages();
                } else {
                    $distributor_id = $request['distributor_id'];
                   
                    $distributor = DistributionModel::find($distributor_id);
          
                
                    if ($distributor) {
                        if ($request->hasFile('hotel_image')) {
                            $distributor->hotel_image = $request->file('hotel_image')->store('uploads');
                        }

                        $distributor->title = $request['title'];
                        $distributor->description = $request['description'];
                        $distributor->hotel_description = $request['hotel_description'];
                        $distributor->link = $request['link'];

                         $distributor->save(); 
                        
                                     if ($distributor) {
                                         $response = response()->json(['status' => true, 'message' => 'Distributor Updated Successfully']);
                                     } else {
                                         $response = response()->json(['status' => false, 'message' => 'Failed to update distributor!']);
                                     }
                                 }
                                 }
                             
                                 return $response;
                             }
                                 

          
            
            public function DeleteDistributor(Request $request)
            {
        
                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
          
                $rules = [
                    'distributor_id' => 'required',
                 
                ];
        
                $validator = Validator::make($request->all(), $rules);
        
        
               $distributor_id = $requestData['distributor_id'];
               $delete_distributor = DistributionModel::find($distributor_id);
        
               if (!$delete_distributor) {
                return response()->json(['message' => 'distributor not found'], 404);
            }
        
            $delete_distributor->delete();
        
            return response()->json(['message' => 'Distributor deleted successfully']);
        
        
        }
    }

