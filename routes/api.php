<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('update', 'update')->middleware('auth:sanctum');
    Route::post('delete', 'delete');
});
Route::controller(ChurchController::class)->group(function(){
    Route::get('churches','index');
    Route::post('churches/store','store');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::controller(AccountController::class)->prefix('accounts/')->group(function(){
        Route::get('','index');
        Route::post('store','store');
        Route::post('update','update');
        // Route::post('delete','delete');clear
        
    });
    Route::controller(PaymentController::class)->prefix('payments/')->group(function(){
        Route::get('','index');
        Route::post('store','store');
        Route::post('update', 'update');
    });
});