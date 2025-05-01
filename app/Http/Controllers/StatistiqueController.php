<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $period = $request->period ?? 'all';
        
        $startDate = null;
        $endDate = Carbon::now();
        
        if ($period == 'week') {
            $startDate = Carbon::now()->subWeek()->startOfDay();
        } elseif ($period == 'month') {
            $startDate = Carbon::now()->subMonth()->startOfDay();
        }
        
        $projects = Project::where('user_id', $user->id)
                          ->when($startDate, function ($query) use ($startDate) {
                              return $query->where('updated_at', '>=', $startDate);
                          })
                          ->withCount(['activities as activity_count'])
                          ->get();
        
        $totalTime = 0;
        $totalLines = 0;
        $activeProjects = count($projects);
        
        if ($period == 'all') {
            foreach ($projects as $project) {
                $totalTime += $project->getTotalTime();
                $totalLines += $project->getTotalLines();
            }
        } else {
            foreach ($projects as $project) {
                // Temps total sur ce projet dans la période
                $projectTime = $this->getProjectTotalTime($project->id, $startDate, $endDate);
                $totalTime += $projectTime;
                
                $projectLines = Language::where('project_id', $project->id)
                    ->when($startDate, function($query) use ($startDate) {
                        return $query->where('updated_at', '>=', $startDate);
                    })
                    ->sum('lines');
                $totalLines += $projectLines;
            }
        }
        
        // 3. Moyenne quotidienne (heures/jour)
        $avgDaily = $this->calculateDailyAverage($user->id, $startDate, $endDate);
        
        // 4. Tendances d'activité (heures par jour de la semaine)
        $activityTrends = $this->getActivityByDayOfWeek($user->id, $startDate, $endDate);
        
        // 5. Distribution par langage
        $languageDistribution = $this->getLanguageDistribution($user->id, $startDate, $endDate);
        
        // 6. Heatmap d'activité (par jour et période)
        $activityHeatmap = $this->getActivityHeatmap($user->id, $startDate, $endDate);
        
        // 7. Progression par projet
        $projectProgression = $this->getProjectProgression($user->id, $startDate, $endDate, 5); // Top 5 projets
        
        // 8. Statistiques additionnelles
        $mostProductiveTime = $this->getMostProductiveTime($user->id, $startDate, $endDate);
        $mostActiveDay = $this->getMostActiveDay($user->id, $startDate, $endDate);
        
        // 9. Badges et réalisations
        $badges = $this->calculateBadges($user->id);
        
        // Formater le temps total
        $formattedTotalTime = Language::formatTime($totalTime);
        
        return view("dashboard.user.statistique", compact(
            'totalTime', 
            'formattedTotalTime',
            'totalLines', 
            'activeProjects',
            'avgDaily',
            'activityTrends',
            'languageDistribution',
            'activityHeatmap',
            'projectProgression',
            'mostProductiveTime',
            'mostActiveDay',
            'badges',
            'period'
        ));
    }
    
  
    private function getProjectTotalTime($projectId, $startDate = null, $endDate = null)
    {
        $project = Project::find($projectId);
        
        if (!$project) {
            return 0;
        }
        
        if (!$startDate) {
            return $project->getTotalTime();
        }
        
        return Activity::where('project_id', $projectId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->sum('duration') * 1000; // Conversion en millisecondes
    }
    
  
    private function calculateDailyAverage($userId, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        }
        
        $dailyActivities = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(duration) / 3600 as hours') // Conversion en heures
            )
            ->groupBy('date')
            ->get();
        
        $totalHours = $dailyActivities->sum('hours');
        $activeDays = $dailyActivities->count();
        
        if ($activeDays == 0) return 0;
        
        $avgHours = round($totalHours / $activeDays, 1);
        return $avgHours;
    }
    
    
    private function getActivityByDayOfWeek($userId, $startDate = null, $endDate = null)
    {
        $activities = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->select(
                DB::raw('DAYOFWEEK(created_at) as day_of_week'),
                DB::raw('SUM(duration) / 3600 as hours') // Conversion en heures
            )
            ->groupBy('day_of_week')
            ->get();
        
        $result = [
            'labels' => ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            'data' => [0, 0, 0, 0, 0, 0, 0]
        ];
        
        foreach ($activities as $activity) {
            $index = $activity->day_of_week - 1; // Ajuster pour notre tableau 0-indexé
            $result['data'][$index] = round($activity->hours, 1);
        }
        
        return $result;
    }
    
    private function getLanguageDistribution($userId, $startDate = null, $endDate = null)
    {
        $languages = Language::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('updated_at', '>=', $startDate);
            })
            ->select('name', DB::raw('SUM(time_ms) as total_time'), DB::raw('SUM(`lines`) as total_lines'))
            ->groupBy('name')
            ->orderBy('total_time', 'desc')
            ->get();
        
        $totalTime = $languages->sum('total_time');
        
        $result = [
            'labels' => [],
            'data' => [],
            'colors' => [],
            'details' => []
        ];
        
        $colorMap = [
            'javascript' => '#f59e0b', // amber-500
            'js' => '#f59e0b',
            'php' => '#8b5cf6', // violet-500
            'html' => '#ef4444', // red-500
            'css' => '#3b82f6', // blue-500
            'python' => '#10b981', // emerald-500
            'java' => '#ec4899', // pink-500
            'c#' => '#7c3aed', // purple-600
            'c++' => '#2563eb', // blue-600
            'ruby' => '#dc2626', // red-600
            'swift' => '#f97316', // orange-500
            'go' => '#06b6d4', // cyan-500
            'typescript' => '#3b82f6', // blue-500
            'ts' => '#3b82f6',
            'rust' => '#b45309', // amber-700
            'kotlin' => '#a855f7', // purple-500
            'scala' => '#be123c', // rose-700
        ];
        
        $otherThreshold = 0.03; // 3%
        $otherLanguages = [
            'name' => 'Autres',
            'total_time' => 0,
            'total_lines' => 0,
            'percentage' => 0
        ];
        
        foreach ($languages as $language) {
            $langName = $language->name;
            $percentage = $totalTime > 0 ? ($language->total_time / $totalTime) * 100 : 0;
            
            if ($percentage < $otherThreshold * 100) {
                $otherLanguages['total_time'] += $language->total_time;
                $otherLanguages['total_lines'] += $language->total_lines;
                $otherLanguages['percentage'] += $percentage;
                continue;
            }
            
            $result['labels'][] = $langName;
            $result['data'][] = round($percentage, 1);
            
            $langKey = strtolower($langName);
            $color = $colorMap[$langKey] ?? '#6366f1'; 
            
            $result['colors'][] = $color;
            $result['details'][$langName] = [
                'name' => $langName,
                'time' => $language->total_time,
                'formattedTime' => Language::formatTime($language->total_time),
                'lines' => $language->total_lines,
                'percentage' => round($percentage, 1),
                'color' => $color
            ];
        }
        
        if ($otherLanguages['percentage'] > 0) {
            $result['labels'][] = 'Autres';
            $result['data'][] = round($otherLanguages['percentage'], 1);
            $result['colors'][] = '#94a3b8'; // slate-400
            $result['details']['Autres'] = [
                'name' => 'Autres',
                'time' => $otherLanguages['total_time'],
                'formattedTime' => Language::formatTime($otherLanguages['total_time']),
                'lines' => $otherLanguages['total_lines'],
                'percentage' => round($otherLanguages['percentage'], 1),
                'color' => '#94a3b8'
            ];
        }
        
        return $result;
    }
    
    private function getActivityHeatmap($userId, $startDate = null, $endDate = null)
    {
        $timeSlots = ['Matin', 'Après-midi', 'Soir'];
        $timeRanges = [
            [0, 12], // Matin (0h-12h)
            [12, 18], // Après-midi (12h-18h)
            [18, 24]  // Soir (18h-24h)
        ];
        
        // Jours de la semaine
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        // Récupérer toutes les activités
        $activities = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->get();
        
        // Initialiser la matrice de données
        $data = [];
        for ($i = 0; $i < 3; $i++) { // 3 périodes de la journée
            $data[$i] = [0, 0, 0, 0, 0, 0, 0]; // 7 jours de la semaine
        }
        
        // Remplir la matrice
        foreach ($activities as $activity) {
            $hour = Carbon::parse($activity->created_at)->hour;
            $day = Carbon::parse($activity->created_at)->dayOfWeekIso - 1; // 0 = lundi, 6 = dimanche
            
            // Déterminer la période de la journée
            for ($slot = 0; $slot < count($timeRanges); $slot++) {
                if ($hour >= $timeRanges[$slot][0] && $hour < $timeRanges[$slot][1]) {
                    // Ajouter la durée (en heures)
                    $data[$slot][$day] += $activity->duration / 3600;
                    break;
                }
            }
        }
        
        return [
            'timeSlots' => $timeSlots,
            'days' => $days,
            'data' => $data
        ];
    }
    
   
    private function getProjectProgression($userId, $startDate = null, $endDate = null, $limit = 5)
    {
        // Récupérer les temps par projet
        $projects = Project::where('user_id', $userId)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('updated_at', '>=', $startDate);
            })
            ->select('id', 'name')
            ->get();
        
        $result = [
            'labels' => [],
            'data' => [],
            'colors' => [
                'rgba(99, 102, 241, 0.7)', // indigo-500
                'rgba(59, 130, 246, 0.7)', // blue-500
                'rgba(168, 85, 247, 0.7)', // purple-500
                'rgba(34, 197, 94, 0.7)', // green-500
                'rgba(249, 115, 22, 0.7)', // orange-500
                'rgba(239, 68, 68, 0.7)', // red-500
                'rgba(20, 184, 166, 0.7)', // teal-500
                'rgba(245, 158, 11, 0.7)', // amber-500
                'rgba(217, 70, 239, 0.7)', // fuchsia-500
                'rgba(6, 182, 212, 0.7)', // cyan-500
            ]
        ];
        
        $projectTimes = [];
        
        foreach ($projects as $project) {
            $time = $this->getProjectTotalTime($project->id, $startDate, $endDate);
            if ($time > 0) {
                $projectTimes[$project->name] = $time / 3600000; // Convertir en heures
            }
        }
        
        // Trier par temps et limiter au nombre demandé
        arsort($projectTimes);
        $projectTimes = array_slice($projectTimes, 0, $limit);
        
        // Préparer les données pour le graphique
        foreach ($projectTimes as $name => $hours) {
            $result['labels'][] = $name;
            $result['data'][] = round($hours, 1);
        }
        
        return $result;
    }
   
    private function getMostProductiveTime($userId, $startDate = null, $endDate = null)
    {
        $activities = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(duration) / 3600 as hours')
            )
            ->groupBy('hour')
            ->orderBy('hours', 'desc')
            ->first();
        
        if (!$activities) {
            return '14h-16h'; // Valeur par défaut
        }
        
        $hour = $activities->hour;
        $nextHour = ($hour + 2) % 24;
        
        return $hour . 'h-' . $nextHour . 'h';
    }
    
    
    private function getMostActiveDay($userId, $startDate = null, $endDate = null)
    {
        $activities = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->select(
                DB::raw('DAYOFWEEK(created_at) as day_of_week'),
                DB::raw('SUM(duration) / 3600 as hours')
            )
            ->groupBy('day_of_week')
            ->orderBy('hours', 'desc')
            ->first();
        
        if (!$activities) {
            return 'Mardi'; // Valeur par défaut
        }
        
        $dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $dayNames[$activities->day_of_week - 1];
    }
    
    private function calculateBadges($userId)
    {
        $badges = [
            'premiere_semaine' => [
                'name' => 'Première semaine',
                'description' => '7 jours de code consécutifs',
                'icon' => 'ph-rocket-launch',
                'color' => 'from-indigo-500 to-purple-600',
                'unlocked' => false
            ],
            'sprint' => [
                'name' => 'Sprint',
                'description' => 'Plus de 5h de codage en un jour',
                'icon' => 'ph-lightning',
                'color' => 'from-blue-500 to-cyan-600',
                'unlocked' => false
            ],
            'polyglotte' => [
                'name' => 'Polyglotte',
                'description' => 'Maîtrise de 5 langages',
                'icon' => 'ph-stack',
                'color' => 'from-emerald-500 to-green-600',
                'unlocked' => false
            ],
            'mensuel' => [
                'name' => 'Mensuel',
                'description' => '30 jours d\'activité',
                'icon' => 'ph-calendar',
                'color' => 'from-gray-500 to-gray-600',
                'unlocked' => false
            ],
            'expert' => [
                'name' => 'Expert',
                'description' => '100h sur un seul langage',
                'icon' => 'ph-medal',
                'color' => 'from-gray-500 to-gray-600',
                'unlocked' => false
            ],
            'architecte' => [
                'name' => 'Architecte',
                'description' => '10 projets complétés',
                'icon' => 'ph-buildings',
                'color' => 'from-gray-500 to-gray-600',
                'unlocked' => false
            ]
        ];
        
        // Vérifier le badge "Sprint" (plus de 5h en un jour)
        $maxDailyHours = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(duration) / 3600 as hours'))
            ->groupBy('date')
            ->orderBy('hours', 'desc')
            ->first();
            
        if ($maxDailyHours && $maxDailyHours->hours >= 5) {
            $badges['sprint']['unlocked'] = true;
        }
        
        // Vérifier le badge "Polyglotte" (5 langages ou plus)
        $languageCount = Language::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select('name')
            ->distinct()
            ->count();
            
        if ($languageCount >= 5) {
            $badges['polyglotte']['unlocked'] = true;
        }
        
        // Vérifier le badge "Expert" (100h sur un langage)
        $expertLanguage = Language::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select('name', DB::raw('SUM(time_ms) as total_time'))
            ->groupBy('name')
            ->having(DB::raw('SUM(time_ms)'), '>=', 360000000) // 100h en ms
            ->first();
            
        if ($expertLanguage) {
            $badges['expert']['unlocked'] = true;
            $badges['expert']['color'] = 'from-yellow-500 to-amber-600';
        }
        
        // Vérifier le badge "Architecte" (10 projets ou plus)
        $projectCount = Project::where('user_id', $userId)->count();
        if ($projectCount >= 10) {
            $badges['architecte']['unlocked'] = true;
            $badges['architecte']['color'] = 'from-cyan-500 to-blue-600';
        }
        
        // Vérifier le badge "Mensuel" (activité pendant 30 jours)
        $activeDaysCount = Activity::whereHas('project', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select(DB::raw('DATE(created_at) as date'))
            ->distinct()
            ->count();
            
        if ($activeDaysCount >= 30) {
            $badges['mensuel']['unlocked'] = true;
            $badges['mensuel']['color'] = 'from-pink-500 to-rose-600';
        }
        
        // Vérifier badge "Première semaine" (7 jours consécutifs)
        // Note: cette logique est simplifiée et pourrait nécessiter un algorithme plus complexe pour vérifier exactement des jours consécutifs
        if ($activeDaysCount >= 7) {
            $badges['premiere_semaine']['unlocked'] = true;
        }
        
        return $badges;
    }
}
