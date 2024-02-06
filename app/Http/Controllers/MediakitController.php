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
use Illuminate\Support\Facades\Storage;

class MediakitController extends Controller
{
    public function AddMediaKit(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
     
        $rules = [
            // 'user_id' => 'required',
            'title' => 'required',
            'media_kit_image' => 'required',
            'file_pdf' => 'required',
        ];

    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $media_kit = new MediaKitModel();
            
             $media_kit->title = $requestData['title'];
            if ($request->hasFile('file_pdf')) {
                $pdfFiles = $request->file('file_pdf');
                $path = $pdfFiles->store('uploads');
                $media_kit->file_pdf = $path;

        }
        
        if ($request->hasFile('media_kit_image')) {
             $media_kit->media_kit_image = $request->file('media_kit_image')->store('uploads');
        }
                    $media_kit->save();
            
            
                    if ($media_kit) {
                        $response = response()->json(['status' => true, 'message' => 'Media Kit Added Successfully']);
                    } else {
                        $response = response()->json(['status' => false, 'message' => 'Failed to add media kit!']);
                    }
                    return $response;
                }
            
    }

    public function AllMediaKit()
        {
                    $data = MediaKitModel::all();
                  

             
                    $data->transform(function ($item) {
            
                        $item->media_kit_image = asset("storage/app/".$item->media_kit_image);
                      
            
                     $item->file_pdf = asset("storage/app/".$item->file_pdf);
            
                        return $item;
                    });
                
                        return response()->json(['status' => true,'data'=>$data]);
                
        }

    public function UpdateMediaKit(Request $request)
        {

                

                    $response = array("status" => false, 'message' => '');
                    $requestData = $request->all(); 
                    


                    $rules = [
                        // 'user_id' => 'required',
                        'title' => 'required',
                        'media_kit_image' => 'required',
                        'file_pdf' => 'required',
                    ];
            
                
                    $validator = Validator::make($request->all(), $rules); 

                    if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {
                        $media_kit_id = $request['media_kit_id'];
                
                        $media_kit = MediaKitModel::find($media_kit_id);
              

                        if ($media_kit) {
                            $media_kit->title = $requestData['title'];
                            if ($request->hasFile('media_kit_image')) {
                                $media_kit->media_kit_image = $request->file('media_kit_image')->store('uploads');
                            }

                          
                            if ($request->hasFile('file_pdf')) {
                                $pdfFiles = $request->file('file_pdf');
                                $path = $pdfFiles->store('uploads');
                                $media_kit->file_pdf = asset("storage/app/".$path);
                
                        }

                             $media_kit->save(); 

                             $response = response()->json(['status' => true, 'message' => 'Media Kit Updated Successfully']);
                            } else {
                                $response = response()->json(['status' => false, 'message' => 'Failed to updated Media Kit!']);
                            }
                            return $response;
                        }
                    
            }
                            
                            
                           
    public function EditMediaKit(Request $request)
        {

                    $response = array("status" => false, 'message' => '');
                    $requestData = $request->all(); 
        

              
                    $rules = [
                        'media_kit_id' => 'required',
                     
                    ];
            
                    $validator = Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        $response['message'] = $validator->messages();
                    } else {
                        $media_kit_id = $request['media_kit_id'];
                
                        $media_kit = MediaKitModel::find($media_kit_id);
              

                   
                        if ($media_kit) {
                            if ($request->hasFile('media_kit_image')) {
                                $media_kit->media_kit_image = $request->file('media_kit_image')->store('uploads');
                            }

                          
                            if ($request->hasFile('file_pdf')) {
                                $pdfFiles = $request->file('file_pdf')->store('uploads');
                                $path = $pdfFiles->store('uploads');
                                $media_kit->file_pdf = asset("storage/app/".$path);
                
                        }

                     
                            $response['status'] = true;
                            $response['message'] = $media_kit;
                            // Do something with $hotel_amenity
                     
                        } else {
                            $response['message'] = 'Media Kit not found';
                        }
                    }
                
                    // You might want to return the response at the end of your function
                    return response()->json($response);
        }


public function DeleteMediaKit(Request $request)
    {

            $response = array("status" => false, 'message' => '');
            $requestData = $request->all(); 
    
            $rules = [
                'media_kit_id' => 'required',
            
            ];

            $validator = Validator::make($request->all(), $rules);


        $media_kit_id = $requestData['media_kit_id'];
        $delete_media_kit = MediaKitModel::find($media_kit_id);

        if (!$delete_media_kit) {
            return response()->json(['message' => 'Media kit not found'], 404);
        }

        $delete_media_kit->delete();

        return response()->json(['message' => 'Media Kit deleted successfully']);


    }
            }
