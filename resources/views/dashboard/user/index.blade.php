<x-app-layout>
    <aside class="fixed left-0 top-0 h-screen w-20 bg-gray-800 border-r border-gray-700 flex flex-col items-center py-8 space-y-8">
        <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
            <i class="ph-code text-2xl text-white"></i>
        </div>
        
        <nav class="flex flex-col space-y-6">
            <a href="#" class="w-12 h-12 rounded-xl bg-gray-700 flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
                <i class="ph-squares-four text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <i class="ph-folder-simple text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <i class="ph-chart-line-up text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <i class="ph-gear-six text-xl"></i>
            </a>
            <!-- Logout icon -->

            <form action="{{ route('logout') }}" method="POST">
                @csrf 
                <button class="w-12 h-12 rounded-xl hover:bg-red-600 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                    <i class="ph-sign-out text-xl"></i>
                </button>
            </form>

    
                
            </a>
        </nav>
        
    </aside>

    <!-- Main Content -->
    <main class="ml-20 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                <p class="text-gray-400">Bienvenue sur CodeTrack, John</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600 transition-all">
                    <i class="ph-bell text-xl"></i>
                </button>
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">JD</div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Temps total</h3>
                    <span class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">32h 45m</p>
                <p class="text-sm text-green-400 mt-2">+2.5% vs semaine dernière</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-folders text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">7</p>
                <p class="text-sm text-purple-400 mt-2">2 nouveaux cette semaine</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Lignes de code</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">12,847</p>
                <p class="text-sm text-blue-400 mt-2">+847 aujourd'hui</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Productivité</h3>
                    <span class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="ph-chart-bar text-green-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">85%</p>
                <p class="text-sm text-green-400 mt-2">Objectif atteint</p>
            </div>
        </div>

        <!-- Nouvelle section: Technologies utilisées -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white mb-6">Technologies utilisées</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-700/50 p-4 rounded-xl flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-lg bg-yellow-600/20 flex items-center justify-center">
                        <i class="ph-file-js text-yellow-400 text-xl"></i>
                    </span>
                    <div>
                        <h4 class="text-white font-medium">JavaScript</h4>
                        <p class="text-sm text-gray-400">8h 30m</p>
                    </div>
                </div>

                <div class="bg-gray-700/50 p-4 rounded-xl flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-file-css text-blue-400 text-xl"></i>
                    </span>
                    <div>
                        <h4 class="text-white font-medium">CSS</h4>
                        <p class="text-sm text-gray-400">4h 15m</p>
                    </div>
                </div>

                <div class="bg-gray-700/50 p-4 rounded-xl flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-lg bg-orange-600/20 flex items-center justify-center">
                        <i class="ph-file-html text-orange-400 text-xl"></i>
                    </span>
                    <div>
                        <h4 class="text-white font-medium">HTML</h4>
                        <p class="text-sm text-gray-400">3h 45m</p>
                    </div>
                </div>

                <div class="bg-gray-700/50 p-4 rounded-xl flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-file-php text-purple-400 text-xl"></i>
                    </span>
                    <div>
                        <h4 class="text-white font-medium">PHP</h4>
                        <p class="text-sm text-gray-400">5h 20m</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chart -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Activité de codage</h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 text-sm hover:bg-gray-600">Jour</button>
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm">Semaine</button>
                    <button class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 text-sm hover:bg-gray-600">Mois</button>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Line Chart -->
                <div>
                    <canvas id="activityChart" class="w-full h-[200px]"></canvas>
                </div>
                <!-- Doughnut Chart -->
                <div>
                    <canvas id="languagesChart" class="w-full h-[200px]"></canvas>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Projets actifs</h2>
                <div class="space-y-4">
                    <!-- Project Card -->
                    <div class="p-4 bg-gray-700/50 rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-white font-medium">CodeTrack</h3>
                            <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Actif</span>
                        </div>
                        <div class="w-full bg-gray-600 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="text-gray-400">12h 30m cette semaine</span>
                            <span class="text-indigo-400">75%</span>
                        </div>
                    </div>
                    <!-- Répéter pour d'autres projets -->
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Fichiers récents</h2>
                <div class="space-y-4">
                    <!-- File Card -->
                    <div class="flex items-center justify-between p-4 bg-gray-700/50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <i class="ph-file-js text-blue-400"></i>
                            </span>
                            <div>
                                <h4 class="text-white">main.js</h4>
                                <p class="text-sm text-gray-400">CodeTrack/src</p>
                            </div>
                        </div>
                        <span class="text-gray-400">2h 15m</span>
                    </div>
                    <!-- Répéter pour d'autres fichiers -->
                </div>
            </div>
        </div>
    </main>

    <!-- Ajout du script Chart.js à la fin du body -->
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
                    data: [4.5, 6, 3.5, 7, 5.5, 4, 2],
                    borderColor: '#818cf8',
                    backgroundColor: '#818cf820',
                    tension: 0.4,
                    fill: true
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
                        display: true,
                        text: 'Temps de codage quotidien',
                        color: '#fff',
                        font: {
                            size: 16,
                            weight: 'normal'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#1f2937'
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
                labels: ['JavaScript', 'PHP', 'HTML', 'CSS', 'Python'],
                datasets: [{
                    data: [35, 25, 15, 15, 10],
                    backgroundColor: [
                        '#fbbf24',  // JavaScript
                        '#a855f7',  // PHP
                        '#f97316',  // HTML
                        '#3b82f6',  // CSS
                        '#22c55e'   // Python
                    ],
                    borderWidth: 0
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
                    },
                    title: {
                        display: true,
                        text: 'Répartition des langages',
                        color: '#fff',
                        font: {
                            size: 16,
                            weight: 'normal'
                        }
                    }
                },
                cutout: '75%'
            }
        });
    </script>
</x-app-layout>