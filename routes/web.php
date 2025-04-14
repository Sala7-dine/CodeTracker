<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController as ControllersDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post("/logout" , [AuthController::class , 'logout'])->name("logout");

Route::get('/', function () {
    if (Auth::check()) {
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


Route::middleware(['auth'])->group(function() {
    Route::get('/user/dashboard', [ControllersDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/dashboard/projects', [ControllersDashboardController::class, 'projects'])->name('user.dashboard.projects');
    Route::get('/dashboard/project/{id}', [ControllersDashboardController::class, 'project'])->name('user.dashboard.project');
    Route::get('/dashboard/profile', [UserController::class, 'profile'])->name('profile');
});


Route::middleware('auth')->controller(AdminController::class)->group( function(){

    Route::get("/admin/dashboard" , "index")->name('admin.dashboard');

});


// Route::get('/', function () {
//     return redirect()->route('dashboard');
// });






