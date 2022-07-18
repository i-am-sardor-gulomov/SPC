<?php

use App\Http\Controllers\v1\AppController;
use App\Http\Controllers\v1\CredentialController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

//Avtarizatsiya talab qilinmagan endpointlar
Route::post('login', [UserController::class, 'login']);                          //200

//Avtarizatsiya talab qilingan endpointlar
Route::middleware('auth:api')->group(function(){

    //Umumiy
    Route::get('logout', [UserController::class, 'logout']);                     //204
    Route::get('profile', [UserController::class, 'profileShow']);               //200
    Route::put('profile', [UserController::class, 'profileUpdate']);             //200

    //Credential ustida amallar
    Route::post('apps/{app}/credential', [CredentialController::class, 'store']);//201
    Route::get('apps/{app}/credential', [CredentialController::class, 'show']);  //200
    Route::put('apps/{app}/credential', [CredentialController::class, 'update']);//200


    //App ustida amallar
    Route::get('apps', [AppController::class, 'index']);                         //200
    Route::get('apps/{app}', [AppController::class, 'show']);                    //200
});


//Admin endpointlari
Route::middleware(['auth:api', 'scope:admin'])->group(function(){

    //User ustida amallar
    Route::get('users', [UserController::class, 'index']);                       //200
    Route::post('users', [UserController::class, 'store']);                      //201
    Route::get('users/{user}', [UserController::class, 'show'])
        ->where("user", '[0-9]+');                                               //200
    Route::put('users/{user}', [UserController::class, 'update']);               //200
    Route::post('users/{user}', [UserController::class, 'destroy']);             //204
    Route::patch('users/{user}', [UserController::class, 'activenessUpdate']);   //200

    //App ustida amallar
    Route::post('apps', [AppController::class, 'store']);                        //201
    Route::put('apps/{app}', [AppController::class, 'update']);                  //200
    Route::delete('apps/{app}', [AppController::class, 'destroy']);              //204
    Route::patch('apps/{app}', [AppController::class, 'activenessUpdate']);      //200
});

