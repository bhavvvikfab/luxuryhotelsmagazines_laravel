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
use App\Http\Controllers\HomeHotelController;
use App\Http\Controllers\About_Us_Controller;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\VotedHotelController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\PackagePriceController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\SubscribersController;


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

Route::get('/reset-password/{token}/{id}', [UserController::class, 'resetPassword'])
    ->name('password.reset');
Route::middleware(['Api_Auth'])->group(function () {
    Route::get('/migrate-database', [DatabaseMigrationController::class, 'migrate']);
    Route::get('/swaggerGenerate', [DatabaseMigrationController::class, 'swaggerGenerate']);
    

    Route::get('/test', [UserController::class, 'test']);


    Route::post('/user-login', [UserController::class, 'UserLogin']);
    // Route::post('/forget-password', [UserController::class, 'Forgotpassword']);
    Route::post('/forgot-password/{remember_token}', [UserController::class, 'Resetpassword']);
    Route::post('/reset_password', [UserController::class, 'Resetpassword']);
    Route::post('/verify_otp', [UserController::class, 'verify_otp']);
    Route::post('/resend_password', [UserController::class, 'resend_password']);
    
    

    
   
    
    Route::post('/user-register', [UserController::class, 'UserRegister']);
    
    Route::post('/add-user', [UserController::class, 'AddUser']);
    Route::get('/all-user', [UserController::class, 'AllUser']);
    Route::post('/edit-user', [UserController::class, 'EditUser']);
    Route::post('/update-user', [UserController::class, 'UpdateUser']);
    Route::post('/delete-user', [UserController::class, 'DeleteUser']);

    
    Route::post('/search-hotel', [HotelController::class, 'SearchHotel']);
    
    // Route::get('/about-us', [About_Us_Controller::class, 'index']);
  
});

Route::middleware(['user_login'])->group(function () {
    Route::post('/user-logout', [UserController::class, 'UserLogout']);
    Route::post('/login-user-profile', [UserController::class, 'LoginUserProfile']);
    Route::post('/sendOTP', [UserController::class, 'sendOTP']);

    Route::post('/add-distibutor', [DistibutionController::class, 'AddDistributor']);
    Route::get('/all-distibutor', [DistibutionController::class, 'AllDistributor']);
    Route::post('/edit-distibutor', [DistibutionController::class, 'EditDistributor']);
    Route::post('/update-distibutor', [DistibutionController::class, 'UpdateDistributor']);
    Route::post('/delete-distibutor', [DistibutionController::class, 'DeleteDistributor']);



    Route::post('/add-distibutor-data', [DistibutionController::class, 'AddDistributorData']);
    Route::get('/all-distibutor-data', [DistibutionController::class, 'AllDistributorData']);
    Route::post('/edit-distibutor-data', [DistibutionController::class, 'EditDistributorData']);
    Route::post('/update-distibutor-data', [DistibutionController::class, 'UpdateDistributorData']);
    Route::post('/delete-distibutor-data', [DistibutionController::class, 'DeleteDistributorData']);

    
    Route::post('/add-distibutor-detail', [DistibutionController::class, 'AddDistributorDetail']);
    Route::get('/all-distibutor-detail', [DistibutionController::class, 'AllDistributorDetail']);
    Route::post('/edit-distibutor-detail', [DistibutionController::class, 'EditDistributorDetail']);
    Route::post('/update-distibutor-detail', [DistibutionController::class, 'UpdateDistributorDetail']);
    Route::post('/delete-distibutor-detail', [DistibutionController::class, 'DeleteDistributorDetail']);
    
    Route::get('/all-news/{id?}', [NewsController::class, 'index']);
	Route::post('/create-news', [NewsController::class, 'CreateNews']);
    Route::post('/edit-news', [NewsController::class, 'EditNews']);
    Route::post('/update-news', [NewsController::class, 'UpdateNews']);
    Route::post('/views-news', [NewsController::class, 'ViewNews']);
    Route::post('/delete-news', [NewsController::class, 'DeleteNews']);
    

    
    Route::post('/add-home-hotel', [HomeHotelController::class, 'AddHomeHotel']);
    Route::get('/all-home-hotel', [HomeHotelController::class, 'AllHomeHotel']);
    Route::post('/edit-home-hotel', [HomeHotelController::class, 'EditHomeHotel']);
    Route::post('/update-home-hotel', [HomeHotelController::class, 'UpdateHomeHotel']);
    Route::post('/delete-home-hotel', [HomeHotelController::class, 'DeleteHomeHotel']);
    Route::post('/limit-all-home-hotel', [HomeHotelController::class, 'LimitAllHomeHotel']);
    

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


    Route::post('/add-banner', [BannerController::class, 'AddBanner']);
    Route::get('/all-banner', [BannerController::class, 'AllBanner']);
    Route::post('/update-banner', [BannerController::class, 'UpdateBanner']);
    Route::post('/delete-banner', [BannerController::class, 'DeleteBanner']);
    Route::post('/edit-banner', [BannerController::class, 'EditBanner']);

    Route::post('/add-query', [QueriesController::class, 'AddQuery']);
    Route::get('/all-query', [QueriesController::class, 'AllQuery']);
    Route::post('/update-query', [QueriesController::class, 'UpdateQuery']);
    Route::post('/delete-query', [QueriesController::class, 'DeleteQuery']);
    Route::post('/edit-query', [QueriesController::class, 'EditQuery']);

    Route::post('/add-team', [TeamController::class, 'AddTeam']);
    Route::get('/all-team', [TeamController::class, 'AllTeam']);
    Route::post('/update-team', [TeamController::class, 'UpdateTeam']);
    Route::post('/delete-team', [TeamController::class, 'DeleteTeam']);
    Route::post('/edit-team', [TeamController::class, 'EditTeam']);


    Route::post('/add-voted-hotel', [VotedHotelController::class, 'AddVotedHotel']);
    Route::post('/update-voted-hotel', [VotedHotelController::class, 'UpdateVotedHotel']);
    Route::post('/delete-voted-hotel', [VotedHotelController::class, 'DeleteVotedHotel']);
    Route::get('/all-voted-hotel', [VotedHotelController::class, 'AllVotedHotel']);
    Route::post('/edit-voted-hotel', [VotedHotelController::class, 'EditVotedHotel']);


    Route::post('/add-hotel-properties', [PropertiesController::class, 'AddHotelProperties']);
    Route::post('/update-hotel-properties', [PropertiesController::class, 'UpdateHotelProperties']);
    Route::post('/delete-hotel-properties', [PropertiesController::class, 'DeleteHotelProperties']);
    Route::get('/all-hotel-properties', [PropertiesController::class, 'AllHotelProperties']);
    Route::post('/edit-hotel-properties', [PropertiesController::class, 'EditHotelProperties']);


    Route::post('/add-package-price', [PackagePriceController::class, 'AddPackagePrice']);
    Route::post('/update-package-price', [PackagePriceController::class, 'UpdatePackagePrice']);
    Route::post('/delete-package-price', [PackagePriceController::class, 'DeletePackagePrice']);
    Route::get('/all-package-price', [PackagePriceController::class, 'AllPackagePrice']);
    Route::post('/edit-package-price', [PackagePriceController::class, 'EditPackagePrice']);

    Route::post('/add-employer', [EmployerController::class, 'AddEmployer']);
    Route::get('/all-employer', [EmployerController::class, 'AllEmployer']);
    Route::post('/edit-employer', [EmployerController::class, 'EditEmployer']);
    Route::post('/update-employer', [EmployerController::class, 'UpdateEmployer']);
    Route::post('/delete-employer', [EmployerController::class, 'DeleteEmployer']);


    Route::post('/add-subscriber', [SubscribersController::class, 'AddSubscriber']);
    Route::get('/all-subscriber', [SubscribersController::class, 'AllSubscriber']);
    Route::post('/edit-subscriber', [SubscribersController::class, 'EditSubscriber']);
    Route::post('/update-subscriber', [SubscribersController::class, 'UpdateSubscriber']);
    Route::post('/delete-subscriber', [SubscribersController::class, 'DeleteSubscriber']);



    // Route::post('/login-user-hotel-ameties', [HotelController::class, 'LoginUserHotelAmeties']);

    Route::post('/create-review', [ReviewController::class, 'CreateReview']);
    Route::get('/all-review/{type?}', [ReviewController::class, 'index']);
    Route::post('/delete-review', [ReviewController::class, 'DeleteReview']);


    Route::post('/update-about-us', [About_Us_Controller::class, 'update_about_us']);
    Route::get('/edit-about-us', [About_Us_Controller::class, 'index']);
    Route::post('/edit-magzine-distributed', [About_Us_Controller::class, 'edit_magzine_distributed']);
    Route::post('/update-magzine-distributed', [About_Us_Controller::class, 'update_magzine_distributed']);
    
    Route::post('/stripe-Single-Payment', [PaymentController::class, 'stripe_Single_Payment']);
    Route::post('/stripe-subscription-payment', [PaymentController::class, 'stripeSubscriptionPayment']);
    
    Route::post('/payment-success', [PaymentController::class, 'paypalPaymentSuccess']);
});

Route::get('/home', [HotelController::class, 'AllHomeData']);