<x-app-layout>
    @include("layouts.user_sidebare")   

    <!-- Main Content -->
    <main class="ml-20 p-8">
        @include("layouts.user_header")

        <!-- En-tête de la page avec filtres -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">Statistiques globales</h1>
                <p class="text-gray-400">Vue d'ensemble de tous vos projets</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Filtres -->
                <div class="bg-gray-800/50 p-1 rounded-lg flex items-center space-x-1">
                    <a href="{{ route('user.dashboard.statistiques', ['period' => 'all']) }}" id="filter-all" class="px-4 py-2 {{ $period == 'all' || !$period ? 'bg-indigo-600 text-white' : 'bg-transparent text-gray-300 hover:bg-gray-700' }} rounded-md text-sm transition-colors">
                        Tous
                    </a>
                    <a href="{{ route('user.dashboard.statistiques', ['period' => 'week']) }}" id="filter-week" class="px-4 py-2 {{ $period == 'week' ? 'bg-indigo-600 text-white' : 'bg-transparent text-gray-300 hover:bg-gray-700' }} rounded-md text-sm transition-colors">
                        Cette semaine
                    </a>
                    <a href="{{ route('user.dashboard.statistiques', ['period' => 'month']) }}" id="filter-month" class="px-4 py-2 {{ $period == 'month' ? 'bg-indigo-600 text-white' : 'bg-transparent text-gray-300 hover:bg-gray-700' }} rounded-md text-sm transition-colors">
                        Ce mois
                    </a>
                </div>
                
                <!-- Sélecteur de date personnalisé -->
                <div class="relative">
                    <input type="date" class="px-4 py-2 bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Cartes d'informations -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Carte: Temps total -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-600/10 rounded-full filter blur-xl -mr-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Temps total</p>
                        <p class="text-2xl font-bold text-white" id="total-time">{{ $formattedTotalTime }}</p>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <p class="text-sm text-indigo-400">
                        <span class="flex items-center">
                            <i class="ph-trend-up mr-1"></i>
                            +12.5% 
                        </span>
                        <span class="text-gray-500 text-xs">vs semaine dernière</span>
                    </p>
                </div>
            </div>

            <!-- Carte: Projets actifs -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-600/10 rounded-full filter blur-xl -mr-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="ph-folders text-green-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Projets actifs</p>
                        <p class="text-2xl font-bold text-white" id="active-projects">{{ $activeProjects }}</p>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <p class="text-sm text-green-400">
                        <span class="flex items-center">
                            <i class="ph-trend-up mr-1"></i>
                            +2 
                        </span>
                        <span class="text-gray-500 text-xs">vs mois dernier</span>
                    </p>
                </div>
            </div>

            <!-- Carte: Total de lignes de code -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-600/10 rounded-full filter blur-xl -mr-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-purple-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Lignes de code</p>
                        <p class="text-2xl font-bold text-white" id="total-lines">{{ number_format($totalLines) }}</p>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <p class="text-sm text-purple-400">
                        <span class="flex items-center">
                            <i class="ph-trend-up mr-1"></i>
                            +3,452
                        </span>
                        <span class="text-gray-500 text-xs">vs semaine dernière</span>
                    </p>
                </div>
            </div>

            <!-- Carte: Moyenne quotidienne -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-600/10 rounded-full filter blur-xl -mr-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-chart-line text-blue-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Moyenne quotidienne</p>
                        <p class="text-2xl font-bold text-white" id="avg-daily">{{ $avgDaily }}h</p>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <p class="text-sm text-blue-400">
                        <span class="flex items-center">
                            <i class="ph-trend-up mr-1"></i>
                            +0.7h
                        </span>
                        <span class="text-gray-500 text-xs">vs semaine dernière</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Première ligne de graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Graphique 1: Tendances d'activité -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600/40 to-purple-600/40 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/10">
                            <i class="ph-chart-line-up text-indigo-400"></i>
                        </span>
                        Tendances d'activité
                    </h2>
                    <div class="flex space-x-2">
                        <button class="period-btn px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-lg">Journalier</button>
                        <button class="period-btn px-3 py-1.5 bg-gray-700 text-gray-300 hover:bg-gray-600 text-sm rounded-lg" data-period="weekly">Hebdomadaire</button>
                        <button class="period-btn px-3 py-1.5 bg-gray-700 text-gray-300 hover:bg-gray-600 text-sm rounded-lg" data-period="monthly">Mensuel</button>
                    </div>
                </div>
                
                <div class="relative h-[350px]">
                    <canvas id="activityTrendsChart"></canvas>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-900/50 p-3 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Temps le plus productif</p>
                        <p class="text-white font-medium">{{ $mostProductiveTime }}</p>
                    </div>
                    <div class="bg-gray-900/50 p-3 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Jour le plus actif</p>
                        <p class="text-white font-medium">{{ $mostActiveDay }}</p>
                    </div>
                    <div class="bg-gray-900/50 p-3 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Moyenne</p>
                        <p class="text-white font-medium">{{ $avgDaily }}h/jour</p>
                    </div>
                </div>
            </div>

            <!-- Graphique 2: Distribution par langage -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white flex items-center mb-6">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600/40 to-teal-600/40 flex items-center justify-center mr-3 shadow-lg shadow-green-500/10">
                        <i class="ph-code text-green-400"></i>
                    </span>
                    Distribution par langage
                </h2>
                
                <div class="relative h-[350px]">
                    <canvas id="languageDistributionChart"></canvas>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    @if(isset($languageDistribution['details']) && count($languageDistribution['details']) > 0)
                        @foreach(array_slice($languageDistribution['details'], 0, 4) as $lang => $details)
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $details['color'] }}"></div>
                                <p class="text-gray-300 text-sm">{{ $lang }} <span class="text-gray-500">{{ $details['percentage'] }}%</span></p>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-4 text-center text-gray-400">
                            Aucune donnée de langage disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Deuxième ligne de graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Graphique 3: Heatmap d'activité -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white flex items-center mb-6">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600/40 to-cyan-600/40 flex items-center justify-center mr-3 shadow-lg shadow-blue-500/10">
                        <i class="ph-calendar-check text-blue-400"></i>
                    </span>
                    Heatmap d'activité
                </h2>
                
                <div class="relative h-[350px]">
                    <canvas id="activityHeatmapChart"></canvas>
                </div>
                
                <div class="flex items-center justify-between text-sm text-gray-400 mt-6">
                    <div>Moins actif</div>
                    <div class="flex space-x-1">
                        <div class="w-5 h-5 bg-gray-700/50 rounded"></div>
                        <div class="w-5 h-5 bg-blue-500/30 rounded"></div>
                        <div class="w-5 h-5 bg-blue-500/50 rounded"></div>
                        <div class="w-5 h-5 bg-blue-500/70 rounded"></div>
                        <div class="w-5 h-5 bg-blue-500/90 rounded"></div>
                    </div>
                    <div>Plus actif</div>
                </div>
            </div>

            <!-- Graphique 4: Progression par projet -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600/40 to-amber-600/40 flex items-center justify-center mr-3 shadow-lg shadow-orange-500/10">
                            <i class="ph-folders text-orange-400"></i>
                        </span>
                        Progression par projet
                    </h2>
                    <select class="bg-gray-800 text-gray-300 px-3 py-2 rounded-lg border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option>5 projets les plus actifs</option>
                        <option>Tous les projets</option>
                    </select>
                </div>
                
                <div class="relative h-[350px]">
                    <canvas id="projectProgressionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section additionnelle: Badges et réalisations -->
        <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white flex items-center mb-6">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-600/40 to-rose-600/40 flex items-center justify-center mr-3 shadow-lg shadow-pink-500/10">
                    <i class="ph-trophy text-pink-400"></i>
                </span>
                Badges et réalisations
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($badges as $key => $badge)
                    <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700 text-center {{ $badge['unlocked'] ? '' : 'opacity-50' }}">
                        <div class="w-16 h-16 bg-gradient-to-br {{ $badge['color'] }} rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="{{ $badge['icon'] }} text-white text-2xl"></i>
                        </div>
                        <h3 class="text-white font-medium">{{ $badge['name'] }}</h3>
                        <p class="text-gray-400 text-xs mt-1">{{ $badge['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Scripts pour les graphiques -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration générale de Chart.js
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = '#334155';
            
            // Graphique 1: Tendances d'activité avec données réelles
            const activityTrendsCtx = document.getElementById('activityTrendsChart').getContext('2d');
            const activityTrendsChart = new Chart(activityTrendsCtx, {
                type: 'line',
                data: {
                    labels: @json($activityTrends['labels']),
                    datasets: [{
                        label: 'Heures de code',
                        data: @json($activityTrends['data']),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#1f2937',
                        pointBorderWidth: 2,
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
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(99, 102, 241, 0.6)',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' heures';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
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
            
            // Graphique 2: Distribution par langage avec données réelles
            const languageDistributionCtx = document.getElementById('languageDistributionChart').getContext('2d');
            const languageDistributionChart = new Chart(languageDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($languageDistribution['labels']),
                    datasets: [{
                        data: @json($languageDistribution['data']),
                        backgroundColor: @json($languageDistribution['colors']),
                        borderWidth: 1,
                        borderColor: '#1f2937'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(99, 102, 241, 0.6)',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label;
                                    const value = context.parsed;
                                    const details = @json($languageDistribution['details']);
                                    const formattedTime = details[label] ? details[label].formattedTime : '';
                                    return [
                                        label + ': ' + value + '%',
                                        'Temps total: ' + formattedTime,
                                        'Lignes: ' + (details[label] ? details[label].lines.toLocaleString() : '0')
                                    ];
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique 3: Heatmap d'activité avec données réelles
            const activityHeatmapCtx = document.getElementById('activityHeatmapChart').getContext('2d');
            const activityHeatmapChart = new Chart(activityHeatmapCtx, {
                type: 'bar',
                data: {
                    labels: @json($activityHeatmap['days']),
                    datasets: [
                        @foreach($activityHeatmap['timeSlots'] as $index => $slot)
                        {
                            label: '{{ $slot }}',
                            data: @json($activityHeatmap['data'][$index]),
                            backgroundColor: 'rgba({{ $index * 50 + 59 }}, {{ 100 + $index * 15 }}, 246, 0.7)',
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                            borderRadius: 4
                        }{{ $loop->last ? '' : ',' }}
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'rect'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(99, 102, 241, 0.6)',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + ' heures';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + 'h';
                                }
                            },
                            stacked: true
                        }
                    }
                }
            });
            
            // Graphique 4: Progression par projet avec données réelles
            const projectProgressionCtx = document.getElementById('projectProgressionChart').getContext('2d');
            const projectProgressionChart = new Chart(projectProgressionCtx, {
                type: 'bar',
                data: {
                    labels: @json($projectProgression['labels']),
                    datasets: [{
                        label: 'Temps passé (heures)',
                        data: @json($projectProgression['data']),
                        backgroundColor: @json(array_slice($projectProgression['colors'], 0, count($projectProgression['labels']))),
                        borderRadius: 6,
                        barThickness: 20,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(99, 102, 241, 0.6)',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.x + ' heures';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + 'h';
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Gestion des boutons de filtrage
            document.querySelectorAll('#filter-all, #filter-week, #filter-month').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Le comportement est maintenant géré par les liens href, nous n'avons plus besoin de JavaScript ici
                    // Nous gardons ce code pour les futurs ajouts de fonctionnalités AJAX si nécessaire
                });
            });
            
            // Gestion des boutons de période pour le premier graphique
            document.querySelectorAll('.period-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Réinitialiser tous les boutons
                    document.querySelectorAll('.period-btn').forEach(btn => {
                        btn.classList.remove('bg-indigo-600', 'text-white');
                        btn.classList.add('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    });
                    
                    // Mettre en évidence le bouton actif
                    this.classList.remove('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    this.classList.add('bg-indigo-600', 'text-white');
                    
                    // Mettre à jour le graphique en fonction de la période
                    const period = this.getAttribute('data-period') || 'daily';
                    updateActivityTrendsChart(period);
                });
            });
            
            // Fonction pour mettre à jour le graphique de tendances d'activité en fonction de la période
            function updateActivityTrendsChart(period) {
                let labels, data;
                
                switch(period) {
                    case 'weekly':
                        labels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
                        data = [18.5, 22.3, 15.8, 20.2];
                        break;
                    case 'monthly':
                        labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
                        data = [65, 78, 82, 75, 92, 84];
                        break;
                    default: // daily
                        labels = @json($activityTrends['labels']);
                        data = @json($activityTrends['data']);
                }
                
                activityTrendsChart.data.labels = labels;
                activityTrendsChart.data.datasets[0].data = data;
                
                // Modifier les options du graphique en fonction de la période
                if (period === 'monthly') {
                    activityTrendsChart.options.scales.y.ticks.callback = function(value) {
                        return value + 'h';
                    };
                }
                
                activityTrendsChart.update();
            }
        });
    </script>
</x-app-layout>