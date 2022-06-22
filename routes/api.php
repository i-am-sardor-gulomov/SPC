<?php

use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

//Avtarizatsiya talab qilinmagan endpointlar
Route::post('login', [UserController::class, 'login']);                 //200


//Avtarizatsiya talab qilingan endpointlar
Route::middleware('auth:api')->group(function(){
    Route::get('logout', [UserController::class, 'logout']);            //204
});


//Admin endpointlari
Route::middleware(['auth:api', 'scope:admin'])->group(function(){
    Route::get('user', [UserController::class, 'index']);               //200
    Route::post('user', [UserController::class, 'store']);              //201
    Route::get('user/{user}', [UserController::class, 'show']);         //200
    Route::put('user/{user}', [UserController::class, 'update']);       //200
    Route::post('user/{user}', [UserController::class, 'destroy']);     //204
});
