<?php

use App\Http\Controllers\v1\AppController;
use App\Http\Controllers\v1\CredentialController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

//Avtarizatsiya talab qilinmagan endpointlar
Route::post('login', [UserController::class, 'login']);                         //200

//Avtarizatsiya talab qilingan endpointlar
Route::middleware('auth:api')->group(function(){

    //Umumiy
    Route::get('logout', [UserController::class, 'logout']);                    //204

    //Credential ustida amallar
    Route::post('app/{app}/credential', [CredentialController::class, 'store']);//201
    Route::get('app/{app}/credential', [CredentialController::class, 'show']);  //200
    Route::put('app/{app}/credential', [CredentialController::class, 'update']);//200

    //User ustida amallar
    Route::get('user/profile', [UserController::class, 'profileShow']);         //200
    Route::put('user/profile', [UserController::class, 'profileUpdate']);       //200

    //App ustida amallar
    Route::get('app', [AppController::class, 'index']);                         //200
    Route::get('app/{app}', [AppController::class, 'show']);                    //200
});


//Admin endpointlari
Route::middleware(['auth:api', 'scope:admin'])->group(function(){

    //User ustida amallar
    Route::get('user', [UserController::class, 'index']);                       //200
    Route::post('user', [UserController::class, 'store']);                      //201
    Route::get('user/{user}', [UserController::class, 'show']);                 //200
    Route::put('user/{user}', [UserController::class, 'update']);               //200
    Route::post('user/{user}', [UserController::class, 'destroy']);             //204

    //App ustida amallar
    Route::post('app', [AppController::class, 'store']);                        //201
    Route::put('app/{app}', [AppController::class, 'update']);                  //200
    Route::delete('app/{app}', [AppController::class, 'destroy']);              //204
});

