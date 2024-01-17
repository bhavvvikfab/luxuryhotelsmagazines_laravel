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

use Illuminate\Support\Facades\Storage;




class DistibutionController extends Controller
{
    public function AddDistributor(Request $request)
     {
        
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
     
        $rules = [
            'title' => 'required',
            'country_name' => 'required',
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
            $distributor->country_name = $request['country_name'];
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


    // public function UpdateDistributor(Request $request)
    // {
        

    //     $response = array("status" => false, 'message' => '');
    //     $requestData = $request->all(); 


        
    //     $rules = [
    //         'title' => 'required',
    //         'country_name' => 'required',
    //         'hotel_image' => 'required',
    //         'hotel_description' => 'required',
    //         'link' => 'required',
    //     ];
    
    //     $validator = Validator::make($request->all(), $rules); 
        


    //     if ($validator->fails()) {
            

    //         $response['message'] = $request->messages();
    //     } else {
    //         $distributor_id = $request['distributor_id'];
            
    //         $distributor = DistributionModel::find($distributor_id);
    
        
    //         if ($distributor) {
    //             if ($request->hasFile('hotel_image')) {
    //                 $distributor->hotel_image = $request->file('hotel_image')->store('uploads');
    //             }

    //             $distributor->title = $request['title'];
    //             $distributor->country_name = $request['country_name'];
    //             $distributor->hotel_description = $request['hotel_description'];
    //             $distributor->link = $request['link'];

    //                 $distributor->save(); 
                
    //                             if ($distributor) {
    //                                 $response = response()->json(['status' => true, 'message' => 'Distributor Updated Successfully']);
    //                             } else {
    //                                 $response = response()->json(['status' => false, 'message' => 'Failed to update distributor!']);
    //                             }
    //                         }
    //                         }
                        
    //                         return $response;
    // }
                                 
    public function updateDistributor(Request $request)
    {
        $response = ['status' => false, 'message' => ''];
        $requestData = $request->all();
    
        $rules = [
            'title' => 'required',
            'country_name' => 'required',
            'hotel_image' => 'required',
            'hotel_description' => 'required',
            'link' => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->errors()->first();
        } else {
            $distributorId = $request->input('distributor_id');
            $distributor = DistributionModel::find($distributorId);
    
            if ($distributor) {
                if ($request->hasFile('hotel_image')) {
                    // Assuming 'uploads' is the storage directory; adjust as needed.
                    $imagePath = $request->file('hotel_image')->store('uploads');
                    $distributor->hotel_image = $imagePath;
                }
    
                $distributor->title = $request->input('title');
                $distributor->country_name = $request->input('country_name');
                $distributor->hotel_description = $request->input('hotel_description');
                $distributor->link = $request->input('link');
    
                if ($distributor->save()) {
                    $response = ['status' => true, 'message' => 'Distributor Updated Successfully'];
                } else {
                    $response = ['status' => false, 'message' => 'Failed to update distributor!'];
                }
            } else {
                $response['message'] = 'Distributor not found!';
            }
        }
    
        return response()->json($response);
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


public function AddDistributorData(Request $request)
{
    $response = array("status" => false, 'message' => '');
    $requestData = $request->all(); 
  
    $rules = [
        'header_info' => 'required',
        'services_data' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
   

        $service = json_encode($requestData['services_data']);
     
        $distributor_data = new DistributionDataModel();
        $distributor_data->header_info  =  $requestData['header_info'];
        $distributor_data->services_data  = $service;
        $distributor_data->save();
  
        if ($distributor_data) {
            $response = response()->json(['status' => true, 'message' => 'Distributor Data Added Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to add distributor data!']);
        }
        return $response;
    }

}


                               
public function updateDistributorData(Request $request)
{
    $response = ['status' => false, 'message' => ''];

    $rules = [
        'header_info' => 'required',
        'services_data' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->errors()->first();
    } else {
        $distributorDataId = $request->input('distributor_data_id');
        $distributorData = DistributionDataModel::find($distributorDataId);

        if ($distributorData) {
            $service = json_encode($request->input('services_data'));

            $distributorData->header_info = $request->input('header_info');
            $distributorData->services_data = $service;

            if ($distributorData->save()) {
                $response = ['status' => true, 'message' => 'Distributor Data Updated Successfully'];
            } else {
                $response['message'] = 'Failed to update distributor data!';
            }
        } else {
            $response['message'] = 'Distributor data not found!';
        }
    }

    return response()->json($response);
}


public function AllDistributorData()
{
    $data = DistributionDataModel::all();

        return response()->json(['status' => true,'data'=>$data]);

}


public function EditDistributorData(Request $request)
            {
                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
             
                $rules = [
                    'distributor_data_id' => 'required',
                ];
        
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $response['message'] = $request->messages();
                } else {
                    $distributor_data_id = $request['distributor_data_id'];
                    $distributor_data = DistributionDataModel::find($distributor_data_id);
           
                    if ($distributor_data) {
                        

                        $response['status'] = true;
                        $response['message'] = $distributor_data;
                        // Do something with $hotel_amenity
                 
                    } else {
                        $response['message'] = 'Distributor Data not found';
                    }
                }
            
                // You might want to return the response at the end of your function
                return response()->json($response);
            }

    public function DeleteDistributorData(Request $request)
        {
        
                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
          
                $rules = [
                    'distributor_data_id' => 'required',
                 
                ];
        
                $validator = Validator::make($request->all(), $rules);
        
        
               $distributor_data_id = $requestData['distributor_data_id'];
               $delete_distributor_data = DistributionDataModel::find($distributor_data_id);
        
               if (!$delete_distributor_data) {
                return response()->json(['message' => 'distributor data not found'], 404);
            }
        
            $delete_distributor_data->delete();
        
            return response()->json(['message' => 'Distributor Data deleted successfully']);
        
        
        }

    public function AddDistributorDetail(Request $request)

        {

                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
            
                $rules = [
                    'title' => 'required',
                    'hotel_image' => 'required',
                    'link' => 'required',
                ];

    
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                } else {
                $distributor = new DistributionDetailsModel();
        

                $distributor->title = $request['title'];
                $distributor->link = $request['link'];
           


                    if ($request->hasFile('hotel_image')) {
                        $pdfFiles = $request->file('hotel_image');
                        $path = $pdfFiles->store('uploads');
                        $distributor->hotel_image = $path;

                }
        
                    $distributor->save();
            
            
                        if ($distributor) {
                            $response = response()->json(['status' => true, 'message' => 'Distribution Detail  Added Successfully']);
                        } else {
                            $response = response()->json(['status' => false, 'message' => 'Failed to add distribution detail data!']);
                        }
                        return $response;
                }
            
        }
        
            public function AllDistributorDetail()
            {
                $data = DistributionDetailsModel::all();
            
                $data->transform(function ($item) {
               
        
                $item->hotel_image = asset("storage/app/".$item->hotel_image);
               
                    return $item;
                });
            
                    return response()->json(['status' => true,'data'=>$data]);
            
            }

            public function EditDistributorDetail(Request $request)
            {
                $response = array("status" => false, 'message' => '');
                $requestData = $request->all(); 
             
                $rules = [
                    'distributor_detail_id' => 'required',
                ];
        
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $response['message'] = $request->messages();
                } else {
                    $distributor_detail_id = $request['distributor_detail_id'];
                    $distributor_detail_data = DistributionDetailsModel::find($distributor_detail_id);
           
                    if ($distributor_detail_data) {
                        if ($request->hasFile('hotel_image')) {
                            $distributor->hotel_image = $request->file('hotel_image')->store('uploads');
                        }

                        $response['status'] = true;
                        $response['message'] = $distributor_detail_data;
                        // Do something with $hotel_amenity
                 
                    } else {
                        $response['message'] = 'Distributor detail not found';
                    }
                }
            
                // You might want to return the response at the end of your function
                return response()->json($response);
            }

    //         public function UpdateDistributorDetail(Request $request)
    // {
        

    //     $response = array("status" => false, 'message' => '');
    //     $requestData = $request->all(); 


        
        
    //     $rules = [
    //         'title' => 'required',
    //         'hotel_image' => 'required',
    //         'link' => 'required',
    //     ];
    
    //     $validator = Validator::make($request->all(), $rules); 
        


    //     if ($validator->fails()) {
            

    //         $response['message'] = $request->messages();
    //     } else {
    //         $distributor_detail_id = $request['distributor_detail_id'];
            
    //         $distributor_detail_data = DistributionDetailsModel::find($distributor_detail_id);
    
        
    //         if ($distributor_detail_data) {
    //             if ($request->hasFile('hotel_image')) {
    //                 $distributor_detail_data->hotel_image = $request->file('hotel_image')->store('uploads');
    //             }

    //             $distributor_detail_data->title = $request['title'];
    //             $distributor_detail_data->link = $request['link'];
    //             $distributor_detail_data->save(); 
                
    //                             if ($distributor_detail_data) {
    //                                 $response = response()->json(['status' => true, 'message' => 'Distribution Detail Updated Successfully']);
    //                             } else {
    //                                 $response = response()->json(['status' => false, 'message' => 'Failed to update distributor!']);
    //                             }
    //                         }
    //                         }
                        
    //                         return $response;
    // }
            
    public function updateDistributorDetail(Request $request)
{
    $response = ['status' => false, 'message' => ''];
    $requestData = $request->all();

    $rules = [
        'title' => 'required',
        'hotel_image' => 'required',
        'link' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->errors()->first();
    } else {
        $distributorDetailId = $request->input('distributor_detail_id');
        $distributorDetailData = DistributionDetailsModel::find($distributorDetailId);

        if ($distributorDetailData) {
            if ($request->hasFile('hotel_image')) {
                // Assuming 'uploads' is the storage directory; adjust as needed.
                $imagePath = $request->file('hotel_image')->store('uploads');
                $distributorDetailData->hotel_image = $imagePath;
            }

            $distributorDetailData->title = $request->input('title');
            $distributorDetailData->link = $request->input('link');

            if ($distributorDetailData->save()) {
                $response = ['status' => true, 'message' => 'Distribution Detail Updated Successfully'];
            } else {
                $response = ['status' => false, 'message' => 'Failed to update distributor detail!'];
            }
        } else {
            $response['message'] = 'Distributor detail not found!';
        }
    }

    return response()->json($response);
}

       
    public function DeleteDistributorDetail(Request $request)
    {
    
            $response = array("status" => false, 'message' => '');
            $requestData = $request->all(); 
      
            $rules = [
                'distributor_detail_id' => 'required',
             
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
    
           $distributor_detail_id = $requestData['distributor_detail_id'];
           $delete_distributor_details = DistributionDetailsModel::find($distributor_detail_id);
    
           if (!$delete_distributor_details) {
            return response()->json(['message' => 'distribution detail not found'], 404);
          }
    
        $delete_distributor_details->delete();
    
        return response()->json(['message' => 'Distribution Detail deleted successfully']);
    
    
    }

}



