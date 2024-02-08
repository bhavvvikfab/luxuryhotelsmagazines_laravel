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

use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function AddTeam(Request $request)
    {

        $response = array("status" => false, 'message' => '');
       
            $rules = [
                'name' => 'required',
                'position' => 'required',
                'image' => 'required',
                'status' => 'required',
            ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        
            if ($validator->fails()) {
                $response['message'] = $validator->messages();
              } else {
               
                $team_data = new TeamModel();
         
                $team_data->name = $requestData['name'];
                $team_data->position = $requestData['position'];
                $team_data->status = $requestData['status'];


                if ($request->hasFile('image')) {
                    $team_data->image = $request->file('image')->store('uploads');
               }

            //     if ($request->hasFile('image')) {
            //         $pdfFiles = $request->file('image');
            //         $path = $pdfFiles->store('uploads');
            //         $team_data->image = $path;
    
            // }
            

                $team_data->save();

                if ($team_data) {
                    $response =  response()->json(['status' => true,'message' => 'Team Created Successfully']);
                } else {
                    $response = response()->json(['status' => false,'message' => 'Failed to create team']);
                }
        
          }

              return $response;
    }

    public function AllTeam()
    {
        $data = TeamModel::all();
        // dd($data);


        $data->transform(function ($item) {
            $item->image = asset("storage/app/".$item->image);
            
            return $item;
        });


        return response()->json(['status' => true,'data'=>$data]);
    
    }

    public function EditTeam(Request $request)
    {
        $response = array("status" => false, 'message' => '');
        $requestData = $request->all(); 
        
        $rules = [
            'team_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $request->messages();
        } else {
            $team_id = $request['team_id'];
            $team_data = TeamModel::find($team_id);

            if ($team_data) {
            
                    $team_data->image = asset("storage/app/".$team_data->image);
         
               
                $response['status'] = true;
                $response['message'] = $team_data;
                // Do something with $hotel_amenity
            
            } else {
                $response['message'] = 'Team not found';
            }
        }
    
        // You might want to return the response at the end of your function
        return response()->json($response);
    }

    public function DeleteTeam(Request $request)
    {
        $response = array("status" => false, 'message' => '');
    
        $rules = [
            'team_id' => 'required',
        ];
    
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, $rules);
    
        if ($validator->fails()) {
            $response['message'] = $validator->messages();
        } else {
    
         $team_id = $requestData['team_id'];
    
        $team_data = TeamModel::find($team_id);
       
            if (!$team_data) {
                return response()->json(['status'=>false,'message'=>'Team not found'], 404);
            }
    
            $team_data->delete();
    
            $response = response()->json(['status'=>true,'message'=>'Team Deleted Successfully!']);
    
        }
    
        return $response;
    }

    public function UpdateTeam(Request $request)
    {

        $response = array("status" => false, 'message' => '');
        
        $rules = [
            'name' => 'required',
            'position' => 'required',
            'image' => 'required',
            'status' => 'required',
        ];

            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);
        
                if ($validator->fails()) {
                    $response['message'] = $validator->messages();
                }else {
                    $team_id = $requestData['team_id'];
                    $team_data = TeamModel::find($team_id);
            
                    if ($team_data) {
                  
                    $team_data->name = $requestData['name'];
                    $team_data->position = $requestData['position'];
                    $team_data->status = $requestData['status'];
              
                    if ($request->hasFile('image')) {
                        $team_data->image = $request->file('image')->store('uploads');
                    }
                    
                    $team_data->save();
        
                    $response = response()->json(['status' => true, 'message' => 'Team Updated Successfully']);
                } else {
                    $response = response()->json(['status' => false, 'message' => 'Team not found']);
                }
            }

              return $response;
    }

}
