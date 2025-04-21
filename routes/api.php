<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Routes API pour CodeTrack
Route::post('/track', [ActivityController::class, 'track']);
Route::get('/data', [ActivityController::class, 'getActivities']);
Route::get('/stats/{project}', [ActivityController::class, 'getProjectStats']);

// Route pour récupérer les projets (avec auth)
Route::middleware(['web', 'auth'])->get('/projects', [ProjectController::class, 'getUserProjects']);
// Routes publiques (sans auth)
Route::get('/projects-public', function() {
    // Pour les démos ou tests sans authentification
    return response()->json([]);
});

// Route pour tester l'API key
Route::get('/test-api-key', function (Request $request) {
    $apiKey = $request->header('X-API-KEY') ?: $request->api_key;
    
    if (!$apiKey) {
        return response()->json([
            'success' => false,
            'message' => 'Aucune API key fournie'
        ], 400);
    }
    
    $user = \App\Models\User::where('api_key', $apiKey)->first();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'API key invalide'
        ], 401);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'API key valide',
        'user' => [
            'id' => $user->id,
            'name' => $user->firstname . ' ' . $user->lastname
        ]
    ]);
});

// Route pour obtenir l'utilisateur actuel
Route::get('/get-current-user', function (Request $request) {
    $user = null;
    
    // Vérifier si l'utilisateur est authentifié
    if (Auth::check()) {
        $user = Auth::user();
    } 
    // Vérifier l'API key s'il y en a une
    elseif ($request->hasHeader('X-API-KEY')) {
        $apiKey = $request->header('X-API-KEY');
        $user = \App\Models\User::where('api_key', $apiKey)->first();
    }
    
    if ($user) {
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->firstname . ' ' . $user->lastname
            ]
        ]);
    }
    
    return response()->json([
        'authenticated' => false,
        'message' => 'Non authentifié'
    ], 401);
});