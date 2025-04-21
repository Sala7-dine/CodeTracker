<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Récupérer les projets de l'utilisateur connecté
     */
    public function getUserProjects(Request $request)
    {
        $user = Auth::user();
        
        // Si pas d'utilisateur connecté, renvoyer un tableau vide
            // if (!$userId) {
            //     return response()->json([]);
            // }
            
        $projects = Project::with('languages')
            ->where("user_id" , $$user->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'total_files' => $project->getTotalFiles(),
                    'total_lines' => $project->getTotalLines(),
                    'total_time' => $project->getTotalTime(),
                    'formatted_time' => Language::formatTime($project->getTotalTime()),
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
    }
}
