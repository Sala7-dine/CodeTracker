<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Language;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::with('languages')->get();
        $number_of_projects = Project::with('languages')->count();
        
        // Récupérer l'activité la plus récente pour déterminer le fichier et projet actuels
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
            'currentProject' => null // Pour stocker les infos du projet actuel
        ];
        
        if ($latestActivity && $latestActivity->project) {
            $activeProject = $latestActivity->project;
            
            // Informations sur le projet actuel
            $globalStats['currentProject'] = [
                'id' => $activeProject->id,
                'name' => $activeProject->name,
                'totalFiles' => $activeProject->getTotalFiles(),
                'totalLines' => $activeProject->getTotalLines(),
                'totalTime' => $activeProject->getTotalTime(),
                'formattedTime' => Language::formatTime($activeProject->getTotalTime())
            ];
            
            // Informations sur le fichier actuel
            $globalStats['currentFile'] = [
                'name' => $latestActivity->file_name,
                'path' => $latestActivity->file_path,
                'language' => $latestActivity->language ?? 'inconnu',
                'lines' => $latestActivity->lines,
                // 'timeSpent' => $latestActivity->duration * 1000, // Convertir en ms pour formater
                'formattedTime' => Language::formatTime($latestActivity->duration * 1000),
                'lastActive' => $latestActivity->created_at->diffForHumans(),
                'edits' => rand(15, 30), // À remplacer par des données réelles
                'efficiency' => rand(85, 99) . '%', // À remplacer par des données réelles
                'project' => $activeProject->name
            ];
            
            // Récupérer toutes les technologies (langages) du projet actuel uniquement
            $projectLanguages = Language::where('project_id', $activeProject->id)
               
                ->get();
            
            foreach ($projectLanguages as $language) {
                $langName = $language->name;
                
                $globalStats['languages'][$langName] = [
                    'name' => $langName,
                    'files' => $language->files,
                    'lines' => $language->lines,
                    // 'time_spent' => $language->time_spent,
                    'formattedTime' => Language::formatTime($language->time_spent)
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

        return view('dashboard.project', compact('project', 'activities', 'currentFile', 'formattedTime'));
    }
}