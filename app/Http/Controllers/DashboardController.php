<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer uniquement les projets de l'utilisateur connecté
        $projects = Project::where('user_id', $user->id)->with('languages')->get();
        
        $number_of_projects = $projects->count();
        
        // Récupérer le projet le plus récemment actif de l'utilisateur connecté
        $activeProject = Project::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Récupérer l'activité la plus récente de l'utilisateur connecté
        $latestActivity = Activity::with('project')
            ->whereHas('project', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Calculer les statistiques globales
        $globalStats = [
            'totalFiles' => 0,
            'totalLines' => 0,
            'totalTime' => 0,
            'currentFile' => null,
            'languages' => [],
            'environment' => [
                'editor' => 'VS Code',
                'os' => PHP_OS,
                'extensions' => [
                    ['name' => 'GitLens', 'active' => true],
                    ['name' => 'Prettier', 'active' => true],
                    ['name' => 'ESLint', 'active' => true],
                ]
            ],
            'currentProject' => null,
            'debugging_info' => [
                'latest_activity_id' => $latestActivity ? $latestActivity->id : null,
                'latest_project_id' => $activeProject ? $activeProject->id : null,
                'total_activities' => Activity::whereHas('project', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
                'has_stats' => $latestActivity && !empty($latestActivity->stats) ? 'oui' : 'non'
            ]
        ];
        
        if ($latestActivity && $latestActivity->project) {
            $activeProject = $latestActivity->project;
            
            // S'assurer que le projet appartient à l'utilisateur connecté
            if ($activeProject->user_id == $user->id) {
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
                
                // Informations sur le fichier actuel
                $globalStats['currentFile'] = [
                    'name' => $latestActivity->file_name ?? basename($latestActivity->file_path ?? 'Inconnu'),
                    'path' => $latestActivity->file_path ?? 'Chemin inconnu',
                    'language' => $latestActivity->language ?? 'inconnu',
                    'lines' => $latestActivity->lines ?? 0,
                    'timeSpent' => $timeSpent,
                    'formattedTime' => Language::formatTime($latestActivity->duration * 1000),
                    'lastActive' => $latestActivity->created_at->diffForHumans(),
                    'edits' => rand(15, 30),
                    'efficiency' => rand(85, 99) . '%',
                    'project' => $activeProject->name
                ];
    
                // Récupérer les langages du projet actuel uniquement
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
        }
        
        // Calculer les totaux à partir des projets de l'utilisateur
        foreach ($projects as $project) {
            $globalStats['totalFiles'] += $project->getTotalFiles();
            $globalStats['totalLines'] += $project->getTotalLines();
            $globalStats['totalTime'] += $project->getTotalTime();
        }
        
        // Formater le temps total global
        $globalStats['formattedTime'] = Language::formatTime($globalStats['totalTime']);
        
        // Ajouter les données de graphiques au tableau globalStats
        $activeProjectId = isset($globalStats['currentProject']) ? $globalStats['currentProject']['id'] : null;
        
        $globalStats['chartData'] = [
            'weekly' => $this->getWeeklyActivityData($activeProjectId, $user->id),
            'monthly' => $this->getMonthlyActivityData($activeProjectId, $user->id),
            'daily' => $this->getDailyActivityData($activeProjectId, $user->id)
        ];
        
        return view('dashboard.user.index', compact('number_of_projects', 'projects', 'globalStats'));
    }
    
    /**
     * Récupérer les données d'activité journalière pour l'utilisateur connecté
     * @param int|null $projectId ID du projet actuel
     * @param int $userId ID de l'utilisateur connecté
     * @return array
     */
    private function getDailyActivityData($projectId = null, $userId = null)
    {
        $today = Carbon::today();
        $result = [];
        
        // Labels pour les heures de la journée
        $result['labels'] = ['00h', '01h', '02h', '03h', '04h', '05h', '06h', '07h', 
                           '08h', '09h', '10h', '11h', '12h', '13h', 
                           '14h', '15h', '16h', '17h', '18h', '19h', 
                           '20h', '21h', '22h', '23h'];
        
        $data = [];
        $colors = [];
        
        // Récupérer les données pour chaque heure
        for ($hour = 0; $hour < 24; $hour++) {
            $start = $today->copy()->startOfDay()->addHours($hour);
            $end = $start->copy()->addHour();
            
            // Requête de base pour récupérer la durée d'activité pour cette heure
            $query = Activity::whereBetween('created_at', [$start, $end])
                ->whereHas('project', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            
            // Filtrer par projet si un ID est fourni
            if ($projectId) {
                $query->where('project_id', $projectId);
            }
            
            // Récupérer la durée d'activité (en heures)
            $duration = $query->sum('duration') / 3600;
            
            $data[] = round($duration, 2);
            
            // Générer une couleur basée sur l'intensité
            $intensity = min(1.0, $duration / 1.5); // 1.5h = intensité max
            $colors[] = $this->getColorForIntensity($intensity);
        }
        
        $result['data'] = $data;
        $result['colors'] = $colors;
        $result['title'] = "Activité de codage aujourd'hui (par heure)";
        
        // Statistiques additionnelles
        $result['totalHours'] = array_sum($data);
        $activeHours = count(array_filter($data, function($h) { return $h > 0; }));
        $result['avgHoursPerHour'] = $activeHours > 0 ? $result['totalHours'] / $activeHours : 0;
        
        // Trouver l'heure la plus productive
        $maxHour = array_search(max($data), $data);
        $result['mostProductiveHour'] = [
            'hour' => $result['labels'][$maxHour] ?? '00h',
            'value' => $data[$maxHour] ?? 0
        ];
        
        return $result;
    }

    /**
     * Récupérer les données d'activité hebdomadaire pour l'utilisateur connecté
     * @param int|null $projectId ID du projet actuel
     * @param int $userId ID de l'utilisateur connecté
     * @return array
     */
    private function getWeeklyActivityData($projectId = null, $userId = null)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $result = [];
        
        // Labels pour les jours de la semaine
        $result['labels'] = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        $data = [];
        $colors = [];
        
        // Récupérer les données pour chaque jour
        for ($day = 0; $day < 7; $day++) {
            $date = $startOfWeek->copy()->addDays($day);
            
            // Si on est sur un jour futur, mettre une valeur nulle
            if ($date->isAfter(Carbon::now())) {
                $data[] = 0;
            } else {
                // Requête de base pour ce jour
                $query = Activity::whereDate('created_at', $date->format('Y-m-d'))
                    ->whereHas('project', function($query) use ($userId) {
                        $query->where('user_id', $userId);
                    });
                
                // Filtrer par projet si un ID est fourni
                if ($projectId) {
                    $query->where('project_id', $projectId);
                }
                
                // Récupérer la durée d'activité pour ce jour (en heures)
                $duration = $query->sum('duration') / 3600;
                
                $data[] = round($duration, 2);
            }
            
            // Générer une couleur basée sur le jour et l'intensité
            $intensity = min(1.0, ($data[$day] ?? 0) / 5.0); // 5h = intensité max
            $colors[] = $this->getColorForIntensity($intensity, 'blue');
        }
        
        $result['data'] = $data;
        $result['colors'] = $colors;
        $result['title'] = "Activité de codage cette semaine (par jour)";
        
        // Statistiques additionnelles
        $result['totalHours'] = array_sum($data);
        $activeDays = count(array_filter($data, function($h) { return $h > 0; }));
        $result['avgHoursPerDay'] = $activeDays > 0 ? $result['totalHours'] / $activeDays : 0;
        
        // Trouver le jour le plus productif
        $maxDay = array_search(max($data), $data);
        $result['mostProductiveDay'] = [
            'day' => $result['labels'][$maxDay] ?? 'Lun',
            'value' => $data[$maxDay] ?? 0
        ];
        
        return $result;
    }

    /**
     * Récupérer les données d'activité mensuelle pour l'utilisateur connecté
     * @param int|null $projectId ID du projet actuel
     * @param int $userId ID de l'utilisateur connecté
     * @return array
     */
    private function getMonthlyActivityData($projectId = null, $userId = null)
    {
        $now = Carbon::now();
        $result = [];
        
        // Créer des labels pour les 4 dernières semaines
        $result['labels'] = [];
        $data = [];
        $colors = [];
        
        // Récupérer les données pour les 4 dernières semaines
        for ($week = 3; $week >= 0; $week--) {
            $endOfWeek = $now->copy()->subWeeks($week);
            $startOfWeek = $endOfWeek->copy()->subDays(6);
            
            // Format du label: "1-7 Mai"
            $label = $startOfWeek->format('j') . '-' . $endOfWeek->format('j') . ' ' . 
                    $this->getMonthName($endOfWeek->format('n'));
            
            $result['labels'][] = $label;
            
            // Requête de base pour cette semaine
            $query = Activity::whereBetween('created_at', [
                                $startOfWeek->startOfDay(), 
                                $endOfWeek->endOfDay()
                            ])
                            ->whereHas('project', function($query) use ($userId) {
                                $query->where('user_id', $userId);
                            });
                            
            // Filtrer par projet si un ID est fourni
            if ($projectId) {
                $query->where('project_id', $projectId);
            }
            
            // Récupérer la durée d'activité pour cette semaine (en heures)
            $duration = $query->sum('duration') / 3600;
            
            $data[] = round($duration, 2);
            
            // Générer une couleur basée sur l'intensité
            $intensity = min(1.0, $duration / 20); // 20h = intensité max
            $colors[] = $this->getColorForIntensity($intensity, 'indigo');
        }
        
        $result['data'] = $data;
        $result['colors'] = $colors;
        $result['title'] = "Activité de codage ces 4 dernières semaines";
        
        // Statistiques additionnelles
        $result['totalHours'] = array_sum($data);
        $activeWeeks = count(array_filter($data, function($h) { return $h > 0; }));
        $result['avgHoursPerWeek'] = $activeWeeks > 0 ? $result['totalHours'] / $activeWeeks : 0;
        
        // Trouver la semaine la plus productive
        $maxWeek = array_search(max($data), $data);
        $result['mostProductiveWeek'] = [
            'week' => $result['labels'][$maxWeek] ?? '',
            'value' => $data[$maxWeek] ?? 0
        ];

        return $result;
    }

    /**
     * Génère une couleur dynamique basée sur l'intensité de l'activité
     * @param float $intensity Valeur entre 0 et 1
     * @param string $colorScheme Couleur de base (blue, indigo, purple)
     * @return string
     */
    private function getColorForIntensity($intensity, $colorScheme = 'indigo')
    {
        // Base de nuances par schéma de couleur
        $colorMaps = [
            'blue' => [
                0 => 'rgba(59, 130, 246, 0.2)',  // blue-500 très transparent
                0.2 => 'rgba(59, 130, 246, 0.4)',
                0.4 => 'rgba(59, 130, 246, 0.6)',
                0.6 => 'rgba(59, 130, 246, 0.75)',
                0.8 => 'rgba(59, 130, 246, 0.9)',
                1 => 'rgba(37, 99, 235, 1)'      // blue-600 solide
            ],
            'indigo' => [
                0 => 'rgba(99, 102, 241, 0.2)',  // indigo-500 très transparent
                0.2 => 'rgba(99, 102, 241, 0.4)',
                0.4 => 'rgba(99, 102, 241, 0.6)',
                0.6 => 'rgba(99, 102, 241, 0.75)',
                0.8 => 'rgba(99, 102, 241, 0.9)',
                1 => 'rgba(79, 70, 229, 1)'      // indigo-600 solide
            ],
            'purple' => [
                0 => 'rgba(168, 85, 247, 0.2)',  // purple-500 très transparent
                0.2 => 'rgba(168, 85, 247, 0.4)',
                0.4 => 'rgba(168, 85, 247, 0.6)',
                0.6 => 'rgba(168, 85, 247, 0.75)',
                0.8 => 'rgba(168, 85, 247, 0.9)',
                1 => 'rgba(147, 51, 234, 1)'     // purple-600 solide
            ],
        ];
        
        // Sélectionner le schéma de couleur (par défaut: indigo)
        $colorMap = $colorMaps[$colorScheme] ?? $colorMaps['indigo'];
        
        // Trouver la couleur correspondante
        foreach ($colorMap as $threshold => $color) {
            if ($intensity <= $threshold) {
                return $color;
            }
        }
        
        // Si l'intensité dépasse tous les seuils, renvoyer la couleur la plus intense
        return end($colorMap);
    }
    
    /**
     * Obtenir le nom du mois en français
     */
    private function getMonthName($month) {
        $monthNames = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mars', 4 => 'Avr',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
            9 => 'Sept', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        
        return $monthNames[$month] ?? '';
    }
}