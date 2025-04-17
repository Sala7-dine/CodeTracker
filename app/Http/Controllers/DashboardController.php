<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::with('languages')->get();
        
        $number_of_projects = Project::with('languages')->count();
        
        // Récupérer le projet le plus récemment actif
        $activeProject = Project::orderBy('created_at', 'desc')->first();
        
        // Récupérer l'activité la plus récente
        $latestActivity = Activity::with('project')
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Calculer les statistiques globales
        $globalStats = [
            'totalFiles' => 0,
            'totalLines' => 0,
            'totalTime' => 0,
            'currentFile' => null,
            'languages' => [], // Pour stocker les stats par langage
            'environment' => [  // Informations d'environnement
                'editor' => 'VS Code',
                'os' => PHP_OS,
                'extensions' => [
                    ['name' => 'GitLens', 'active' => true],
                    ['name' => 'Prettier', 'active' => true],
                    ['name' => 'ESLint', 'active' => false],
                ]
            ],
            'currentProject' => null, // Pour stocker les infos du projet actuel
            'debugging_info' => [
                'latest_activity_id' => $latestActivity ? $latestActivity->id : null,
                'latest_project_id' => $activeProject ? $activeProject->id : null,
                'total_activities' => Activity::count(),
                'has_stats' => $latestActivity && !empty($latestActivity->stats) ? 'oui' : 'non'
            ]
        ];

        if ($latestActivity) {
            // Débogage pour voir les données stats
            Log::debug('Stats de la dernière activité', [
                'activity_id' => $latestActivity->id,
                'stats' => $latestActivity->stats,
                'raw_stats_type' => gettype($latestActivity->stats),
                'file_name' => $latestActivity->file_name,
                'file_path' => $latestActivity->file_path,
                'language' => $latestActivity->language,
                'duration' => $latestActivity->duration
            ]);
        }
        
        if ($latestActivity && $latestActivity->project) {
            $activeProject = $latestActivity->project;
            
            // Vérifier si stats existe et contient currentFile
            $timeSpent = $latestActivity->duration * 1000; // Valeur par défaut
            if (isset($latestActivity->stats) && is_array($latestActivity->stats) && 
                isset($latestActivity->stats['currentFile']) && 
                isset($latestActivity->stats['currentFile']['timeSpent'])) {
                $timeSpent = $latestActivity->stats['currentFile']['timeSpent'] * 1000;
            }
            
            // Informations sur le projet actuel
            $globalStats['currentProject'] = [
                'id' => $activeProject->id,
                'name' => $activeProject->name,
                'totalFiles' => $activeProject->getTotalFiles(),
                'totalLines' => $activeProject->getTotalLines(),
                'totalTime' => $activeProject->getTotalTime(),
                'timeSpent' => $timeSpent,
                'formattedTime' => Language::formatTime($activeProject->getTotalTime())
            ];
            
            // Informations sur le fichier actuel avec vérifications supplémentaires
            $globalStats['currentFile'] = [
                'name' => $latestActivity->file_name ?? basename($latestActivity->file_path ?? 'Inconnu'),
                'path' => $latestActivity->file_path ?? 'Chemin inconnu',
                'language' => $latestActivity->language ?? 'inconnu',
                'lines' => $latestActivity->lines ?? 0,
                'timeSpent' => $timeSpent,
                'formattedTime' => Language::formatTime($latestActivity->duration * 1000),
                'lastActive' => $latestActivity->created_at->diffForHumans(),
                'edits' => rand(15, 30), // À remplacer par des données réelles
                'efficiency' => rand(85, 99) . '%', // À remplacer par des données réelles
                'project' => $activeProject->name
            ];

            // Récupérer toutes les technologies (langages) du projet actuel uniquement
            $projectLanguages = Language::where('project_id', $activeProject->id)->get();
            
            foreach ($projectLanguages as $language) {
                $langName = $language->name;
                
                // Identifier le bon champ pour le temps
                $languageTime = 0;
                if (isset($language->time_spent)) {
                    $languageTime = $language->time_spent;
                } elseif (isset($language->time_ms)) {
                    $languageTime = $language->time_ms;
                } elseif (isset($language->duration)) {
                    $languageTime = $language->duration * 1000;
                }
                
                $globalStats['languages'][$langName] = [
                    'name' => $langName,
                    'files' => $language->files,
                    'lines' => $language->lines,
                    'time_spent' => $languageTime,
                    'formattedTime' => Language::formatTime($languageTime)
                ];
            }
        }
        
        // Calculer les totaux à partir de tous les projets pour les statistiques globales
        foreach ($projects as $project) {
            $globalStats['totalFiles'] += $project->getTotalFiles();
            $globalStats['totalLines'] += $project->getTotalLines();
            $globalStats['totalTime'] += $project->getTotalTime();
        }
        
        // Formater le temps total global
        $globalStats['formattedTime'] = Language::formatTime($globalStats['totalTime']);
        
        return view('dashboard.user.index', compact('number_of_projects', 'projects', 'globalStats'));
    }
    
    public function project($id)
    {
        $project = Project::with('languages', 'activities')->findOrFail($id);
     
        $activities = $project->activities()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        // Récupérer le fichier actuel (le plus récemment modifié) pour ce projet
        $currentFile = $project->activities()
            ->orderBy('created_at', 'desc')
            ->first();
            
        // Formater le temps total pour le projet
        $formattedTime = Language::formatTime($project->getTotalTime());

        return view('dashboard.user.project', compact('project', 'activities', 'currentFile', 'formattedTime'));
    }

    public function projects()
    {
        $projects = Project::with('languages')->get();
        
        return view('dashboard.user.projects', compact('projects'));
    }
}