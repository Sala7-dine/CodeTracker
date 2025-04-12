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
        $recentActivities = Activity::with('project')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        // Calculer les statistiques globales
        $globalStats = [
            'totalFiles' => 0,
            'totalLines' => 0,
            'totalTime' => 0,
            'currentFile' => null
        ];
        
        // Récupérer l'activité la plus récente pour déterminer le fichier actuel
        $latestActivity = Activity::with('project')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($latestActivity) {
            $globalStats['currentFile'] = [
                'name' => $latestActivity->file_name,
                'path' => $latestActivity->file_path,
                'language' => $latestActivity->language ?? 'inconnu',
                'lines' => $latestActivity->lines,
                'timeSpent' => $latestActivity->duration * 1000, // Convertir en ms pour formater
                'formattedTime' => Language::formatTime($latestActivity->duration * 1000)
            ];
        }
        
        // Calculer les totaux à partir de tous les projets
        foreach ($projects as $project) {
            $globalStats['totalFiles'] += $project->getTotalFiles();
            $globalStats['totalLines'] += $project->getTotalLines();
            $globalStats['totalTime'] += $project->getTotalTime();
        }
        
        // Formater le temps total
        $globalStats['formattedTime'] = Language::formatTime($globalStats['totalTime']);
        
        return view('dashboard.index', compact('projects', 'recentActivities', 'globalStats'));
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