<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatabaseMigrationController;

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
});