<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Language;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function track(Request $request)
    {
        // Valider les données reçues
        $validator = Validator::make($request->all(), [
            'file' => 'required|string',
            'project' => 'required|string',
            'duration' => 'required|integer|min:1',
            'timestamp' => 'required|integer',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Données incomplètes ou invalides',
                'details' => $validator->errors()
            ], 400);
        }
        
        try {
            // Obtenir un utilisateur valide (soit authentifié, soit par défaut)
            $userId = null;
            
            // Option 1: Essayer de récupérer l'utilisateur authentifié
            if (auth()->check()) {
                $userId = auth()->id();
            }
            
            // Option 2: Utiliser un utilisateur passé dans la requête
            if (!$userId && $request->has('user_id')) {
                $userId = $request->user_id;
            }
            
            // Option 3: Utiliser un API key si présent dans l'en-tête
            if (!$userId && $request->hasHeader('X-API-KEY')) {
                $apiKey = $request->header('X-API-KEY');
                $user = User::where('api_key', $apiKey)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
            
            // Option 4: Utiliser l'utilisateur par défaut (ici l'ID 1)
            if (!$userId) {
                // Pour le développement/démo, utiliser le premier utilisateur
                $user = User::first();
                if ($user) {
                    $userId = $user->id;
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Aucun utilisateur trouvé dans le système'
                    ], 500);
                }
            }
            
            // Récupérer ou créer le projet avec user_id
            $project = Project::firstOrCreate(
                ['name' => $request->project, 'user_id' => $userId],
                [
                    'user_id' => $userId,
                    'description' => 'Projet créé automatiquement',
                    'environment_info' => [
                        'editor' => 'VS Code',
                        'os' => PHP_OS,
                    ]
                ]
            );
            
            // Extraire le nom du fichier depuis le chemin
            $fileName = basename($request->file);
            
            // Créer l'activité
            $activity = new Activity([
                'file_path' => $request->file,
                'file_name' => $fileName,
                'duration' => $request->duration,
                'activity_time' => now(), // Ou utiliser le timestamp du client converti
            ]);
            
            // Traiter les statistiques complètes si elles sont présentes
            if ($request->has('stats')) {
                $stats = $request->stats;
                $activity->stats = $stats;
                
                // Mettre à jour les informations d'environnement du projet
                if (isset($stats['environment'])) {
                    $project->environment_info = $stats['environment'];
                    $project->save();
                }
                
                // Mettre à jour les informations du fichier courant
                if (isset($stats['currentFile'])) {
                    $currentFile = $stats['currentFile'];
                    $activity->language = $currentFile['language'] ?? null;
                    $activity->lines = $currentFile['lineCount'] ?? 0;
                }
                
                // Mettre à jour les statistiques par langage
                if (isset($stats['languages']) && is_array($stats['languages'])) {
                    foreach ($stats['languages'] as $langName => $langData) {
                        // Créer ou mettre à jour les statistiques de langage
                        Language::updateOrCreate(
                            ['project_id' => $project->id, 'name' => $langName],
                            [
                                'files' => $langData['files'] ?? 0,
                                'lines' => $langData['lines'] ?? 0,
                                'time_ms' => $langData['time'] ?? 0,
                            ]
                        );
                    }
                }
            }
            
            // Associer l'activité au projet et sauvegarder
            $project->activities()->save($activity);
            
            // Log pour debug
            Log::info('Activité enregistrée', [
                'project' => $request->project,
                'file' => $request->file,
                'duration' => $request->duration
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Activité enregistrée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'activité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors du traitement de la requête',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getActivities()
    {
        $activities = Activity::with('project')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        return response()->json($activities);
    }
    
    public function getProjectStats($projectName)
    {
        $project = Project::where('name', $projectName)
            ->with('languages')
            ->first();
        
        if (!$project) {
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }
        
        // Récupérer la dernière activité
        $lastActivity = $project->activities()
            ->orderBy('created_at', 'desc')
            ->first();
        
        $stats = [
            'project' => $project->name,
            'environment' => $project->environment_info,
            'totals' => [
                'files' => $project->getTotalFiles(),
                'lines' => $project->getTotalLines(),
                'time' => $project->getTotalTime(),
                'formatted_time' => Language::formatTime($project->getTotalTime())
            ],
            'lastActivity' => $lastActivity ? [
                'file' => $lastActivity->file_name,
                'path' => $lastActivity->file_path,
                'language' => $lastActivity->language,
                'lines' => $lastActivity->lines,
                'time' => $lastActivity->created_at->format('Y-m-d H:i:s')
            ] : null,
            'languages' => []
        ];
        
        foreach ($project->languages as $language) {
            $stats['languages'][$language->name] = [
                'files' => $language->files,
                'lines' => $language->lines,
                'time' => $language->time_ms,
                'formatted_time' => $language->getFormattedTime()
            ];
        }
        
        return response()->json($stats);
    }
}