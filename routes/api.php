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
use App\Http\Controllers\HomeInfoController;
use App\Http\Controllers\HotelCreateProfileController;
use App\Http\Controllers\HotelFacilitiesController;
use App\Http\Controllers\VotingDetailsController;
use App\Http\Controllers\AdevertisingWithUsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\WhatWeDoController;








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
    
    Route::post('/add-employer', [EmployerController::class, 'AddEmployer']);
    Route::get('/all-employer', [EmployerController::class, 'AllEmployer']);
    Route::post('/edit-employer', [EmployerController::class, 'EditEmployer']);
    Route::post('/update-employer', [EmployerController::class, 'UpdateEmployer']);
    Route::post('/delete-employer', [EmployerController::class, 'DeleteEmployer']);

    

    
   
    
    Route::post('/user-register', [UserController::class, 'UserRegister']);
    
    Route::post('/add-user', [UserController::class, 'AddUser']);
    Route::get('/all-user', [UserController::class, 'AllUser']);
    Route::post('/edit-user', [UserController::class, 'EditUser']);
    Route::post('/update-user', [UserController::class, 'UpdateUser']);
    Route::post('/delete-user', [UserController::class, 'DeleteUser']);

    
    
    Route::post('/search-hotel', [HotelController::class, 'SearchHotel']);

    Route::get('/all-hotels', [HotelController::class, 'AllHotels']);
    Route::post('/edit-hotels', [HotelController::class, 'EditHotels']);
    Route::post('/user_hotel', [HotelController::class, 'user_hotel']);

    Route::get('/all_special_offer', [HotelController::class, 'all_special_offer']);
    Route::post('/edit_special_offer', [HotelController::class, 'edit_special_offer']);
    

    Route::get('/all-distibutor', [DistibutionController::class, 'AllDistributor']);
    Route::post('/edit-distibutor', [DistibutionController::class, 'EditDistributor']);


    Route::get('/all-distibutor-data', [DistibutionController::class, 'AllDistributorData']);
    Route::post('/edit-distibutor-data', [DistibutionController::class, 'EditDistributorData']);

    Route::get('/all-distibutor-detail', [DistibutionController::class, 'AllDistributorDetail']);
    Route::post('/edit-distibutor-detail', [DistibutionController::class, 'EditDistributorDetail']);

    Route::get('/all-news/{id?}', [NewsController::class, 'index']);
    Route::post('/edit-news', [NewsController::class, 'EditNews']);
    Route::post('/views-news', [NewsController::class, 'ViewNews']);

    Route::post('/edit_news_category', [NewsController::class, 'edit_news_category']);
    Route::get('/all_news_category', [NewsController::class, 'all_news_category']);

    Route::get('/all-home-hotel', [HomeHotelController::class, 'AllHomeHotel']);
    Route::post('/edit-home-hotel', [HomeHotelController::class, 'EditHomeHotel']);
    Route::post('/limit-all-home-hotel', [HomeHotelController::class, 'LimitAllHomeHotel']);

    Route::post('/edit-hotel-magazines', [HotelMagazinesController::class, 'EditHotelMagazines']);

    Route::get('/all-hotel-ameties', [HotelController::class, 'AllHotelAmeties']);
    Route::post('/edit-hotel-ameties', [HotelController::class, 'EditHotelAmeties']);

    Route::get('/all-hotel-facilities', [HotelFacilitiesController::class, 'AllHotelFacilities']);
    Route::post('/edit-hotel-facilities', [HotelFacilitiesController::class, 'EditHotelFacilities']);

    Route::get('/all-media-kit', [MediakitController::class, 'AllMediaKit']);
    Route::post('/edit-media-kit', [MediakitController::class, 'EditMediaKit']);

    Route::get('/all-team', [TeamController::class, 'AllTeam']);
    Route::post('/edit-team', [TeamController::class, 'EditTeam']);


    Route::get('/all-banner', [BannerController::class, 'AllBanner']);
    Route::post('/edit-banner', [BannerController::class, 'EditBanner']);

    Route::get('/all-query', [QueriesController::class, 'AllQuery']);
    Route::post('/edit-query', [QueriesController::class, 'EditQuery']);

    Route::get('/all-voted-hotel', [VotedHotelController::class, 'AllVotedHotel']);
    Route::post('/edit-voted-hotel', [VotedHotelController::class, 'EditVotedHotel']);

    Route::get('/all-hotel-properties', [PropertiesController::class, 'AllHotelProperties']);
    Route::post('/edit-hotel-properties', [PropertiesController::class, 'EditHotelProperties']);

    Route::get('/all-package-price', [PackagePriceController::class, 'AllPackagePrice']);
    Route::post('/edit-package-price', [PackagePriceController::class, 'EditPackagePrice']);

    Route::get('/all-subscriber', [SubscribersController::class, 'AllSubscriber']);
    Route::post('/edit-subscriber', [SubscribersController::class, 'EditSubscriber']);

    Route::get('/edit-about-us', [About_Us_Controller::class, 'index']);
    Route::post('/edit-magzine-distributed', [About_Us_Controller::class, 'edit_magzine_distributed']);

    Route::get('/all-review/{type?}', [ReviewController::class, 'index']);

    Route::post('/edit_home_info', [HomeInfoController::class, 'edit_home_info']);

    Route::post('/get_single_page_details', [HomeInfoController::class, 'get_single_page_details']);
    Route::get('/all-review-categories', [HotelController::class, 'All_Review_Categories']);

    Route::post('/edit_magazine_cover', [HomeInfoController::class, 'edit_magazine_cover']);
    Route::get('/all-review-topics', [HotelController::class, 'All_Review_Topics']);

    Route::post('/add_contact_us', [ContactUsController::class, 'add_contact_us']);

 
    
    // Route::get('/about-us', [About_Us_Controller::class, 'index']);
    Route::post('/home', [HotelController::class, 'AllHomeData']);

    


    Route::post('/CreateNewsLetter', [NewsController::class, 'CreateNewsLetter']);
    Route::get('/all-newsletter', [NewsController::class, 'All_Newsletter']);
    Route::get('/all-hotel-magazines', [HotelMagazinesController::class, 'AllHotelMagazines']);
  
});


Route::middleware(['user_login'])->group(function () {
    Route::post('/user-logout', [UserController::class, 'UserLogout']);
    Route::post('/login-user-profile', [UserController::class, 'LoginUserProfile']);
    Route::post('/login-user-update-profile', [UserController::class, 'LoginUserUpdateProfile']);
    
    Route::post('/sendOTP', [UserController::class, 'sendOTP']);

    Route::post('/add-distibutor', [DistibutionController::class, 'AddDistributor']);
   
    Route::post('/update-distibutor', [DistibutionController::class, 'UpdateDistributor']);
    Route::post('/delete-distibutor', [DistibutionController::class, 'DeleteDistributor']);



    Route::post('/add-distibutor-data', [DistibutionController::class, 'AddDistributorData']);
   
    Route::post('/update-distibutor-data', [DistibutionController::class, 'UpdateDistributorData']);
    Route::post('/delete-distibutor-data', [DistibutionController::class, 'DeleteDistributorData']);

    
    Route::post('/add-distibutor-detail', [DistibutionController::class, 'AddDistributorDetail']);
    
    Route::post('/update-distibutor-detail', [DistibutionController::class, 'UpdateDistributorDetail']);
    Route::post('/delete-distibutor-detail', [DistibutionController::class, 'DeleteDistributorDetail']);
    
 
	Route::post('/create-news', [NewsController::class, 'CreateNews']);
  
    Route::post('/update-news', [NewsController::class, 'UpdateNews']);

    Route::post('/delete-news', [NewsController::class, 'DeleteNews']);
    Route::post('/delete_single_news_image', [NewsController::class, 'delete_single_news_image']);
    Route::post('/update_single_news_image', [NewsController::class, 'update_single_news_image']);
    Route::post('/add_multiple_images_news', [NewsController::class, 'add_multiple_images_news']);

    // news category
    
    Route::post('/add_news_category', [NewsController::class, 'add_news_category']);
    
    Route::post('/update_news_category', [NewsController::class, 'update_news_category']);
    
    Route::post('/delete_news_category', [NewsController::class, 'delete_news_category']);
    

    
    Route::post('/add-home-hotel', [HomeHotelController::class, 'AddHomeHotel']);
  
    Route::post('/update-home-hotel', [HomeHotelController::class, 'UpdateHomeHotel']);
    Route::post('/delete-home-hotel', [HomeHotelController::class, 'DeleteHomeHotel']);


    
   
    

    Route::post('/payment', [PaymentController::class, 'payment']);
     Route::post('/add-hotel-magazines', [HotelMagazinesController::class, 'AddHotelMagazines']);
   
   
     Route::post('/update-hotel-magazines', [HotelMagazinesController::class, 'UpdateHotelMagazines']);
     Route::post('/delete-hotel-magazines', [HotelMagazinesController::class, 'DeleteHotelMagazines']);
     Route::post('/delete-hotel-magazines-single-pdffile', [HotelMagazinesController::class, 'DeleteHotelMagazinesSinglePdffile']);
     
     Route::post('/login-user-hotel-magazines', [HotelMagazinesController::class, 'LoginUserHotelMagazines']);

     
         Route::post('/hotel-register', [HotelController::class, 'HotelRegister']);
 

    Route::post('/update-hotels', [HotelController::class, 'UpdateHotels']);
    Route::post('/delete-hotels', [HotelController::class, 'DeleteHotels']);

    Route::post('/add_special_offer', [HotelController::class, 'add_special_offer']);
   
    
 

    Route::post('/update_special_offer', [HotelController::class, 'update_special_offer']);
    Route::post('/delete_special_offer', [HotelController::class, 'delete_special_offer']);

    Route::post('/login-user-hotels', [HotelController::class, 'LoginUserHotels']);

    Route::post('/delete_single_hotel_image', [HotelController::class, 'delete_single_hotel_image']);
    Route::post('/update_single_hotel_image', [HotelController::class, 'update_single_hotel_image']);
    Route::post('/add_multiple_images_hotels', [HotelController::class, 'add_multiple_images_hotels']);
      
 



    Route::post('/add-hotel-ameties', [HotelController::class, 'AddHotelAmeties']);
    Route::post('/update-hotel-ameties', [HotelController::class, 'UpdateHotelAmeties']);
    Route::post('/delete-hotel-ameties', [HotelController::class, 'DeleteHotelAmeties']);
  

    Route::post('/sort_order_ameties', [HotelController::class, 'sort_order_ameties']);

    Route::post('/add-hotel-facilities', [HotelFacilitiesController::class, 'AddHotelFacilities']);
    Route::post('/update-hotel-facilities', [HotelFacilitiesController::class, 'UpdateHotelFacilities']);
    Route::post('/delete-hotel-facilities', [HotelFacilitiesController::class, 'DeleteHotelFacilities']);
   

    Route::post('/sort_order_facilities', [HotelFacilitiesController::class, 'sort_order_facilities']);

    


    Route::post('/add-media-kit', [MediakitController::class, 'AddMediaKit']);
    Route::post('/update-media-kit', [MediakitController::class, 'UpdateMediaKit']);
    Route::post('/delete-media-kit', [MediakitController::class, 'DeleteMediaKit']);
  


    Route::post('/add-banner', [BannerController::class, 'AddBanner']);

    Route::post('/update-banner', [BannerController::class, 'UpdateBanner']);
    Route::post('/delete-banner', [BannerController::class, 'DeleteBanner']);
  
    Route::post('/add-query', [QueriesController::class, 'AddQuery']);
   
    Route::post('/update-query', [QueriesController::class, 'UpdateQuery']);
    Route::post('/delete-query', [QueriesController::class, 'DeleteQuery']);
  

    Route::post('/send_query_reply', [QueriesController::class, 'send_query_reply']);
    

    Route::post('/add-team', [TeamController::class, 'AddTeam']);
  
    Route::post('/update-team', [TeamController::class, 'UpdateTeam']);
    Route::post('/delete-team', [TeamController::class, 'DeleteTeam']);
   

    Route::post('/add-voted-hotel', [VotedHotelController::class, 'AddVotedHotel']);
    Route::post('/update-voted-hotel', [VotedHotelController::class, 'UpdateVotedHotel']);
    Route::post('/delete-voted-hotel', [VotedHotelController::class, 'DeleteVotedHotel']);
   


    Route::post('/add-hotel-properties', [PropertiesController::class, 'AddHotelProperties']);
    Route::post('/update-hotel-properties', [PropertiesController::class, 'UpdateHotelProperties']);
    Route::post('/delete-hotel-properties', [PropertiesController::class, 'DeleteHotelProperties']);
    


    Route::post('/add-package-title', [PackagePriceController::class, 'AddPackageTitle']);

    Route::post('/add-package-price', [PackagePriceController::class, 'AddPackagePrice']);
    Route::post('/update-package-price', [PackagePriceController::class, 'UpdatePackagePrice']);
    Route::post('/delete-package-price', [PackagePriceController::class, 'DeletePackagePrice']);
   

    

    Route::post('/add-subscriber', [SubscribersController::class, 'AddSubscriber']);
    
    Route::post('/update-subscriber', [SubscribersController::class, 'UpdateSubscriber']);
    Route::post('/delete-subscriber', [SubscribersController::class, 'DeleteSubscriber']);



    // Route::post('/login-user-hotel-ameties', [HotelController::class, 'LoginUserHotelAmeties']);

    Route::post('/create-review', [ReviewController::class, 'CreateReview']);

    Route::post('/delete-review', [ReviewController::class, 'DeleteReview']);


    Route::post('/update-about-us', [About_Us_Controller::class, 'update_about_us']);
   
    Route::post('/update-magzine-distributed', [About_Us_Controller::class, 'update_magzine_distributed']);
    
    Route::post('/stripe-Single-Payment', [PaymentController::class, 'stripe_Single_Payment']);
    Route::post('/stripe-subscription-payment', [PaymentController::class, 'stripeSubscriptionPayment']);
    
    Route::post('/payment-success', [PaymentController::class, 'paypalPaymentSuccess']);

    
    Route::post('/create-review-category', [HotelController::class, 'Create_Review_Category']);
  
    Route::post('/create-review-topic', [HotelController::class, 'Create_Review_Topic']);
   
    Route::post('/create-guest-review', [HotelController::class, 'Create_Guest_Review']);
    Route::post('/get-reviews-by-topics', [HotelController::class, 'Get_Reviews_By_Topics']);
    Route::post('/get_review_average', [HotelController::class, 'get_review_average']);



});

Route::post('/add_home_info', [HomeInfoController::class, 'add_home_info']);
Route::post('/update_home_info', [HomeInfoController::class, 'update_home_info']);
Route::post('/delete_home_info', [HomeInfoController::class, 'delete_home_info']);


Route::post('/single_page_details', [HomeInfoController::class, 'single_page_details']);
Route::post('/update_single_home_info', [HomeInfoController::class, 'update_single_home_info']);

Route::post('/delete_magazine_cover', [HomeInfoController::class, 'delete_magazine_cover']);


// advertise with us 

Route::post('/add_advertise_with_us', [AdevertisingWithUsController::class, 'add_advertise_with_us']);
Route::post('/update_advertise_with_us', [AdevertisingWithUsController::class, 'update_advertise_with_us']);
Route::post('/delete_advertise_with_us', [AdevertisingWithUsController::class, 'delete_advertise_with_us']);
Route::post('/edit_advertise_with_us', [AdevertisingWithUsController::class, 'edit_advertise_with_us']);


// what we do

Route::post('/add_what_we_do', [WhatWeDoController::class, 'add_what_we_do']);
Route::post('/update_what_we_do', [WhatWeDoController::class, 'update_what_we_do']);
Route::post('/delete_what_we_do', [WhatWeDoController::class, 'delete_what_we_do']);
Route::post('/edit_what_we_do', [WhatWeDoController::class, 'edit_what_we_do']);




Route::post('/add_hotel_create_profile', [HotelCreateProfileController::class, 'add_hotel_create_profile']);
Route::post('/edit_hotel_create_profile', [HotelCreateProfileController::class, 'edit_hotel_create_profile']);


Route::post('/delete_hotel_create_profile', [HotelCreateProfileController::class, 'delete_hotel_create_profile']);

Route::post('/add_voting_details', [VotingDetailsController::class, 'add_voting_details']);
Route::post('/all_voting_details', [VotingDetailsController::class, 'all_voting_details']);







