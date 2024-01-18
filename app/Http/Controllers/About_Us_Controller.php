<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AboutUs_Model;

class About_Us_Controller extends Controller
{
    public function index(Request $request)
   {
     $response = array("status"=>false,'message' => '');
    //  $rules = [
    //         'about_us_id' => 'required'
    //     ];

    // $requestData = $request->all();
    // $validator = Validator::make($requestData, $rules);
    $data = [];

        // if ($validator->fails()) {
        //     $response['message'] = $validator->messages();
        //   } else {
            $about_us_id = 1;

            
            $about_us_data = AboutUs_Model::find($about_us_id);
            
            if ($about_us_data) {
                $magzine_distributed = json_decode($about_us_data['magzine_distributed'], true);
                $magazines = [];
                foreach ($magzine_distributed as $magazine) {
                    $magazines[] = [
                        'image' => asset('storage/app/'.$magazine['image']),
                        'title' => $magazine['title'],
                        'desc' => $magazine['desc'],
                    ];
                }
                
                $about_us_data['magzine_distributed'] = $magazines;
               
                $data = $about_us_data;
    
                $response = response()->json(['status' => true, 'message' => 'Data Found', 'data' => $data]);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Data Not Found','data'=>$data]);
            }
        // }

          return $response;
   }

   public function update_about_us(Request $request){
        $response = array("status" => false, 'message' => '');
        $rules = [
            'about_us' => 'required',
            'distributed_desc' => 'required',
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $about_us = AboutUs_Model::find(1);

            if (!$about_us) {
                return response()->json(['status' => false, 'message' => 'About Us not found']);
            }

            $about_us->about_us = $requestData['about_us'];
            $about_us->distributed_desc = $requestData['distributed_desc'];
            $about_us->save();

            if ($about_us) {
                $response = response()->json(['status' => true, 'message' => 'About Us Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update about us']);
            }
        }

        return $response;
}

public function edit_magzine_distributed(Request $request){
    $response = array("status"=>false,'message' => '');
    $rules = [
            'index' => 'required'
        ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);
    $data = [];

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
          } else {
          $about_us_id = 1;
          $index = $requestData['index'];
            
            $about_us_data = AboutUs_Model::find($about_us_id);
            if ($about_us_data) {
                $magzine_distributed = json_decode($about_us_data['magzine_distributed'], true);

                if (isset($magzine_distributed[$index])) {
                    $magzine_distributed_data = $magzine_distributed[$index];
                    $magzine_distributed_data['image'] = asset('storage/app/'.$magzine_distributed_data['image']);
                    $data = $magzine_distributed_data;
                    $response = response()->json(['status' => true, 'message' => 'Data Found', 'data' => $data]);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Index not found in magzine_distributed', 'data' => $data]);
                }
            } else {
                $response = response()->json(['status' => false, 'message' => 'Data Not Found','data'=>$data]);
            }
       }

          return $response;
}

public function update_magzine_distributed(Request $request)
{
    $response = array("status" => false, 'message' => '');
    $rules = [
        'index' => 'required',
        'title' => 'required',
        'desc' => 'required',
    ];

    $requestData = $request->all();
    $validator = Validator::make($requestData, $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {
        $about_us = AboutUs_Model::find(1);

        if (!$about_us) {
            return response()->json(['status' => false, 'message' => 'About us data not found']);
        }

        $magzine_distributed = json_decode($about_us->magzine_distributed, true);
        $index = $requestData['index'];

        if (isset($magzine_distributed[$index])) {
            // Handle image upload
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                
                // Check for valid file extension
                $validExtensions = ['jpg','jpeg','png','gif'];
                $fileExtension = $uploadedFile->getClientOriginalExtension();

                if (!in_array($fileExtension, $validExtensions)) {
                    return response()->json(['status' => false, 'message' => 'Invalid file extension. Allowed extensions:  jpg, jpeg, png, gif']);
                }

                $imagePath = $request->file('image')->store('uploads');
                $magzine_distributed[$index]['image'] = $imagePath;
            }

            $magzine_distributed[$index]['title'] = $requestData['title'];
            $magzine_distributed[$index]['desc'] = $requestData['desc'];

            $about_us->magzine_distributed = json_encode($magzine_distributed);
         
            $about_us->save();

            if ($about_us) {
                $response = response()->json(['status' => true, 'message' => 'Data Updated Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update data']);
            }
        } else {
            $response = response()->json(['status' => false, 'message' => 'Index not found in magzine distributed']);
        }
    }

    return $response;
 }

}
