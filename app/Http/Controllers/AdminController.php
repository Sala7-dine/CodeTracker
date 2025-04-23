<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        
        $totalProjects = Project::count();
        $newProjectsThisWeek = Project::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()])->count();
        
        // Calculer l'espace de stockage utilisé (en supposant que vous stockez des fichiers)
        $storageUsed = $this->calculateStorageUsage();
        
        // Charge serveur simulée (dans un vrai système, vous pourriez obtenir cela via une API système)
        $serverLoad = rand(15, 85); // Simulation
        
        // Récupérer les utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
        
        // Récupérer des logs système récents (simulation)
        $systemLogs = $this->getSystemLogs();
        
        return view("dashboard.admin.index", compact(
            'totalUsers', 
            'newUsersToday', 
            'totalProjects', 
            'newProjectsThisWeek',
            'storageUsed',
            'serverLoad',
            'recentUsers',
            'systemLogs'
        ));
    }
    
    /**
     * Calcule l'espace de stockage utilisé
     */
    private function calculateStorageUsage()
    {
        // Récupérer la taille de toutes les activités (en supposant qu'elles sont stockées)
        $activitiesCount = Activity::count();
        
        // Estimation simulée - dans un système réel, on calculerait l'espace disque réel utilisé
        $estimatedSize = $activitiesCount * 0.02; // Supposons 20 KB par activité en moyenne
        
        return [
            'used' => round($estimatedSize, 1), // GB
            'total' => 100, // GB (limite supposée)
            'percentage' => min(100, round(($estimatedSize / 100) * 100, 1))
        ];
    }
    
    /**
     * Récupère les logs système récents
     */
    private function getSystemLogs()
    {
        // Dans un système réel, vous pourriez récupérer des logs réels de la base de données
        // Ici nous simulons quelques logs
        return [
            [
                'timestamp' => Carbon::now()->subMinutes(5)->format('Y-m-d H:i'),
                'type' => 'warning',
                'message' => 'Haute utilisation CPU détectée',
                'status' => 'warning'
            ],
            [
                'timestamp' => Carbon::now()->subMinutes(8)->format('Y-m-d H:i'),
                'type' => 'info',
                'message' => 'Backup automatique complété',
                'status' => 'success'
            ],
            [
                'timestamp' => Carbon::now()->subHours(1)->format('Y-m-d H:i'),
                'type' => 'error',
                'message' => 'Échec de connexion à la base de données',
                'status' => 'error'
            ],
            [
                'timestamp' => Carbon::now()->subHours(2)->format('Y-m-d H:i'),
                'type' => 'info',
                'message' => 'Nouvel utilisateur enregistré',
                'status' => 'success'
            ]
        ];
    }
    
    /**
     * Affiche la liste des utilisateurs
     */
    public function users()
    {
        $users = User::withCount('projects')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
        return view('dashboard.admin.users', compact('users'));
    }
    
    /**
     * Bloque/débloque un utilisateur
     */
    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher le blocage du compte administrateur principal
        if ($user->role === 'admin' && $user->id === 1) {
            return redirect()->back()->with('error', 'Impossible de bloquer le compte administrateur principal.');
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        $message = $user->is_active ? 'Utilisateur débloqué avec succès.' : 'Utilisateur bloqué avec succès.';
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Change le rôle d'un utilisateur
     */
    public function changeUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);
        
        $user = User::findOrFail($id);
        
        // Empêcher la modification du rôle de l'administrateur principal
        if ($user->id === 1) {
            return redirect()->back()->with('error', 'Impossible de modifier le rôle de l\'administrateur principal.');
        }
        
        // Validation pour éviter de supprimer tous les admin
        if ($user->role == 'admin' && $request->role != 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Impossible de changer le rôle. Il doit y avoir au moins un administrateur.');
            }
        }
        
        $user->role = $request->role;
        $user->save();
        
        return redirect()->back()->with('success', "Le rôle de l'utilisateur a été modifié avec succès.");
    }
    
    /**
     * Supprime un utilisateur
     */
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la suppression de l'administrateur principal
        if ($user->id === 1) {
            return redirect()->back()->with('error', 'Impossible de supprimer l\'administrateur principal.');
        }
        
        // Validation pour éviter de supprimer le dernier admin
        if ($user->role == 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Impossible de supprimer le dernier administrateur.');
            }
        }
        
        // Sauvegarde des informations pour le log d'audit
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_by' => auth()->id()
        ];
        
        DB::beginTransaction();
        try {
            // Supprimer ou anonymiser les projets selon votre politique
            foreach ($user->projects as $project) {
                // Option 1: Supprimer complètement
                $project->activities()->delete();
                $project->languages()->delete();
                $project->delete();
                
                // Option 2: Marquer comme supprimés mais conserver pour statistiques
                // $project->update(['user_id' => null, 'status' => 'deleted']);
            }
            
            // Supprimer l'utilisateur
            $user->delete();
            
            // Log d'audit (optionnel)
            DB::table('audit_logs')->insert([
                'action' => 'user_deleted',
                'data' => json_encode($userData),
                'created_at' => Carbon::now(),
                'user_id' => auth()->id()
            ]);
            
            DB::commit();
            return redirect()->route('admin.users')->with('success', 'L\'utilisateur et tous ses projets ont été supprimés avec succès.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche les détails d'un utilisateur
     */
    public function showUser($id)
    {
        $user = User::with(['projects' => function($query) {
            $query->withCount(['activities', 'languages']);
        }])->findOrFail($id);
        
        // Calcul des statistiques de l'utilisateur
        $totalCodingTime = 0;
        $totalLines = 0;
        
        foreach ($user->projects as $project) {
            $totalCodingTime += $project->getTotalTime();
            $totalLines += $project->getTotalLines();
        }
        
        $userStats = [
            'totalProjects' => $user->projects->count(),
            'totalCodingTime' => \App\Models\Language::formatTime($totalCodingTime),
            'totalLines' => $totalLines,
            'memberSince' => $user->created_at->diffForHumans(),
            'lastActive' => $user->updated_at->diffForHumans()
        ];
        
        return view('dashboard.admin.user-details', compact('user', 'userStats'));
    }
}
