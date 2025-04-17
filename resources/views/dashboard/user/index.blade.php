<x-app-layout>
    
    @include("layouts.user_sidebare")

    <!-- Main Content -->
    <main class="ml-20 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">Tableau de bord</h1>
                <p class="text-gray-400">Visualisez vos statistiques de codage</p>
            </div>
            
            <!-- Date selector -->
            <div class="flex items-center space-x-2 text-gray-400">
                <i class="ph-calendar text-lg"></i>
                <select class="bg-gray-800 border-none rounded-lg text-sm focus:ring-indigo-500">
                    <option>Aujourd'hui</option>
                    <option>Cette semaine</option>
                    <option selected>Ce mois</option>
                    <option>Tout le temps</option>
                </select>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Temps total -->
            <div class="bg-gray-800/70 rounded-xl p-6 border border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-indigo-500/20 p-3 rounded-lg">
                        <i class="ph-clock text-xl text-indigo-400"></i>
                    </div>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Temps de codage</h3>
                <p class="text-2xl font-bold text-white">{{ $globalStats['formattedTime'] }}</p>
                <div class="mt-2 text-xs flex items-center text-green-400">
                    <i class="ph-trend-up mr-1"></i>
                    <span>+5% par rapport à la semaine dernière</span>
                </div>
            </div>
            
            <!-- Nombre de lignes -->
            <div class="bg-gray-800/70 rounded-xl p-6 border border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-blue-500/20 p-3 rounded-lg">
                        <i class="ph-brackets-curly text-xl text-blue-400"></i>
                    </div>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Lignes de code</h3>
                <p class="text-2xl font-bold text-white">{{ number_format($globalStats['totalLines']) }}</p>
                <div class="mt-2 text-xs flex items-center text-green-400">
                    <i class="ph-trend-up mr-1"></i>
                    <span>+12% par rapport à la semaine dernière</span>
                </div>
            </div>
            
            <!-- Nombre de fichiers -->
            <div class="bg-gray-800/70 rounded-xl p-6 border border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-purple-500/20 p-3 rounded-lg">
                        <i class="ph-file-code text-xl text-purple-400"></i>
                    </div>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Fichiers</h3>
                <p class="text-2xl font-bold text-white">{{ number_format($globalStats['totalFiles']) }}</p>
                <div class="mt-2 text-xs flex items-center text-yellow-400">
                    <i class="ph-trend-right mr-1"></i>
                    <span>Stable depuis la semaine dernière</span>
                </div>
            </div>
            
            <!-- Nombre de projets -->
            <div class="bg-gray-800/70 rounded-xl p-6 border border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-green-500/20 p-3 rounded-lg">
                        <i class="ph-folders text-xl text-green-400"></i>
                    </div>
                    <span class="text-xs text-gray-500">Actifs</span>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Projets</h3>
                <p class="text-2xl font-bold text-white">{{ number_format($number_of_projects) }}</p>
                <div class="mt-2 text-xs flex items-center text-red-400">
                    <i class="ph-trend-down mr-1"></i>
                    <span>-1 par rapport au mois dernier</span>
                </div>
            </div>
        </div>
        
        <!-- Current file & charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Current file card -->
            <div class="bg-gray-800/70 rounded-xl border border-gray-700 p-6">
                <h2 class="font-bold text-white text-lg mb-6">Fichier actuel</h2>
                
                @if(isset($globalStats['currentFile']) && !empty($globalStats['currentFile']['name']))
                <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                    <h3 class="text-lg font-medium text-white">
                        {{ basename($globalStats['currentFile']['name']) }}
                    </h3>
                    <p class="text-sm text-gray-400 truncate">
                        {{ $globalStats['currentFile']['path'] ?? 'N/A' }}
                    </p>
                    <div class="flex items-center mt-2 space-x-3">
                        <span class="text-xs bg-indigo-500/20 text-indigo-400 px-2 py-1 rounded-full">
                            {{ $globalStats['currentFile']['language'] }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $globalStats['currentFile']['lines'] ?? 0 }} lignes
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $globalStats['currentFile']['lastActive'] }}
                        </span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="text-sm text-gray-400">Temps passé sur ce fichier</p>
                        <p class="text-2xl font-bold text-white">{{ $globalStats['currentFile']['formattedTime'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Modifications</p>
                        <p class="text-2xl font-bold text-green-400">{{ $globalStats['currentFile']['edits'] }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Efficacité</span>
                    <span class="text-green-400">{{ $globalStats['currentFile']['efficiency'] }}</span>
                </div>
                <div class="w-full h-2 bg-gray-700 rounded-full mt-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ substr($globalStats['currentFile']['efficiency'], 0, -1) }}%"></div>
                </div>
                
                @if(isset($globalStats['currentProject']) && !empty($globalStats['currentProject']['name']))
                <div class="mt-6">
                    <p class="text-sm text-gray-400">Projet</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-white">{{ $globalStats['currentProject']['name'] }}</span>
                        <a href="{{ route('user.dashboard.project', $globalStats['currentProject']['id']) }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                            Voir détails →
                        </a>
                    </div>
                </div>
                @endif
                
                @else
                <div class="border border-gray-700 border-dashed rounded-lg p-8 text-center">
                    <div class="w-16 h-16 bg-gray-800/80 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="ph-file-x text-3xl text-gray-600"></i>
                    </div>
                    <h3 class="text-white font-medium mb-2">Aucun fichier actif</h3>
                    <p class="text-gray-400 text-sm">Commencez à coder pour voir les statistiques de votre fichier actuel</p>
                </div>
                @endif
            </div>
            
            <!-- Language distribution -->
            <div class="bg-gray-800/70 rounded-xl border border-gray-700 p-6">
                <h2 class="font-bold text-white text-lg mb-6">Distribution des langages</h2>
                <div class="h-60">
                    <canvas id="languageDistributionChart"></canvas>
                </div>
            </div>
            
            <!-- Activity chart -->
            <div class="bg-gray-800/70 rounded-xl border border-gray-700 p-6">
                <h2 class="font-bold text-white text-lg mb-6">Activité de codage</h2>
                <div class="h-60">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent projects & timeline -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent projects -->
            <div class="bg-gray-800/70 rounded-xl border border-gray-700 p-6 lg:col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-white text-lg">Projets actifs</h2>
                    <a href="{{ route('user.dashboard.projects') }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                        Voir tous →
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse($projects->take(3) as $project)
                    <!-- Project Card -->
                    <div class="p-4 bg-gray-800/30 border border-gray-700 rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-white font-medium">{{ $project->name }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                                @if(isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id)
                                    Actif
                                @else
                                    Récent
                                @endif
                            </span>
                        </div>
                        <div class="w-full bg-gray-600 rounded-full h-2">
                            @php
                                $percentage = 75; // Valeur par défaut
                                if (isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id) {
                                    // Calculer un pourcentage basé sur l'activité
                                    $percentage = 100;
                                } elseif ($project->getTotalTime() > 0) {
                                    $percentage = min(95, max(30, $project->getTotalTime() / 36000000 * 100)); // 10h = 100%
                                }
                            @endphp
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="text-gray-400">{{ \App\Models\Language::formatTime($project->getTotalTime()) }}</span>
                            <a href="{{ route('user.dashboard.project', $project->id) }}" class="text-indigo-400 hover:text-indigo-300">
                                Voir détails
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 bg-gray-700/50 rounded-xl text-center">
                        <p class="text-gray-400">Aucun projet actif</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="bg-gray-800/70 rounded-xl border border-gray-700 p-6 lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-white text-lg">Activité récente</h2>
                    <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300">
                        Voir tout →
                    </a>
                </div>
                
                <!-- Timeline -->
                <div class="relative pl-8 space-y-6 before:content-[''] before:absolute before:left-4 before:top-1 before:bottom-0 before:w-0.5 before:bg-gray-700">
                    <!-- Timeline items would go here -->
                    <div class="relative">
                        <div class="absolute -left-8 w-8 h-8 flex items-center justify-center">
                            <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">Il y a 2 heures</div>
                        <p class="text-white">Début du travail sur <span class="text-indigo-400">CodeTracker</span></p>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-8 w-8 h-8 flex items-center justify-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">Hier à 15:42</div>
                        <p class="text-white">Finalisé la fonction <span class="text-green-400">statistiques par langage</span></p>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-8 w-8 h-8 flex items-center justify-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">Hier à 13:37</div>
                        <p class="text-white">Mise en place des <span class="text-purple-400">graphiques d'activité</span></p>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute -left-8 w-8 h-8 flex items-center justify-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">Avant-hier</div>
                        <p class="text-white">Début du projet <span class="text-blue-400">CodeTracker</span></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Ajout du script Chart.js à la fin du body -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration commune pour tous les graphiques
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = 'rgba(75, 85, 99, 0.2)';
        Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
        
        // Données pour le graphique de distribution des langages
        const languageData = {
            labels: {!! json_encode(array_keys($globalStats['languages'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_column($globalStats['languages'] ?? [], 'time_spent')) !!},
                backgroundColor: [
                    'rgba(129, 140, 248, 0.8)',  // indigo
                    'rgba(168, 85, 247, 0.8)',   // purple
                    'rgba(59, 130, 246, 0.8)',   // blue
                    'rgba(16, 185, 129, 0.8)',   // green
                    'rgba(245, 158, 11, 0.8)',   // amber
                    'rgba(239, 68, 68, 0.8)',    // red
                    'rgba(236, 72, 153, 0.8)',   // pink
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        };
        
        // Initialiser le graphique de distribution des langages
        const languageCtx = document.getElementById('languageDistributionChart').getContext('2d');
        new Chart(languageCtx, {
            type: 'doughnut',
            data: languageData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%',
                radius: '90%'
            }
        });
        
        // Données pour le graphique d'activité
        const activityData = {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Heures de codage',
                data: [2.5, 3.2, 4.1, 2.8, 5.2, 4.3, 2.9],
                borderColor: 'rgba(129, 140, 248, 1)',
                backgroundColor: 'rgba(129, 140, 248, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(129, 140, 248, 1)',
                pointBorderColor: '#111827',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(129, 140, 248, 1)',
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        };
        
        // Initialiser le graphique d'activité
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: activityData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(75, 85, 99, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + 'h';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-app-layout>