<x-app-layout>
    
    @include("layouts.user_sidebare")

    <!-- Main Content -->
    <main class="ml-20 p-8 bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Tableau de bord</h1>
                <p class="text-gray-400">Visualisez vos statistiques de codage</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-gray-400">
                    <i class="ph-calendar text-lg"></i>
                    <select class="bg-gray-700 border-none rounded-lg text-sm focus:ring-indigo-500">
                        <option>Aujourd'hui</option>
                        <option>Cette semaine</option>
                        <option selected>Ce mois</option>
                        <option>Tout le temps</option>
                    </select>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Temps total</h3>
                    <span class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $globalStats['formattedTime'] }}</p>
                <p class="text-sm text-green-400 mt-2">+5% par rapport à la semaine dernière</p>
            </div>

            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-folders text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ number_format($number_of_projects) }}</p>
                <p class="text-sm text-purple-400 mt-2">{{ $number_of_projects > 1 ? 'Projets en cours' : 'Projet en cours' }}</p>
            </div>

            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Lignes de code</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ number_format($globalStats['totalLines']) }}</p>
                <p class="text-sm text-blue-400 mt-2">+12% par rapport à la semaine dernière</p>
            </div>

            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Fichiers</h3>
                    <span class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="ph-file-code text-green-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ number_format($globalStats['totalFiles']) }}</p>
                <p class="text-sm text-yellow-400 mt-2">Stable depuis la semaine dernière</p>
            </div>
        </div>

        <!-- Technologies utilisées (langages) -->
        <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white mb-6">Technologies utilisées</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($globalStats['languages'] as $langName => $langData)
                    @php
                        // Déterminer la couleur et l'icône en fonction du langage
                        $bgColor = 'blue';
                        $iconClass = 'ph-file-code';
                        
                        $lang = strtolower($langName);
                        if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                            $bgColor = 'yellow';
                            $iconClass = 'ph-file-js';
                        } elseif (strpos($lang, 'css') !== false) {
                            $bgColor = 'blue';
                            $iconClass = 'ph-file-css';
                        } elseif (strpos($lang, 'html') !== false) {
                            $bgColor = 'orange';
                            $iconClass = 'ph-file-html';
                        } elseif (strpos($lang, 'php') !== false) {
                            $bgColor = 'purple';
                            $iconClass = 'ph-file-php';
                        } elseif (strpos($lang, 'python') !== false) {
                            $bgColor = 'green';
                            $iconClass = 'ph-file-py';
                        }
                    @endphp
                    
                    <div class=" p-4 rounded-xl flex items-center space-x-3">
                        <span class="w-10 h-10 rounded-lg bg-{{ $bgColor }}-600/20 flex items-center justify-center">
                            <i class="{{ $iconClass }} text-{{ $bgColor }}-400 text-xl"></i>
                        </span>
                        <div>
                            <h4 class="text-white font-medium">{{ $langName }}</h4>
                            <p class="text-sm text-gray-400">{{ $langData['formattedTime'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 bg-gray-900/30 p-6 rounded-xl text-center">
                        <p class="text-gray-400">Aucune technologie détectée pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chart et Fichier actuel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Graphiques -->
            <div class="lg:col-span-2 bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white">Activité de codage</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded-lg bg-gray-700 text-gray-300 text-xs hover:bg-gray-600">Jour</button>
                        <button class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-xs">Semaine</button>
                        <button class="px-3 py-1 rounded-lg bg-gray-700 text-gray-300 text-xs hover:bg-gray-600">Mois</button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Fichier actuel -->
            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Fichier actuel</h2>
                
                @if(isset($globalStats['currentFile']) && !empty($globalStats['currentFile']['name']))
                    @php
                        // Déterminer la couleur et l'icône en fonction du langage
                        $bgColor = 'blue';
                        $iconClass = 'ph-file-code';
                        
                        $lang = strtolower($globalStats['currentFile']['language']);
                        if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                            $bgColor = 'yellow';
                            $iconClass = 'ph-file-js';
                        } elseif (strpos($lang, 'css') !== false) {
                            $bgColor = 'blue';
                            $iconClass = 'ph-file-css';
                        } elseif (strpos($lang, 'html') !== false) {
                            $bgColor = 'orange';
                            $iconClass = 'ph-file-html';
                        } elseif (strpos($lang, 'php') !== false) {
                            $bgColor = 'purple';
                            $iconClass = 'ph-file-php';
                        } elseif (strpos($lang, 'python') !== false) {
                            $bgColor = 'green';
                            $iconClass = 'ph-file-py';
                        }
                    @endphp
                    
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="w-12 h-12 rounded-lg bg-{{ $bgColor }}-600/20 flex items-center justify-center">
                            <i class="{{ $iconClass }} text-{{ $bgColor }}-400 text-2xl"></i>
                        </span>
                        <div>
                            <h4 class="text-white font-medium">{{ basename($globalStats['currentFile']['name']) }}</h4>
                            <p class="text-sm text-gray-400 truncate max-w-xs">{{ $globalStats['currentFile']['path'] }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-900/30 p-3 rounded-lg">
                            <p class="text-sm text-gray-400">Temps passé</p>
                            <p class="text-lg font-semibold text-white">{{ $globalStats['currentFile']['formattedTime'] }}</p>
                        </div>
                        
                        <div class="bg-gray-900/30 p-3 rounded-lg">
                            <p class="text-sm text-gray-400">Lignes</p>
                            <p class="text-lg font-semibold text-white">{{ $globalStats['currentFile']['lines'] }}</p>
                        </div>
                        
                        <div class="bg-gray-900/30 p-3 rounded-lg">
                            <div class="flex justify-between mb-1">
                                <p class="text-sm text-gray-400">Efficacité</p>
                                <p class="text-sm text-green-400">{{ $globalStats['currentFile']['efficiency'] }}</p>
                            </div>
                            <div class="w-full h-2 bg-gray-800 rounded-full">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ substr($globalStats['currentFile']['efficiency'], 0, -1) }}%"></div>
                            </div>
                        </div>
                        
                        @if(isset($globalStats['currentProject']) && !empty($globalStats['currentProject']['name']))
                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-400">Projet</p>
                                        <p class="text-base text-white">{{ $globalStats['currentProject']['name'] }}</p>
                                    </div>
                                    <a href="{{ route('user.dashboard.project', $globalStats['currentProject']['id']) }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                                        Détails →
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="w-20 h-20 bg-gray-700/80 rounded-full flex items-center justify-center mb-4">
                            <i class="ph-file-x text-4xl text-gray-500"></i>
                        </div>
                        <h3 class="text-white font-medium mb-2">Aucun fichier actif</h3>
                        <p class="text-gray-400 text-sm text-center">
                            Commencez à coder pour voir les statistiques de votre fichier actuel
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Distribution des langages et projets -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Distribution des langages</h2>
                <div class="h-64">
                    <canvas id="languagesChart"></canvas>
                </div>
            </div>
            
            <div class="lg:col-span-2 bg-gray-900/30 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Projets actifs</h2>
                    <a href="{{ route('user.dashboard.projects') }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                        Voir tous →
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($projects->take(4) as $project)
                        <!-- Project Card -->
                        <div class="p-4 bg-gray-900/30 rounded-xl">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-white font-medium">{{ $project->name }}</h3>
                                <span class="px-2 py-1 rounded-full text-xs {{ isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/50 text-gray-300' }}">
                                    {{ isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id ? 'Actif' : 'Récent' }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-600 rounded-full h-2 mb-2">
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
                        <div class="col-span-2 bg-gray-900/30 p-8 rounded-xl text-center">
                            <div class="w-16 h-16 bg-gray-800/80 rounded-full mx-auto flex items-center justify-center mb-4">
                                <i class="ph-folders text-3xl text-gray-600"></i>
                            </div>
                            <h3 class="text-white font-medium mb-2">Aucun projet actif</h3>
                            <p class="text-gray-400 text-sm">Commencez un projet pour voir vos statistiques</p>
                            <button class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm flex items-center mx-auto">
                                <i class="ph-plus mr-2"></i>
                                Créer un projet
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- Script pour les charts -->
    <script>
        // Configuration du thème global de Chart.js
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#334155';

        // Graphique d'activité (ligne)
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Heures de code',
                    data: [2.5, 3.2, 4.1, 2.8, 5.2, 4.3, 2.9],
                    borderColor: '#818cf8',
                    backgroundColor: '#818cf820',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#818cf8',
                    pointBorderColor: '#111827',
                    pointHoverBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#1f2937'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + 'h';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Graphique des langages (donut)
        const languagesCtx = document.getElementById('languagesChart').getContext('2d');
        new Chart(languagesCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($globalStats['languages'] ?? [])) !!},
                datasets: [{
                    data: {!! json_encode(array_column($globalStats['languages'] ?? [], 'time_spent')) !!},
                    backgroundColor: [
                        '#fbbf24',  // Jaune pour JS
                        '#a855f7',  // Violet pour PHP
                        '#f97316',  // Orange pour HTML
                        '#3b82f6',  // Bleu pour CSS
                        '#22c55e',  // Vert pour Python
                        '#ec4899',  // Rose
                        '#0ea5e9',  // Bleu clair
                        '#6366f1'   // Indigo
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#fff',
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</x-app-layout>