<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('user/update', 'update')->middleware('auth:sanctum');
    Route::post('user/delete', 'delete');
});
Route::controller(ChurchController::class)->group(function(){
    Route::get('churches','index');
    Route::post('churches/store','store');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::controller(AccountController::class)->prefix('accounts')->group(function(){
        Route::get('/','index');
        Route::post('/store','store');
        Route::post('/update/{id}','update');
        // Route::post('delete','delete');clear
        
    });
    Route::controller(PaymentController::class)->prefix('payments/')->group(function(){
        Route::get('','index');
        Route::post('store','store');
        Route::post('callback/{id}','Callback');
        Route::post('update', 'update');
    });
    Route::controller(HomeController::class)->group(function(){
        Route::get('/fetchAccountingData', 'fetchAccountingData');
    });
});