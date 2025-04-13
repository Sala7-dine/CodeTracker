<?php

use App\Http\Controllers\Api\ActivityController;
use Illuminate\Support\Facades\Route;

// Routes API pour CodeTrack
Route::post('/track', [ActivityController::class, 'track']);
Route::get('/data', [ActivityController::class, 'getActivities']);
Route::get('/stats/{project}', [ActivityController::class, 'getProjectStats']);

// Route pour récupérer tous les projets (sans auth pour démonstration)
Route::get('/projects', function() {
    $projects = \App\Models\Project::with('languages')->get()->map(function($project) {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'total_files' => $project->getTotalFiles(),
            'total_lines' => $project->getTotalLines(),
            'total_time' => $project->getTotalTime(),
            'formatted_time' => \App\Models\Language::formatTime($project->getTotalTime()),
            'languages' => $project->languages->take(3)->map(function($lang) {
                return [
                    'name' => $lang->name,
                    'files' => $lang->files,
                    'lines' => $lang->lines
                ];
            }),
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at
        ];
    });
    
    return response()->json($projects);
});