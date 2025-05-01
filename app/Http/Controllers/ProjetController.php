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
use Illuminate\Support\Facades\DB; 

class ProjetController extends Controller
{
    public function project($id)
    {

        $project = Project::with('languages')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        

            $activities = Activity::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        

            $currentFile = $activities->first();
        
        
        $formattedTime = Language::formatTime($project->getTotalTime());

       
        $chartData = [
            'daily' => $this->getDailyActivityData($project->id),
            'weekly' => $this->getWeeklyActivityData($project->id),
            'monthly' => $this->getMonthlyActivityData($project->id)
        ];
        
        
        $productivityStats = $this->getProductivityStats($project->id);
        
        
        $heatmapData = $this->getHeatmapData($project->id);
        
       
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
        
        $projects = Project::where('user_id', Auth::id())->with('languages')->get();
        
        return view('dashboard.user.projects', compact('projects'));
    }
    
  
    private function getDailyActivityData($projectId)
    {
        $today = Carbon::today();
        $result = [];
        
        
        $labels = [];
        $data = [];
        
        
        for ($hour = 0; $hour < 24; $hour++) {
            $start = $today->copy()->startOfDay()->addHours($hour);
            $end = $start->copy()->addHour();
            
            $labels[] = $start->format('H') . 'h';
            
            $duration = Activity::where('project_id', $projectId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('duration') / 3600;
            
            $data[] = round($duration, 2);
        }
        
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
    
    
    private function getWeeklyActivityData($projectId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $data = [];
        
        for ($day = 0; $day < 7; $day++) {
            $date = $startOfWeek->copy()->addDays($day);
            
            if ($date->isAfter(Carbon::now())) {
                $data[] = 0;
            } else {
                $duration = Activity::where('project_id', $projectId)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('duration') / 3600;
                
                $data[] = round($duration, 2);
            }
        }
        
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
    
    
    private function getMonthlyActivityData($projectId)
    {
        $now = Carbon::now();
        $labels = [];
        $data = [];
        
        for ($week = 3; $week >= 0; $week--) {
            $endOfWeek = $now->copy()->subWeeks($week);
            $startOfWeek = $endOfWeek->copy()->subDays(6);
            
            $monthName = $this->getMonthName($endOfWeek->format('n'));
            $label = $startOfWeek->format('j') . '-' . $endOfWeek->format('j') . ' ' . $monthName;
            $labels[] = $label;
            
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
    
   
    private function getProductivityStats($projectId)
    {
        $lastWeek = [
            Carbon::now()->subDays(14)->startOfDay(),
            Carbon::now()->subDays(7)->endOfDay()
        ];
        
        $thisWeek = [
            Carbon::now()->subDays(7)->startOfDay(),
            Carbon::now()->endOfDay()
        ];
        
        $lastWeekTime = Activity::where('project_id', $projectId)
            ->whereBetween('created_at', $lastWeek)
            ->sum('duration') / 3600;
            
        $thisWeekTime = Activity::where('project_id', $projectId)
            ->whereBetween('created_at', $thisWeek)
            ->sum('duration') / 3600;
            
        $percentChange = $lastWeekTime > 0 
            ? round((($thisWeekTime - $lastWeekTime) / $lastWeekTime) * 100, 1)
            : 100;
            
        $hourlyActivity = Activity::where('project_id', $projectId)
            ->get()
            ->groupBy(function($activity) {
                return Carbon::parse($activity->created_at)->format('H');
            })
            ->map(function($items) {
                return $items->sum('duration') / 3600;
            });
            
        $peakHour = $hourlyActivity->sortDesc()->keys()->first() ?? '14';
        $nextHour = ($peakHour + 1) % 24;
        
        $last7Days = [];
        $productivity7Days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            
            $dayActivity = Activity::where('project_id', $projectId)
                ->whereDate('created_at', $date)
                ->get();
                
            $timeSpent = $dayActivity->sum('duration') / 3600;
            $filesModified = $dayActivity->unique('file_path')->count();
            
       
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
    

    private function getHeatmapData($projectId)
    {
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $timeSlots = ['0-6h', '6-9h', '9-12h', '12-14h', '14-17h', '17-20h', '20-24h'];
        
        $timeRanges = [
            [0, 6], [6, 9], [9, 12], [12, 14], [14, 17], [17, 20], [20, 24]
        ];
        
        $startDate = Carbon::now()->subDays(28)->startOfDay();
        
        $activities = Activity::where('project_id', $projectId)
            ->where('created_at', '>=', $startDate)
            ->get();
            
        $heatmap = [];
        for ($i = 0; $i < 7; $i++) {
            $row = [];
            for ($j = 0; $j < count($timeSlots); $j++) {
                $row[] = 0;
            }
            $heatmap[] = $row;
        }
        
        foreach ($activities as $activity) {
            $dayOfWeek = Carbon::parse($activity->created_at)->dayOfWeekIso - 1; // 0 = Lundi
            $hour = Carbon::parse($activity->created_at)->hour;
            
            for ($slot = 0; $slot < count($timeRanges); $slot++) {
                if ($hour >= $timeRanges[$slot][0] && $hour < $timeRanges[$slot][1]) {
                    $heatmap[$dayOfWeek][$slot] += $activity->duration / 60;
                    break;
                }
            }
        }
        
        $maxValue = 0;
        foreach ($heatmap as $row) {
            $maxValue = max($maxValue, max($row));
        }
        
        if ($maxValue > 0) {
            for ($i = 0; $i < 7; $i++) {
                for ($j = 0; $j < count($timeSlots); $j++) {
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
    
    
    private function getContributionCalendar($projectId)
    {
        $result = [];
        $startDate = Carbon::now()->subDays(13);
        
        $activities = Activity::where('project_id', $projectId)
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($activity) {
                return Carbon::parse($activity->created_at)->format('Y-m-d');
            });
        
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
    
 
    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        
        return $months[$monthNumber] ?? '';
    }

   
    public function exportPdf($id)
    {
        $project = Project::findOrFail($id);
        
        if ($project->user_id !== auth()->id()) {
            return abort(403);
        }
        
        $activities = Activity::where('project_id', $project->id)
                              ->orderBy('created_at', 'desc')
                              ->take(50)
                              ->get();
        
        $formattedTime = \App\Models\Language::formatTime($project->getTotalTime());
        
        $totalHours = round($project->getTotalTime() / 3600000, 1); // Convertir en heures
        $totalFiles = $project->getTotalFiles();
        $totalLines = $project->getTotalLines();
        
        $languages = $project->languages()
                            ->orderBy('time_ms', 'desc')
                            ->get();
        
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
        
        $pdf->setPaper('a4');
        
        return $pdf->download($project->name . '_stats_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
