<?php

use App\Http\Controllers\Api\ActivityController;
use Illuminate\Support\Facades\Route;

// Routes API pour CodeTrack
Route::post('/track', [ActivityController::class, 'track']);
Route::get('/data', [ActivityController::class, 'getActivities']);
Route::get('/stats/{project}', [ActivityController::class, 'getProjectStats']);