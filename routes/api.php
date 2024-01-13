<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HotelMagazinesController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DatabaseMigrationController;
use App\Http\Middleware\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MediakitController;
use App\Http\Controllers\DistibutionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['Api_Auth'])->group(function () {
    Route::get('/migrate-database', [DatabaseMigrationController::class, 'migrate']);
    



    Route::get('/test', function(){
        return ['status'=>true,'message'=>"Hello,i'm test."];
    });

    Route::post('/user-login', [UserController::class, 'UserLogin']);
    Route::post('/user-register', [UserController::class, 'UserRegister']);
    

    Route::get('/all-news/{id?}', [NewsController::class, 'index']);
	Route::post('/create-news', [NewsController::class, 'CreateNews']);
    Route::post('/edit-news', [NewsController::class, 'EditNews']);
    Route::post('/update-news', [NewsController::class, 'UpdateNews']);
    Route::post('/views-news', [NewsController::class, 'ViewNews']);
    Route::post('/delete-news', [NewsController::class, 'DeleteNews']);

    
    
    Route::post('/search-hotel', [HotelController::class, 'SearchHotel']);
    
     
     
    Route::post('/create-review', [ReviewController::class, 'CreateReview']);
    Route::get('/all-review/{type?}', [ReviewController::class, 'index']);
    Route::post('/delete-review', [ReviewController::class, 'DeleteReview']);
});

Route::middleware(['user_login'])->group(function () {
    Route::post('/user-logout', [UserController::class, 'UserLogout']);
    Route::post('/login-user-profile', [UserController::class, 'LoginUserProfile']);


    Route::post('/add-distibutor', [DistibutionController::class, 'AddDistributor']);
    Route::get('/all-distibutor', [DistibutionController::class, 'AllDistributor']);
    Route::post('/edit-distibutor', [DistibutionController::class, 'EditDistributor']);
    Route::post('/update-distibutor', [DistibutionController::class, 'UpdateDistributor']);
    Route::post('/delete-distibutor', [DistibutionController::class, 'DeleteDistributor']);
    
    
    

    Route::post('/payment', [PaymentController::class, 'payment']);
     Route::post('/add-hotel-magazines', [HotelMagazinesController::class, 'AddHotelMagazines']);
     Route::get('/all-hotel-magazines', [HotelMagazinesController::class, 'AllHotelMagazines']);
     Route::post('/edit-hotel-magazines', [HotelMagazinesController::class, 'EditHotelMagazines']);
     Route::post('/update-hotel-magazines', [HotelMagazinesController::class, 'UpdateHotelMagazines']);
     Route::post('/delete-hotel-magazines', [HotelMagazinesController::class, 'DeleteHotelMagazines']);
     Route::post('/delete-hotel-magazines-single-pdffile', [HotelMagazinesController::class, 'DeleteHotelMagazinesSinglePdffile']);
     
     Route::post('/login-user-hotel-magazines', [HotelMagazinesController::class, 'LoginUserHotelMagazines']);


     Route::post('/hotel-register', [HotelController::class, 'HotelRegister']);
    Route::get('/all-hotels', [HotelController::class, 'AllHotels']);
    Route::post('/edit-hotels', [HotelController::class, 'EditHotels']);
    Route::post('/update-hotels', [HotelController::class, 'UpdateHotels']);
    Route::post('/delete-hotels', [HotelController::class, 'DeleteHotels']);

    Route::post('/login-user-hotels', [HotelController::class, 'LoginUserHotels']);
    



    Route::post('/add-hotel-ameties', [HotelController::class, 'AddHotelAmeties']);
    Route::post('/update-hotel-ameties', [HotelController::class, 'UpdateHotelAmeties']);
    Route::post('/delete-hotel-ameties', [HotelController::class, 'DeleteHotelAmeties']);
    Route::get('/all-hotel-ameties', [HotelController::class, 'AllHotelAmeties']);
    Route::post('/edit-hotel-ameties', [HotelController::class, 'EditHotelAmeties']);


    Route::post('/add-media-kit', [MediakitController::class, 'AddMediaKit']);
    Route::get('/all-media-kit', [MediakitController::class, 'AllMediaKit']);
    Route::post('/update-media-kit', [MediakitController::class, 'UpdateMediaKit']);
    Route::post('/delete-media-kit', [MediakitController::class, 'DeleteMediaKit']);
    Route::post('/edit-media-kit', [MediakitController::class, 'EditMediaKit']);


    // Route::post('/login-user-hotel-ameties', [HotelController::class, 'LoginUserHotelAmeties']);

});

Route::get('/home', [HotelController::class, 'AllHomeData']);