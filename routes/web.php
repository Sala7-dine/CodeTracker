<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController as ControllersDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post("/logout" , [AuthController::class , 'logout'])->name("logout");

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'user') {
        return redirect()->route('user.dashboard');
    }else if(Auth::check() && Auth::user()->role === 'admin'){
        return redirect()->route('admin.dashboard');
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
    Route::get('/dashboard/projects', [ProjetController::class, 'projects'])->name('user.dashboard.projects');
    Route::get('/dashboard/project/{id}', [ProjetController::class, 'project'])->name('user.dashboard.project');
    Route::get('/dashboard/project/{id}/export', [ProjetController::class, 'exportPdf'])->name('user.dashboard.project.export');
    Route::get('/dashboard/profile', [UserController::class, 'profile'])->name('profile');

    // Routes pour le profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-image', [ProfileController::class, 'updateProfileImage'])->name('profile.update.image');
    Route::post('/profile/delete', [ProfileController::class, 'deleteAccount'])->name('profile.delete');
    
    // Route pour générer une clé API
    Route::post('/profile/api-key', [ProfileController::class, 'generateApiKey'])->name('profile.api-key');

    Route::get('/user/dashboard/statistiques' , [StatistiqueController::class, "index"])->name("user.dashboard.statistiques");

});


// Routes administrateur
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Routes existantes
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Routes de gestion des utilisateurs
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::put('/users/{id}/change-role', [AdminController::class, 'changeUserRole'])->name('users.changeRole');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
});


// Route::get('/', function () {
//     return redirect()->route('dashboard');
// });






