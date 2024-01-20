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
use App\Models\SubscribersModel;



class SubscribersController extends Controller
{
    public function AddSubscriber(Request $request)
    {
        $response = array("status" => false, 'message' => '');

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'interest' => 'required',
            'about_us' => 'required',
        ];

        $requestData = $request->all();

        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {

            $subscribers = new SubscribersModel();
   
            $subscribers->name = $requestData['name'];
            $subscribers->email = $requestData['email'];
            $subscribers->interest = $requestData['interest'];
            $subscribers->about_us = $requestData['about_us'];
            // if (isset($requestData['contact_no'])) {
            //     $subscribers->contact_no = $requestData['contact_no'];
            // }
     
            $subscribers->save();
    
            if ($subscribers) {
                $response =  response()->json(['status' => true, 'message' => 'Subscriber Added Successfully!']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to add subscriber']);
            }
        }
    
        return $response;
    }

    public function AllSubscriber()
{

    // $data = User::all();
    $data = SubscribersModel::all();

    return response()->json(['status' => true,'data'=>$data]);

}

public function EditSubscriber(Request $request)
{
    
    $response = array("status" => false, 'message' => '');

    $rules = [
        'subscriber_id' => 'required',
    ];

    // $requestData = $request->json()->all();

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $request->messages();
    } else {
        $subscriber_id = $request['subscriber_id'];

        $subscriber_data = SubscribersModel::find($subscriber_id);

    
        if ($subscriber_data) {
           
            $response['status'] = true;
            $response['message'] = $subscriber_data;
            // Do something with $hotel_amenity
     
        } else {
            $response['message'] = 'Subscriber not found';
        }
    }

    // You might want to return the response at the end of your function
    return response()->json($response);
}

public function UpdateSubscriber(Request $request)
    {

        $response = array("status" => false, 'message' => '');

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'interest' => 'required',
            'about_us' => 'required',
        ];
        $requestData = $request->all();

        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {

            $subscriber_id = $requestData['subscriber_id'];
            $subscriber_data = SubscribersModel::find($subscriber_id);

            if (!$subscriber_data) {
                return response()->json(['status' => false, 'message' => 'Subscriber not found']);
            }
    
            $subscriber_data->name = $requestData['name'];
            $subscriber_data->email = $requestData['email'];
            $subscriber_data->interest = $requestData['interest'];
            $subscriber_data->about_us = $requestData['about_us'];
            

            // if (isset($requestData['contact_no'])) {
            //     $subscriber_data->contact_no = $requestData['contact_no'];
            // }
    
            $subscriber_data->save();
    
            if ($subscriber_data) {
                $response = response()->json(['status' => true, 'message' => 'Subscriber Updated Successfully!']);
            } else {
                $response = response()->json(['status' => false, 'message' => 'Failed to update Subscriber']);
            }
        }
    
        return $response;
    }


public function DeleteSubscriber(Request $request)
{

    $response = array("status" => false, 'message' => '');

    $rules = [
        'subscriber_id' => 'required',
    ];

    // $requestData = $request->json()->all();

    // $validator = Validator::make($requestData, $rules);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
    } else {

        $subscriber_id = $request['subscriber_id'];

        $subscriber_data = SubscribersModel::find($subscriber_id);

    
        if (!$subscriber_data) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        $subscriber_data->delete();

        return response()->json(['message' => 'Subscriber deleted successfully!']);

    
    }
   
}



}
