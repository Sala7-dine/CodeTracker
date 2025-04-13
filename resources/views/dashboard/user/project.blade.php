<x-app-layout>
    @include("layouts.user_sidebare")

    <!-- Main Content -->
    <main class="ml-20 p-8">
        <!-- Header avec informations du projet -->
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="text-gray-400 hover:text-white mr-3">
                        <i class="ph-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-white">{{ $project->name }}</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="px-4 py-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600 transition-all flex items-center">
                        <i class="ph-export text-lg mr-1.5"></i>
                        Exporter
                    </button>
                    <button class="px-4 py-2 bg-indigo-600 rounded-lg text-white hover:bg-indigo-700 transition-all flex items-center">
                        <i class="ph-gear text-lg mr-1.5"></i>
                        Paramètres
                    </button>
                </div>
            </div>
            <p class="text-gray-400 mt-2">Dernière activité {{ $currentFile ? $currentFile->created_at->diffForHumans() : 'il y a longtemps' }}</p>
        </header>

        <!-- Statistiques générales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Temps total</p>
                        <p class="text-2xl font-bold text-white">{{ $formattedTime }}</p>
                    </div>
                </div>
                
                <!-- Mini-timeline -->
                <div class="mt-5">
                    <div class="w-full flex justify-between items-center">
                        @for ($i = 0; $i < 7; $i++)
                            @php 
                                $height = rand(5, 20); 
                                $opacity = rand(6, 10) * 10;
                            @endphp
                            <div class="w-8 bg-indigo-500/{{ $opacity }} rounded-t-sm" style="height: {{ $height }}px"></div>
                        @endfor
                    </div>
                    <div class="w-full h-px bg-gray-700 mt-1"></div>
                    <div class="w-full flex justify-between mt-1">
                        <span class="text-xs text-gray-500">Lun</span>
                        <span class="text-xs text-gray-500">Mar</span>
                        <span class="text-xs text-gray-500">Mer</span>
                        <span class="text-xs text-gray-500">Jeu</span>
                        <span class="text-xs text-gray-500">Ven</span>
                        <span class="text-xs text-gray-500">Sam</span>
                        <span class="text-xs text-gray-500">Dim</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-file-code text-blue-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Fichiers</p>
                        <p class="text-2xl font-bold text-white">{{ $project->getTotalFiles() }}</p>
                    </div>
                </div>
                
                <!-- Files activity visualization -->
                <div class="mt-5 grid grid-cols-7 gap-2">
                    @for ($i = 0; $i < 7; $i++)
                        <div class="aspect-square rounded-lg bg-blue-600/{{ rand(10, 40) }} relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xs font-medium text-blue-200">{{ rand(1, 9) }}</span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            
            <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-purple-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Lignes de code</p>
                        <p class="text-2xl font-bold text-white">{{ $project->getTotalLines() }}</p>
                    </div>
                </div>
                
                <!-- Code flow visualization -->
                <div class="mt-5 relative h-12">
                    <div class="absolute inset-0 flex">
                        @for ($i = 0; $i < 40; $i++)
                            @php $opacity = rand(3, 10) * 10; @endphp
                            <div class="flex-1 h-full">
                                <div class="w-full h-1 bg-purple-500/{{ $opacity }} mb-1"></div>
                                <div class="w-full h-1 bg-purple-500/{{ $opacity }} mb-1"></div>
                                <div class="w-full h-1 bg-purple-500/{{ $opacity }} mb-1"></div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Langages -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-6">Langages</h2>
                    
                    <div class="space-y-4">
                        @if($project->languages->count() > 0)
                            <div class="relative h-[200px] mb-6">
                                <canvas id="languagesChart"></canvas>
                            </div>
                            
                            @foreach($project->languages as $language)
                                @php
                                    $langColor = 'indigo';
                                    $iconClass = 'ph-file-code';
                                    
                                    if (strtolower($language->name) === 'javascript') {
                                        $langColor = 'yellow';
                                        $iconClass = 'ph-file-js';
                                    } elseif (strtolower($language->name) === 'css') {
                                        $langColor = 'blue';
                                        $iconClass = 'ph-file-css';
                                    } elseif (strtolower($language->name) === 'html') {
                                        $langColor = 'orange';
                                        $iconClass = 'ph-file-html';
                                    } elseif (strtolower($language->name) === 'php') {
                                        $langColor = 'purple';
                                        $iconClass = 'ph-file-php';
                                    } elseif (strtolower($language->name) === 'python') {
                                        $langColor = 'green';
                                        $iconClass = 'ph-file-py';
                                    }
                                    
                                    // Calculer le pourcentage pour ce langage
                                    $totalTime = $project->getTotalTime();
                                    $percentage = $totalTime > 0 ? ($language->time_ms / $totalTime) * 100 : 0;
                                @endphp
                                
                                <div class="bg-gray-700/50 p-4 rounded-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <i class="{{ $iconClass }} text-{{ $langColor }}-400 mr-2"></i>
                                            <span class="text-white font-medium">{{ $language->name }}</span>
                                        </div>
                                        <span class="text-{{ $langColor }}-400 text-sm">{{ \App\Models\Language::formatTime($language->time_ms) }}</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-5 gap-1">
                                        <div class="col-span-3">
                                            <div class="text-xs text-gray-400 mb-1">Fichiers</div>
                                            <div class="text-white font-medium">{{ $language->files }}</div>
                                        </div>
                                        <div class="col-span-2">
                                            <div class="text-xs text-gray-400 mb-1">Lignes</div>
                                            <div class="text-white font-medium">{{ $language->lines }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-gray-700/50 p-5 rounded-xl text-center">
                                <i class="ph-code-block text-indigo-400 text-3xl mb-2"></i>
                                <p class="text-gray-300">Aucun langage détecté</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Fichiers et activités -->
            <div class="lg:col-span-2">
                <!-- Fichiers récents -->
                <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-white">Fichiers récents</h2>
                        <div class="flex rounded-lg overflow-hidden border border-gray-700">
                            <input type="text" placeholder="Rechercher un fichier..." 
                                   class="px-3 py-1.5 bg-gray-900 text-gray-300 w-64 border-none focus:outline-none">
                            <button class="px-3 py-1.5 bg-gray-700 text-gray-300">
                                <i class="ph-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                        @forelse($activities->unique('file_path')->take(10) as $activity)
                            @php
                                $langColor = 'indigo';
                                $iconClass = 'ph-file-code';
                                
                                if ($activity->language) {
                                    $lang = strtolower($activity->language);
                                    if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                                        $iconClass = 'ph-file-js';
                                        $langColor = 'yellow';
                                    } elseif (strpos($lang, 'css') !== false) {
                                        $iconClass = 'ph-file-css';
                                        $langColor = 'blue';
                                    } elseif (strpos($lang, 'html') !== false) {
                                        $iconClass = 'ph-file-html';
                                        $langColor = 'orange';
                                    } elseif (strpos($lang, 'php') !== false) {
                                        $iconClass = 'ph-file-php';
                                        $langColor = 'purple';
                                    } elseif (strpos($lang, 'python') !== false) {
                                        $iconClass = 'ph-file-py';
                                        $langColor = 'green';
                                    }
                                }
                            @endphp
                            
                            <div class="flex items-center justify-between p-4 bg-gray-700/50 border border-gray-600 hover:border-{{ $langColor }}-500/50 transition-all rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <span class="w-10 h-10 rounded-lg bg-{{ $langColor }}-600/20 flex items-center justify-center">
                                        <i class="{{ $iconClass }} text-{{ $langColor }}-400"></i>
                                    </span>
                                    <div>
                                        <h4 class="text-white font-medium">{{ $activity->file_name }}</h4>
                                        <p class="text-sm text-gray-400 truncate max-w-xs">{{ $activity->file_path }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-gray-300">{{ \App\Models\Language::formatTime($activity->duration * 1000) }}</span>
                                    <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-700/50 p-5 rounded-xl text-center">
                                <i class="ph-files text-indigo-400 text-3xl mb-2"></i>
                                <p class="text-gray-300">Aucun fichier récent</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Timeline d'activité -->
                <div class="bg-gray-800/50 p-6 rounded-2xl border border-gray-700">
                    <h2 class="text-xl font-semibold text-white mb-6">Historique d'activité</h2>
                    
                    <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2">
                        @forelse($activities->take(15) as $activity)
                            <div class="relative pl-6 pb-6 border-l border-gray-700 last:border-0 last:pb-0">
                                <!-- Bullet point -->
                                <div class="absolute top-0 left-0 w-3 h-3 -translate-x-1.5 rounded-full bg-indigo-500"></div>
                                
                                <div class="bg-gray-700/30 p-4 rounded-xl border border-gray-600">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $langColor = 'indigo';
                                                $iconClass = 'ph-file-code';
                                                
                                                if ($activity->language) {
                                                    $lang = strtolower($activity->language);
                                                    if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                                                        $iconClass = 'ph-file-js';
                                                        $langColor = 'yellow';
                                                    } elseif (strpos($lang, 'css') !== false) {
                                                        $iconClass = 'ph-file-css';
                                                        $langColor = 'blue';
                                                    } elseif (strpos($lang, 'html') !== false) {
                                                        $iconClass = 'ph-file-html';
                                                        $langColor = 'orange';
                                                    } elseif (strpos($lang, 'php') !== false) {
                                                        $iconClass = 'ph-file-php';
                                                        $langColor = 'purple';
                                                    } elseif (strpos($lang, 'python') !== false) {
                                                        $iconClass = 'ph-file-py';
                                                        $langColor = 'green';
                                                    }
                                                }
                                            @endphp
                                            <i class="{{ $iconClass }} text-{{ $langColor }}-400"></i>
                                            <span class="text-white font-medium">{{ $activity->file_name }}</span>
                                        </div>
                                        <span class="text-gray-400 text-sm">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-3 mb-2">
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $langColor }}-500/20 text-{{ $langColor }}-400">
                                            {{ $activity->language ?? 'Inconnu' }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-600/50 text-gray-300">
                                            {{ $activity->lines }} lignes
                                        </span>
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-500/20 text-indigo-400">
                                            {{ \App\Models\Language::formatTime($activity->duration * 1000) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-400 text-sm truncate">{{ $activity->file_path }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-700/50 p-5 rounded-xl text-center">
                                <i class="ph-activity text-indigo-400 text-3xl mb-2"></i>
                                <p class="text-gray-300">Aucune activité récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Graphique des langages
        const createLanguagesChart = () => {
            const ctx = document.getElementById('languagesChart').getContext('2d');
            
            // Préparation des données
            const languages = @json($project->languages->map(function($lang) {
                return [
                    'name' => $lang->name,
                    'time' => $lang->time_ms,
                ];
            }));
            
            const labels = languages.map(lang => lang.name);
            const data = languages.map(lang => lang.time);
            
            // Couleurs pour chaque langage
            const colors = languages.map(lang => {
                const name = lang.name.toLowerCase();
                if (name.includes('javascript')) return '#fbbf24';
                if (name.includes('css')) return '#3b82f6';
                if (name.includes('html')) return '#f97316';
                if (name.includes('php')) return '#a855f7';
                if (name.includes('python')) return '#22c55e';
                return '#6366f1';
            });
            
            // Création du graphique
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
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
                    cutout: '75%'
                }
            });
        };
        
        // Initialiser les graphiques quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', () => {
            createLanguagesChart();
        });
    </script>
</x-app-layout>