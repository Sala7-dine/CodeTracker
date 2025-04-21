<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Récupérer les projets de l'utilisateur connecté
     */
    public function getUserProjects(Request $request)
    {
        // Vérification de l'authentification avec journalisation
        $authenticated = Auth::check();
        $user = Auth::user();
        
        Log::info('Status d\'authentification dans ProjectController', [
            'authenticated' => $authenticated,
            'user_id' => $user ? $user->id : null,
            'cookies' => $request->cookies->all(),
            'session_id' => session()->getId()
        ]);
        
        // Si pas d'utilisateur authentifié, renvoyer une erreur
        if (!$authenticated || !$user) {
            return response()->json([
                'error' => 'Non authentifié',
                'authenticated' => false
            ], 401);
        }
        
        try {
            // Récupérer les projets de l'utilisateur
            $projects = Project::with('languages')
                ->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();
                
            Log::info('Projets trouvés', [
                'count' => $projects->count(),
                'user_id' => $user->id
            ]);
            
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
            Log::error('Erreur lors de la récupération des projets', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erreur lors de la récupération des projets',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

