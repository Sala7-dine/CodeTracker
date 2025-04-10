<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post("/logout" , [AuthController::class , 'logout'])->name("logout");

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }
    return app(UserController::class)->home();
})->name("home");


Route::middleware('guest')->controller(AuthController::class)->group( function(){

    Route::get("/register" , 'showRegister')->name("showRegister");
    Route::get("/login" , 'showLogin')->name("showLogin");

    Route::post("/register" , 'register')->name("register");
    Route::post("/login" , 'login')->name("login");

});


Route::middleware('auth')->controller(UserController::class)->group( function(){

    Route::get("/user/dashboard" , "index")->name('user.dashboard');

});





