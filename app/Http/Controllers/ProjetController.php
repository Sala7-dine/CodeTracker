<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Language;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB; // Ajoutez cet import

class ProjetController extends Controller
{
    public function project($id)
    {
        // Récupérer le projet spécifié ET vérifier qu'il appartient à l'utilisateur connecté
        $project = Project::with('languages')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Récupérer les activités de l'utilisateur pour ce projet spécifique
        $activities = Activity::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        // Récupérer le fichier actuel (le plus récemment modifié) pour ce projet
        $currentFile = $activities->first();
        
        // Formater le temps total pour le projet
        $formattedTime = Language::formatTime($project->getTotalTime());

        // Collecter les données pour les graphiques
        $chartData = [
            'daily' => $this->getDailyActivityData($project->id),
            'weekly' => $this->getWeeklyActivityData($project->id),
            'monthly' => $this->getMonthlyActivityData($project->id)
        ];
        
        // Calculer les statistiques de productivité
        $productivityStats = $this->getProductivityStats($project->id);
        
        // Obtenir des données sur l'activité par jour et par heure (heatmap)
        $heatmapData = $this->getHeatmapData($project->id);
        
        // Récupérer les données du calendrier de contribution
        $contributionCalendar = $this->getContributionCalendar($project->id);
        
        return view('dashboard.user.project', compact(
            'project', 
            'activities', 
            'currentFile', 
            'formattedTime', 
            'chartData', 
            'productivityStats',
            'heatmapData',
            'contributionCalendar'
        ));
    }

    public function projects()
    {
        // Récupérer uniquement les projets de l'utilisateur connecté
        $projects = Project::where('user_id', Auth::id())->with('languages')->get();
        
        return view('dashboard.user.projects', compact('projects'));
    }
    
    /**
     * Récupère les données d'activité quotidienne
     */
    private function getDailyActivityData($projectId)
    {
        $today = Carbon::today();
        $result = [];
        
        // Labels pour les heures de la journée
        $labels = [];
        $data = [];
        
        // Récupérer les données pour chaque heure
        for ($hour = 0; $hour < 24; $hour++) {
            $start = $today->copy()->startOfDay()->addHours($hour);
            $end = $start->copy()->addHour();
            
            // Format de l'heure pour l'affichage
            $labels[] = $start->format('H') . 'h';
            
            // Récupérer la durée d'activité pour cette heure (en heures)
            $duration = Activity::where('project_id', $projectId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('duration') / 3600;
            
            $data[] = round($duration, 2);
        }
        
        // Trouver l'heure la plus productive
        $maxHour = array_search(max($data), $data);
        $mostProductiveHour = $maxHour !== false ? $labels[$maxHour] : '00h';
        
        return [
            'labels' => $labels,
            'data' => $data,
            'title' => "Activité de codage aujourd'hui",
            'mostProductiveHour' => $mostProductiveHour,
            'totalHours' => array_sum($data)
        ];
    }
    
    /**
     * Récupère les données d'activité hebdomadaire
     */
    private function getWeeklyActivityData($projectId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $data = [];
        
        // Récupérer les données pour chaque jour
        for ($day = 0; $day < 7; $day++) {
            $date = $startOfWeek->copy()->addDays($day);
            
            // Si on est sur un jour futur, mettre une valeur nulle
            if ($date->isAfter(Carbon::now())) {
                $data[] = 0;
            } else {
                // Récupérer la durée d'activité pour ce jour (en heures)
                $duration = Activity::where('project_id', $projectId)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('duration') / 3600;
                
                $data[] = round($duration, 2);
            }
        }
        
        // Trouver le jour le plus productif
        $maxDay = array_search(max($data), $data);
        $mostProductiveDay = $maxDay !== false ? $labels[$maxDay] : 'Lun';
        
        return [
            'labels' => $labels,
            'data' => $data,
            'title' => "Activité de codage cette semaine",
            'mostProductiveDay' => $mostProductiveDay,
            'totalHours' => array_sum($data)
        ];
    }
    
    /**
     * Récupère les données d'activité mensuelle
     */
    private function getMonthlyActivityData($projectId)
    {
        $now = Carbon::now();
        $labels = [];
        $data = [];
        
        // Récupérer les données pour les 4 dernières semaines
        for ($week = 3; $week >= 0; $week--) {
            $endOfWeek = $now->copy()->subWeeks($week);
            $startOfWeek = $endOfWeek->copy()->subDays(6);
            
            // Format du label: "1-7 Mai"
            $monthName = $this->getMonthName($endOfWeek->format('n'));
            $label = $startOfWeek->format('j') . '-' . $endOfWeek->format('j') . ' ' . $monthName;
            $labels[] = $label;
            
            // Récupérer la durée d'activité pour cette semaine (en heures)
            $duration = Activity::where('project_id', $projectId)
                ->whereBetween('created_at', [
                    $startOfWeek->startOfDay(),
                    $endOfWeek->endOfDay()
                ])
                ->sum('duration') / 3600;
            
            $data[] = round($duration, 2);
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'title' => "Activité ces 4 dernières semaines",
            'totalHours' => array_sum($data)
        ];
    }
    
    /**
     * Récupère les statistiques de productivité
     */
    private function getProductivityStats($projectId)
    {
        // Périodes pour calculer la tendance
        $lastWeek = [
            Carbon::now()->subDays(14)->startOfDay(),
            Carbon::now()->subDays(7)->endOfDay()
        ];
        
        $thisWeek = [
            Carbon::now()->subDays(7)->startOfDay(),
            Carbon::now()->endOfDay()
        ];
        
        // Temps codé la semaine dernière
        $lastWeekTime = Activity::where('project_id', $projectId)
            ->whereBetween('created_at', $lastWeek)
            ->sum('duration') / 3600;
            
        // Temps codé cette semaine
        $thisWeekTime = Activity::where('project_id', $projectId)
            ->whereBetween('created_at', $thisWeek)
            ->sum('duration') / 3600;
            
        // Calculer l'évolution en pourcentage
        $percentChange = $lastWeekTime > 0 
            ? round((($thisWeekTime - $lastWeekTime) / $lastWeekTime) * 100, 1)
            : 100;
            
        // Heure de la journée avec le plus d'activité
        $hourlyActivity = Activity::where('project_id', $projectId)
            ->get()
            ->groupBy(function($activity) {
                return Carbon::parse($activity->created_at)->format('H');
            })
            ->map(function($items) {
                return $items->sum('duration') / 3600;
            });
            
        // Trouver l'heure de pic d'activité
        $peakHour = $hourlyActivity->sortDesc()->keys()->first() ?? '14';
        $nextHour = ($peakHour + 1) % 24;
        
        // Récupérer les 7 derniers jours d'activité pour le graphique de productivité
        $last7Days = [];
        $productivity7Days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            
            // Calculer durée et nombre de fichiers modifiés pour ce jour
            $dayActivity = Activity::where('project_id', $projectId)
                ->whereDate('created_at', $date)
                ->get();
                
            $timeSpent = $dayActivity->sum('duration') / 3600;
            $filesModified = $dayActivity->unique('file_path')->count();
            
            // Calculer "productivité" (60% temps + 40% nombre de fichiers)
            // Valeur entre 0 et 100
            $productivity = $timeSpent > 0 || $filesModified > 0
                ? min(100, (($timeSpent / 5) * 60) + (($filesModified / 10) * 40))
                : 0;
                
            $productivity7Days[] = round($productivity);
        }
        
        return [
            'percentChange' => $percentChange,
            'peakHour' => $peakHour . 'h - ' . $nextHour . 'h',
            'efficiency' => min(100, max(50, 70 + $percentChange/2)),
            'last7Days' => $last7Days,
            'productivity7Days' => $productivity7Days
        ];
    }
    
    /**
     * Récupère les données pour le graphique heatmap
     */
    private function getHeatmapData($projectId)
    {
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $timeSlots = ['0-6h', '6-9h', '9-12h', '12-14h', '14-17h', '17-20h', '20-24h'];
        
        // Plages horaires pour chaque slot
        $timeRanges = [
            [0, 6], [6, 9], [9, 12], [12, 14], [14, 17], [17, 20], [20, 24]
        ];
        
        // Récupérer les données des 4 dernières semaines
        $startDate = Carbon::now()->subDays(28)->startOfDay();
        
        $activities = Activity::where('project_id', $projectId)
            ->where('created_at', '>=', $startDate)
            ->get();
            
        // Initialiser la matrice de données
        $heatmap = [];
        for ($i = 0; $i < 7; $i++) {
            $row = [];
            for ($j = 0; $j < count($timeSlots); $j++) {
                $row[] = 0;
            }
            $heatmap[] = $row;
        }
        
        // Remplir la matrice avec les données d'activité
        foreach ($activities as $activity) {
            $dayOfWeek = Carbon::parse($activity->created_at)->dayOfWeekIso - 1; // 0 = Lundi
            $hour = Carbon::parse($activity->created_at)->hour;
            
            // Déterminer dans quel créneau horaire tombe cette activité
            for ($slot = 0; $slot < count($timeRanges); $slot++) {
                if ($hour >= $timeRanges[$slot][0] && $hour < $timeRanges[$slot][1]) {
                    // Ajouter la durée (en minutes) à ce créneau
                    $heatmap[$dayOfWeek][$slot] += $activity->duration / 60;
                    break;
                }
            }
        }
        
        // Normaliser les valeurs (0-100)
        $maxValue = 0;
        foreach ($heatmap as $row) {
            $maxValue = max($maxValue, max($row));
        }
        
        if ($maxValue > 0) {
            for ($i = 0; $i < 7; $i++) {
                for ($j = 0; $j < count($timeSlots); $j++) {
                    // Limiter à 100 et arrondir
                    $heatmap[$i][$j] = round(min(100, ($heatmap[$i][$j] / $maxValue) * 100));
                }
            }
        }
        
        return [
            'days' => $days,
            'timeSlots' => $timeSlots,
            'data' => $heatmap
        ];
    }
    
    /**
     * Récupère les données pour le calendrier d'activité
     */
    private function getContributionCalendar($projectId)
    {
        $result = [];
        $startDate = Carbon::now()->subDays(13);
        
        // Récupérer les activités des 14 derniers jours
        $activities = Activity::where('project_id', $projectId)
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($activity) {
                return Carbon::parse($activity->created_at)->format('Y-m-d');
            });
        
        // Préparer les données par jour
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayActivities = $activities->get($date, collect());
            
            $result[] = [
                'date' => $date,
                'dayOfWeek' => Carbon::parse($date)->dayOfWeekIso,
                'intensity' => $dayActivities->isEmpty() ? 0 : min(10, $dayActivities->sum('duration') / 3600),
                'filesCount' => $dayActivities->unique('file_path')->count()
            ];
        }
        
        return $result;
    }
    
    /**
     * Récupère le nom du mois en français
     */
    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        
        return $months[$monthNumber] ?? '';
    }

    /**
     * Exporte les données du projet en PDF
     */
    public function exportPdf($id)
    {
        $project = Project::findOrFail($id);
        
        // Vérifier que l'utilisateur est propriétaire du projet
        if ($project->user_id !== auth()->id()) {
            return abort(403);
        }
        
        // Récupérer les données nécessaires pour le PDF
        $activities = Activity::where('project_id', $project->id)
                              ->orderBy('created_at', 'desc')
                              ->take(50)
                              ->get();
        
        $formattedTime = \App\Models\Language::formatTime($project->getTotalTime());
        
        // Calculer des statistiques pour le PDF
        $totalHours = round($project->getTotalTime() / 3600000, 1); // Convertir en heures
        $totalFiles = $project->getTotalFiles();
        $totalLines = $project->getTotalLines();
        
        $languages = $project->languages()
                            ->orderBy('time_ms', 'desc')
                            ->get();
        
        // Calculer les activités par jour de la semaine
        $activityByDay = Activity::where('project_id', $project->id)
                                 ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('SUM(duration)/3600 as hours'))
                                 ->groupBy('day')
                                 ->orderBy(DB::raw('DAYOFWEEK(created_at)'))
                                 ->get()
                                 ->pluck('hours', 'day')
                                 ->toArray();
        
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $activityData = [];
        
        foreach ($daysOfWeek as $day) {
            $activityData[$day] = isset($activityByDay[$day]) ? round($activityByDay[$day], 1) : 0;
        }
        
        // Générer le PDF avec la vue
        $pdf = PDF::loadView('exports.project-pdf', [
            'project' => $project,
            'activities' => $activities,
            'formattedTime' => $formattedTime,
            'totalHours' => $totalHours,
            'totalFiles' => $totalFiles,
            'totalLines' => $totalLines,
            'languages' => $languages,
            'activityData' => $activityData,
            'exportDate' => Carbon::now()->format('d/m/Y H:i'),
        ]);
        
        // Définir quelques options pour le PDF
        $pdf->setPaper('a4');
        
        // Télécharger le PDF avec un nom personnalisé
        return $pdf->download($project->name . '_stats_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
