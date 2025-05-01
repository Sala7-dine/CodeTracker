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
use Carbon\Carbon;

class ActivityController extends Controller
{
    private $inactivityThreshold;
    
    public function __construct()
    {
        $this->inactivityThreshold = config('codetrack.inactivity_threshold', 120);
    }
    
    public function track(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|string',
            'project' => 'required|string',
            'duration' => 'required|integer|min:1',
            'timestamp' => 'required|integer',
            'isActive' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Données incomplètes ou invalides',
                'details' => $validator->errors()
            ], 400);
        }
        
        $isActive = $request->has('isActive') ? (bool)$request->isActive : true;
        Log::debug('État d\'activité détecté', ['isActive' => $isActive]);
        
        if (!$isActive){
            
            return response()->json([
                'success' => true,
                'message' => 'Requête ignorée - utilisateur inactif',
                'status' => 'inactive'
            ]);
        }
        
        try {
            $userId = null;
            
            if (auth()->check()) {
                $userId = auth()->id();
            }
            
            else if ($request->hasHeader('X-API-KEY')) {
                $apiKey = $request->header('X-API-KEY');
                $user = User::where('api_key', $apiKey)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
            
            else if ($request->has('user_id')) {
                $userExists = User::where('id', $request->user_id)->exists();
                if ($userExists) {
                    $userId = $request->user_id;
                }
            }
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Utilisateur non authentifié',
                    'message' => 'Pour enregistrer vos activités, vous devez être connecté ou utiliser une API key valide',
                    'resolution' => 'Veuillez vous connecter à l\'application web puis réessayer'
                ], 401);
            }
            
            try {

                $project = Project::where('name', $request->project)
                    ->where('user_id', $userId)
                    ->first();
                    
                if (!$project) {
                    $project = new Project();
                    $project->name = $request->project;
                    $project->user_id = $userId;
                    $project->description = 'Projet créé automatiquement';
                    $project->environment_info = [
                        'editor' => 'VS Code',
                        'os' => PHP_OS,
                    ];
                    $project->save();
                    
                }
            } catch (\Exception $e) {
                
                return response()->json([
                    'success' => false,
                    'error' => 'Erreur lors de la création du projet',
                    'message' => $e->getMessage()
                ], 500);
            }

            $fileName = basename($request->file);
            
            $lastActivity = Activity::where('project_id', $project->id)
                ->where('file_path', $request->file)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $activity = new Activity([
                'file_path' => $request->file,
                'file_name' => $fileName,
                'duration' => $request->duration,
                'activity_time' => Carbon::createFromTimestamp($request->timestamp),
                'last_activity_time' => now(),
                'activity_status' => 'active',
                'project_id' => $project->id,
            ]);

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $language = $extension ? strtolower($extension) : null;
            $activity->language = $language; 
            
            if ($lastActivity) {
                $timeSinceLastActivity = now()->diffInSeconds($lastActivity->created_at);
                
                if ($timeSinceLastActivity > ($this->inactivityThreshold + $request->duration)) {
                    $activity->activity_status = 'resumed';
                    $activity->inactive_gap = $timeSinceLastActivity - $request->duration;
                
                }
            }
            
            if ($request->has('stats') && is_array($request->stats)) {
                $stats = $request->stats;
                
                $activity->stats = $stats;
                
                if (isset($stats['environment'])) {
                    $project->environment_info = $stats['environment'];
                    $project->save();
                }
                
                if (isset($stats['currentFile'])) {
                    $currentFile = $stats['currentFile'];
                    $activity->language = $currentFile['language'] ?? null;
                    $activity->lines = $currentFile['lineCount'] ?? 0;
                }
                
                if (isset($stats['languages']) && is_array($stats['languages'])) {
                    foreach ($stats['languages'] as $langName => $langData) {
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
            } else if ($request->has('stats') && is_string($request->stats)) {
                try {
                    $stats = json_decode($request->stats, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $activity->stats = $stats;
                        
                        if (isset($stats['environment'])) {
                            $project->environment_info = $stats['environment'];
                            $project->save();
                        }
                        
                        if (isset($stats['currentFile'])) {
                            $currentFile = $stats['currentFile'];
                            $activity->language = $currentFile['language'] ?? null;
                            $activity->lines = $currentFile['lineCount'] ?? 0;
                        }
                        
                        if (isset($stats['languages']) && is_array($stats['languages'])) {
                            foreach ($stats['languages'] as $langName => $langData) {
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
                    } else {
                        Log::error('Erreur de décodage JSON', ['error' => json_last_error_msg()]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception lors du décodage JSON', ['error' => $e->getMessage()]);
                }
            } else {
                Log::debug('Aucune statistique reçue dans la requête');
            }
            
            $activity->user_id = $userId;
            
            if ($project) {
                $project->activities()->save($activity);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Activité enregistrée avec succès',
                    'data' => [
                        'status' => $activity->activity_status,
                        'inactivity_threshold' => $this->inactivityThreshold,
                        'inactive_gap' => $activity->inactive_gap ?? 0,
                        'project_id' => $project->id
                    ]
                ]);
            } else {
                throw new \Exception("Le projet n'a pas pu être créé ou récupéré correctement");
            }
            
        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors du traitement de la requête',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getActivities(Request $request){

        $query = Activity::with('project');
        
        if ($request->has('project')) {
            $project = Project::where('name', $request->project)->first();
            if ($project) {
                $query->where('project_id', $project->id);
            }
        }
        
        if ($request->has('status')) {
            $query->where('activity_status', $request->status);
        }
        
        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }
        
        $activities = $query->orderBy('created_at', 'desc')
            ->take($request->limit ?? 50)
            ->get();
        
        return response()->json($activities);
    }
    
    public function getProjectStats($projectName){
        
        $project = Project::where('name', $projectName)
            ->with('languages')
            ->first();
        
        if (!$project) {
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }
        
        $lastActivity = $project->activities()
            ->orderBy('created_at', 'desc')
            ->first();
        
        $activitiesQuery = $project->activities();
        $totalTime = $activitiesQuery->sum('duration');
        $inactiveGaps = $activitiesQuery->where('inactive_gap', '>', 0)->sum('inactive_gap');
        $activityCount = $activitiesQuery->count();
        $resumedActivities = $activitiesQuery->where('activity_status', 'resumed')->count();
        
        $totalElapsedTime = $totalTime + $inactiveGaps;
        $activePercentage = $totalElapsedTime > 0 
            ? round(($totalTime / $totalElapsedTime) * 100) 
            : 100;
        
        $stats = [
            'project' => $project->name,
            'environment' => $project->environment_info,
            'totals' => [
                'files' => $project->getTotalFiles(),
                'lines' => $project->getTotalLines(),
                'time' => $project->getTotalTime(),
                'formatted_time' => Language::formatTime($project->getTotalTime())
            ],
            'activity' => [
                'total_sessions' => $activityCount,
                'resumed_sessions' => $resumedActivities,
                'active_percentage' => $activePercentage,
                'total_active_time' => $totalTime,
                'total_inactive_gaps' => $inactiveGaps,
                'formatted_active_time' => Language::formatTime($totalTime * 1000),
                'formatted_inactive_gaps' => Language::formatTime($inactiveGaps * 1000)
            ],
            'lastActivity' => $lastActivity ? [
                'file' => $lastActivity->file_name,
                'path' => $lastActivity->file_path,
                'language' => $lastActivity->language,
                'lines' => $lastActivity->lines,
                'time' => $lastActivity->created_at->format('Y-m-d H:i:s'),
                'status' => $lastActivity->activity_status
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
    
    
    public function getActivityTimeline($projectName)
    {
        $project = Project::where('name', $projectName)->first();
        
        if (!$project) {
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }
        
        $activities = $project->activities()
            ->selectRaw('DATE(created_at) as date, SUM(duration) as total_duration, 
                        SUM(inactive_gap) as total_inactive, 
                        COUNT(*) as session_count,
                        SUM(CASE WHEN activity_status = "resumed" THEN 1 ELSE 0 END) as resumed_count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        $timeline = [];
        
        foreach ($activities as $day) {
            $timeline[] = [
                'date' => $day->date,
                'active_time' => $day->total_duration,
                'inactive_time' => $day->total_inactive,
                'session_count' => $day->session_count,
                'resumed_count' => $day->resumed_count,
                'formatted_active_time' => Language::formatTime($day->total_duration * 1000),
                'formatted_inactive_time' => Language::formatTime($day->total_inactive * 1000),
            ];
        }
        
        return response()->json($timeline);
    }
}