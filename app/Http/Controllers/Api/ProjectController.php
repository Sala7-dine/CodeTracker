<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function getUserProjects(Request $request)
    {

        $authenticated = Auth::check();
        $user = Auth::user();
        
        if (!$authenticated || !$user) {
            return response()->json([
                'error' => 'Non authentifié',
                'authenticated' => false
            ], 401);
        }
        
        try {
          
            $projects = Project::with('languages')
                ->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();
            
            $formattedProjects = $projects->map(function($project) {
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
            
            return response()->json($formattedProjects);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'Erreur lors de la récupération des projets',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

