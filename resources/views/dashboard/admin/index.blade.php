<x-app-layout>
    <aside class="fixed left-0 top-0 h-screen w-64 bg-gray-800 border-r border-gray-700">
        <div class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <i class="ph-shield text-xl text-white"></i>
                </div>
                <span class="text-white font-bold text-lg">Admin Panel</span>
            </div>
        </div>

        <nav class="mt-6">
            <div class="px-6 py-3">
                <p class="text-xs font-semibold text-gray-400 uppercase">Principal</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-100 bg-gray-700' : 'text-gray-400 hover:bg-gray-700 hover:text-gray-100' }}">
                <i class="ph-squares-four text-lg mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users') ? 'text-gray-100 bg-gray-700' : 'text-gray-400 hover:bg-gray-700 hover:text-gray-100' }}">
                <i class="ph-users text-lg mr-3"></i>
                Utilisateurs
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-folders text-lg mr-3"></i>
                Projets
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-chart-line text-lg mr-3"></i>
                Statistiques
            </a>

            <div class="px-6 py-3 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase">Système</p>
            </div>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-gear-six text-lg mr-3"></i>
                Paramètres
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-database text-lg mr-3"></i>
                Base de données
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-sign-out text-lg mr-3"></i>
                Déconnexion
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Administrateur</h1>
                <p class="text-gray-400">Vue d'ensemble du système</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600 transition-all">
                    <i class="ph-bell text-xl"></i>
                </button>
                <div class="flex items-center space-x-3 px-4 py-2 bg-gray-700 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-sm">
                        {{ Auth::user()->name[0] ?? 'A' }}{{ strlen(Auth::user()->name) > 1 ? Auth::user()->name[1] : '' }}
                    </div>
                    <span class="text-white">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Utilisateurs totaux</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-users text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
                <p class="text-sm text-blue-400 mt-2">+{{ $newUsersToday }} aujourd'hui</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="ph-folder-open text-green-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $totalProjects }}</p>
                <p class="text-sm text-green-400 mt-2">+{{ $newProjectsThisWeek }} cette semaine</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Espace utilisé</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-database text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $storageUsed['used'] }} GB</p>
                <p class="text-sm text-purple-400 mt-2">Sur {{ $storageUsed['total'] }} GB</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Charge serveur</h3>
                    <span class="w-8 h-8 rounded-lg bg-red-600/20 flex items-center justify-center">
                        <i class="ph-cpu text-red-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $serverLoad }}%</p>
                <p class="text-sm {{ $serverLoad > 75 ? 'text-red-400' : ($serverLoad > 50 ? 'text-yellow-400' : 'text-green-400') }} mt-2">
                    {{ $serverLoad > 75 ? 'Élevé' : ($serverLoad > 50 ? 'Modéré' : 'Normal') }}
                </p>
            </div>
        </div>

        <!-- Recent Users & System Status -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Utilisateurs récents</h2>
                    <a href="{{ route('admin.users') }}" class="text-sm text-gray-400 hover:text-white">Voir tout</a>
                </div>
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-4 bg-gray-700/50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full {{ $user->role === 'admin' ? 'bg-red-600' : 'bg-blue-600' }} flex items-center justify-center text-white">
                                    {{ $user->name[0] ?? 'U' }}{{ strlen($user->name) > 1 ? $user->name[1] : '' }}
                                </div>
                                <div>
                                    <h4 class="text-white">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                            </span>
                        </div>
                    @endforeach
                    
                    @if($recentUsers->isEmpty())
                        <div class="text-center text-gray-400 py-4">
                            Aucun utilisateur enregistré
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">État du système</h2>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">CPU</span>
                            <span class="text-white">{{ $serverLoad }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $serverLoad }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Mémoire</span>
                            <span class="text-white">{{ min(100, $serverLoad + 15) }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, $serverLoad + 15) }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Stockage</span>
                            <span class="text-white">{{ $storageUsed['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $storageUsed['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-6">Logs système récents</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-400 text-sm">
                            <th class="pb-4">Timestamp</th>
                            <th class="pb-4">Type</th>
                            <th class="pb-4">Message</th>
                            <th class="pb-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($systemLogs as $log)
                            <tr class="border-t border-gray-700">
                                <td class="py-3">{{ $log['timestamp'] }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $log['type'] === 'warning' ? 'bg-yellow-500/20 text-yellow-400' : 
                                           ($log['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400') }}">
                                        {{ ucfirst($log['type']) }}
                                    </span>
                                </td>
                                <td>{{ $log['message'] }}</td>
                                <td>
                                    <i class="{{ $log['status'] === 'warning' ? 'ph-warning text-yellow-400' : 
                                              ($log['status'] === 'error' ? 'ph-x-circle text-red-400' : 'ph-check-circle text-green-400') }}"></i>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($systemLogs) === 0)
                            <tr class="border-t border-gray-700">
                                <td colspan="4" class="py-4 text-center text-gray-400">Aucun log système</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</x-app-layout>