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
use Illuminate\Support\Facades\Storage;

class HotelMagazinesController extends Controller
{
    public function AddHotelMagazines(Request $request)
    {
      

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
     
    
        $rules = [
            // 'user_id' => 'required',
            'title' => 'required',
            'thumbnail' => 'required',
            'file_pdf' => 'required',
            'file_image' => 'required',
        ];

    
    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
            $hotel_magazines = new MagazinesModel();
            $hotel_magazines->title = $request['title'];
            $hotel_magazines->user_id = $request['user_id'];
            // $hotel_magazines->file_pdf = $request->file('file_pdf')->store('uploads');

           

        if ($request->hasFile('file_pdf')) {
            $pdfFiles = $request->file('file_pdf');
        
        foreach ($pdfFiles as $pdfFile) {
        
            $path = $pdfFile->store('uploads');

        
            $filePaths[] = $path;
        }
        
        $hotel_magazines->file_pdf = json_encode($filePaths);
        
        }

        
        if ($request->hasFile('file_image')) {
            $imageFiles = $request->file('file_image');
        
        foreach ($imageFiles as $pdfFile) {
        
            $path = $pdfFile->store('uploads');

        
            $imagePaths[] = $path;
        }
        
        $hotel_magazines->file_image = json_encode($imagePaths);
        
        }


        if ($request->hasFile('thumbnail')) {
            $hotel_magazines->thumbnail = $request->file('thumbnail')->store('uploads');
        }
                $hotel_magazines->save();
    
    
            if ($hotel_magazines) {
                $response = response()->json(['status' => true, 'message' => 'Hotel Magazine Added Successfully']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add hotel magazines!']);
            }
        }
    
        return $response;
    }

    public function AllHotelMagazines()
    {
        $data = MagazinesModel::orderBy('id','DESC')->get();
    
        
 
        $data->transform(function ($item) {

            $item->thumbnail = asset("storage/app/".$item->thumbnail);
            $pdf_files = $item->file_pdf;
            $image_files = $item->file_image;

            $pdfarr = []; // Initialize $pdfarr here
            $imagearr = [];

            if(!empty($image_files)){

                $image = json_decode($image_files);
                foreach ($image as $key => $val) {
                    $fullImagePath = asset("storage/app/".$val);
                    $imagearr[] = $fullImagePath;
                }
             }
             $item->file_image = $imagearr; 

         if(!empty($pdf_files)){

            $pdf  = json_decode($pdf_files);
            foreach ($pdf as $key => $val) {
                $fullImagePath = asset("storage/app/".$val);
                $pdfarr[] = $fullImagePath;
            }
         }
    

         $item->file_pdf = $pdfarr; 

            return $item;
        });


    
            return response()->json(['status' => true,'data'=>$data]);
    
    }
   
    public function EditHotelMagazines(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
  
        $rules = [
            'hotel_magazine_id' => 'required',
         
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $hotel_magazine_id = $request['hotel_magazine_id'];
 

    
            $hotel_magazine = MagazinesModel::find($hotel_magazine_id);
   
            if ($request->hasFile('hotel_images')) {
                $hotel_data->hotel_images = $request->file('hotel_images')->store('uploads');
            }
            
        
            if ($hotel_magazine) {
                $hotel_magazine->thumbnail = asset("storage/app/".$hotel_magazine->thumbnail);
                $pdf_files = $hotel_magazine->file_pdf;
                $image_files = $hotel_magazine->file_image;
                $pdfarr = []; // Initialize $pdfarr here
                $imagearr = [];

                if(!empty($image_files)){

                    $pdf  = json_decode($image_files);
                    foreach ($pdf as $key => $val) {
                        $fullImagePath = asset("storage/app/".$val);
                        $imagearr[] = $fullImagePath;
                    }
                 }
    
                if(!empty($pdf_files)){
       
                   $pdf  = json_decode($pdf_files);
                   foreach ($pdf as $key => $val) {
                       $fullImagePath = asset("storage/app/".$val);
                       $pdfarr[] = $fullImagePath;
                   }
                   
           
                }
                $hotel_magazine->file_image = $imagearr; 
                $hotel_magazine->file_pdf = $pdfarr; 
                
                // $hotel_magazine->file_pdf = asset("storage/app/".$hotel_magazine->file_pdf);
                $response['status'] = true;
                $response['message'] = $hotel_magazine;
                // Do something with $hotel_amenity
         
            } else {
                $response['message'] = 'Magazine not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }



// public function UpdateHotelMagazines(Request $request)
// {
//     $response = array("status" => false, 'message' => '');
//     $requestData = $request->all(); 


//     $rules = [
//         'title' => 'required',
//         'thumbnail' => 'required',
//         'file_pdf' => 'required',
//     ];
//     $validator = Validator::make($request->all(), $rules);
//     if ($validator->fails()) {
//         $response['message'] = $request->messages();
//     } else {
//         $hotel_magazine_id = $request['hotel_magazine_id'];

//         $hotel_magazine = MagazinesModel::find($hotel_magazine_id);
//         if($hotel_magazine){
   
//         $hotel_magazine->title = $request['title'];
//         // $hotel_magazines->file_pdf = $request->file('file_pdf')->store('uploads');

       

//     if ($request->hasFile('file_pdf')) {
//     $pdfFiles = $request->file('file_pdf');



//     foreach ($pdfFiles as $pdfFile) {
    
//         $path = $pdfFile->store('uploads');

 
//     $filePaths[] = $path;
//         }

//     $hotel_magazine->file_pdf = json_encode($filePaths);

//     }

//     if ($request->hasFile('file_image')) {
//         $imageFiles = $request->file('file_image');
    
//     foreach ($imageFiles as $pdfFile) {
    
//         $path = $pdfFile->store('uploads');

    
//         $imagePaths[] = $path;
//     }
    
//     $hotel_magazine->file_image = json_encode($imagePaths);
    
//     }

//     if ($request->hasFile('thumbnail')) {
//     $hotel_magazine->thumbnail = $request->file('thumbnail')->store('uploads');
//     }
//         $hotel_magazine->save();

//             $response = response()->json(['status' => true, 'message' => 'Hotel Magazine Updated Successfully']);
//         } else {
//             $response = response()->json(['status' => false, 'message' => 'Failed to update hotel magazines!']);
//         }
 
//     }

//     return $response;
// }
public function UpdateHotelMagazines(Request $request)
{
    $response = array("status" => false, 'message' => '');
    $requestData = $request->all(); 

    $rules = [
        'title' => 'required',
        'thumbnail' => 'required',
        'file_pdf' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        $response['message'] = $request->messages();
    } else {
        $hotel_magazine_id = $request['hotel_magazine_id'];

        $hotel_magazine = MagazinesModel::find($hotel_magazine_id);
        if($hotel_magazine) {
            $hotel_magazine->title = $request['title'];

            // Append new PDF files
            if ($request->hasFile('file_pdf')) {
                $pdfFiles = $request->file('file_pdf');
                $filePaths = json_decode($hotel_magazine->file_pdf, true) ?? [];

                foreach ($pdfFiles as $pdfFile) {
                    $path = $pdfFile->store('uploads');
                    $filePaths[] = $path;
                }

                $hotel_magazine->file_pdf = json_encode($filePaths);
            }

            // Append new image files
            if ($request->hasFile('file_image')) {
                $imageFiles = $request->file('file_image');
                $imagePaths = json_decode($hotel_magazine->file_image, true) ?? [];

                foreach ($imageFiles as $imageFile) {
                    $path = $imageFile->store('uploads');
                    $imagePaths[] = $path;
                }

                $hotel_magazine->file_image = json_encode($imagePaths);
            }

            // Update thumbnail if provided
            if ($request->hasFile('thumbnail')) {
                $hotel_magazine->thumbnail = $request->file('thumbnail')->store('uploads');
            }

            $hotel_magazine->save();

            $response = response()->json(['status' => true, 'message' => 'Hotel Magazine Updated Successfully']);
        } else {
            $response = response()->json(['status' => false, 'message' => 'Failed to update hotel magazines!']);
        }
    }

    return $response;
}


public function DeleteHotelMagazines(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
  
        $rules = [
            'hotel_magazine_id' => 'required',
         
        ];

        $validator = Validator::make($request->all(), $rules);


       $hotel_magazine_id = $requestData['hotel_magazine_id'];
       $delete_hotel_magazine = MagazinesModel::find($hotel_magazine_id);

       if (!$delete_hotel_magazine) {
        return response()->json(['message' => 'Magazine not found'], 404);
         }

     $delete_hotel_magazine->delete();

            return response()->json(['message' => 'Magazine deleted successfully']);


    }


// public function DeleteHotelMagazinesSinglePdffile(Request $request)
// {
//     $response = array("status" => false, 'message' => '');
//     $requestData = $request->all();

//     $rules = [
//         'key' => 'required',
//         'hotel_magazine_id' => 'required',
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         $response['message'] = $request->messages();
//     } else {
//         $hotel_magazine_id = $requestData['hotel_magazine_id'];
//         $key = $requestData['key'];

//         $delete_hotel_magazine = MagazinesModel::find($hotel_magazine_id);

//         if ($delete_hotel_magazine) {
//             $file_pdf = json_decode($delete_hotel_magazine['file_pdf'], true);
//             $file_image = json_decode($delete_hotel_magazine['file_image'], true);
           



//             // Check if the key exists in the array
//             if (array_key_exists($key, $file_pdf)) {
//                 // Remove the file using the provided key
//                 unset($file_pdf[$key]);
//                 // Reindex array keys
//                 $file_pdf = array_values($file_pdf);

//                 // Update the 'file_pdf' array in the database
//                 $delete_hotel_magazine->file_pdf = json_encode($file_pdf);
//                 $delete_hotel_magazine->file_image = json_encode($file_image);
//                 $delete_hotel_magazine->save();

//                 $response['status'] = true;
//                 $response['message'] = 'File deleted successfully.';
//             } else {
//                 $response['message'] = 'Invalid key provided.';
//             }
//         }
//     }

//     return response()->json($response);
// }
public function DeleteHotelMagazinesSinglePdffile(Request $request)
{
    $response = array("status" => false, 'message' => '');
    $requestData = $request->all();

    $rules = [
        'key' => 'required',
        'hotel_magazine_id' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $request->messages();
    } else {
        $hotel_magazine_id = $requestData['hotel_magazine_id'];
        $key = $requestData['key'];

        $delete_hotel_magazine = MagazinesModel::find($hotel_magazine_id);

        if ($delete_hotel_magazine) {
            $file_pdf = json_decode($delete_hotel_magazine['file_pdf'], true);
            $file_image = json_decode($delete_hotel_magazine['file_image'], true);

            // Check if the key exists in the array
            if (array_key_exists($key, $file_pdf)) {
                // Remove the file using the provided key
                unset($file_pdf[$key]);
                unset($file_image[$key]); // Remove corresponding file_image
                // Reindex array keys
                $file_pdf = array_values($file_pdf);
                $file_image = array_values($file_image);

                // Update the 'file_pdf' and 'file_image' arrays in the database
                $delete_hotel_magazine->file_pdf = json_encode($file_pdf);
                $delete_hotel_magazine->file_image = json_encode($file_image);
                $delete_hotel_magazine->save();

                $response['status'] = true;
                $response['message'] = 'File and Image deleted successfully.';
            } else {
                $response['message'] = 'Invalid key provided.';
            }
        }
    }

    return response()->json($response);
}

public function LoginUserHotelMagazines(Request $request)
{
    $user = Auth::guard('api')->user();
    
    $u_id = $user['id'];
    $data = MagazinesModel::where('user_id', $u_id)->orderBy('id', 'desc')->get();

    return response()->json(['status' => true,'login_user_hotel_magazines_data'=>$data]);

}

    }

