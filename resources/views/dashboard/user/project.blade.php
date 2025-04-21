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
            <div class="flex items-center mt-2">
                <span class="px-3 py-1 rounded-full bg-indigo-600/20 text-indigo-400 text-sm mr-2">
                    {{ $activities->count() }} activités
                </span>
                <p class="text-gray-400">Dernière activité {{ $currentFile ? $currentFile->created_at->diffForHumans() : 'il y a longtemps' }}</p>
            </div>
        </header>

        <!-- Statistiques générales dans des cartes élégantes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <!-- Élément décoratif -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-600/10 rounded-full filter blur-xl -mr-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Temps total</p>
                        <p class="text-2xl font-bold text-white">{{ $formattedTime }}</p>
                    </div>
                </div>
                
                <!-- Mini-timeline amélioré -->
                <div class="mt-5 relative z-10">
                    <div class="w-full flex justify-between items-end">
                        @for ($i = 0; $i < 7; $i++)
                            @php 
                                $height = rand(10, 30); 
                                $opacity = rand(6, 10) * 10;
                                $glow = $i == 4 ? 'shadow-lg shadow-indigo-500/20' : '';
                                $active = $i == 4 ? 'bg-indigo-400' : "bg-indigo-500/{$opacity}";
                            @endphp
                            <div class="w-7 {{ $active }} rounded-t-md {{ $glow }}" style="height: {{ $height }}px"></div>
                        @endfor
                    </div>
                    <div class="w-full h-0.5 bg-gray-700/50 mt-1 rounded-full"></div>
                    <div class="w-full flex justify-between mt-2">
                        <span class="text-xs text-gray-500">Lun</span>
                        <span class="text-xs text-gray-500">Mar</span>
                        <span class="text-xs text-gray-500">Mer</span>
                        <span class="text-xs text-indigo-400 font-medium">Jeu</span>
                        <span class="text-xs text-gray-500">Ven</span>
                        <span class="text-xs text-gray-500">Sam</span>
                        <span class="text-xs text-gray-500">Dim</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <!-- Élément décoratif -->
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-600/10 rounded-full filter blur-xl -ml-8 -mb-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-file-code text-blue-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Fichiers</p>
                        <p class="text-2xl font-bold text-white">{{ $project->getTotalFiles() }}</p>
                    </div>
                </div>
                
                <!-- Calendrier de contribution -->
                <div class="mt-5 grid grid-cols-7 gap-1.5 relative z-10">
                    @foreach($contributionCalendar ?? [] as $day)
                        @php 
                            $intensity = $day['intensity'];
                            $bg = $intensity == 0 ? 'bg-gray-700/50' : ($intensity < 3 ? 'bg-blue-500/30' : ($intensity < 6 ? 'bg-blue-500/60' : 'bg-blue-400'));
                        @endphp
                        <div class="aspect-square rounded-sm {{ $bg }} hover:ring-2 hover:ring-blue-300 transition-all"
                             title="{{ $day['filesCount'] }} fichier(s) modifiés le {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}">
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 relative overflow-hidden">
                <!-- Élément décoratif -->
                <div class="absolute top-0 left-0 w-24 h-24 bg-purple-600/10 rounded-full filter blur-xl -ml-8 -mt-8"></div>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-14 h-14 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-purple-400 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Lignes de code</p>
                        <p class="text-2xl font-bold text-white">{{ $project->getTotalLines() }}</p>
                    </div>
                </div>
                
                <!-- Code pulse visualization -->
                <div class="mt-5 relative h-12 z-10">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-0.5 w-full bg-gray-700/50 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 w-3/4 rounded-full"></div>
                        </div>
                    </div>
                    <!-- Pulse dots -->
                    @foreach([15, 38, 62, 74] as $position)
                        <div class="absolute top-1/2 -translate-y-1/2" style="left: {{ $position }}%">
                            <span class="block w-3 h-3 rounded-full bg-purple-500 animate-ping absolute"></span>
                            <span class="block w-3 h-3 rounded-full bg-purple-400"></span>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-between text-xs text-gray-400 mt-6">
                    <div>
                        <p>Moyenne</p>
                        <p class="text-white font-medium">{{ round($project->getTotalLines() / max(1, $project->getTotalFiles())) }} lignes/fichier</p>
                    </div>
                    <div class="text-right">
                        <p>Changements récents</p>
                        <p class="text-green-400 font-medium">+{{ rand(100, 500) }} lignes cette semaine</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations sur l'environnement -->
        <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600/40 to-purple-600/40 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/10">
                    <i class="ph-desktop text-indigo-400"></i>
                </span>
                Environnement de développement
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center mr-3">
                            <i class="ph-code text-indigo-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Éditeur</p>
                            <p class="text-white font-medium">VS Code</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-700 text-gray-300">v1.80.1</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-600/20 text-green-400">Stable</span>
                    </div>
                </div>
                
                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center mr-3">
                            <i class="ph-laptop text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Machine</p>
                            <p class="text-white font-medium">{{ gethostname() }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-700 text-gray-300">{{ PHP_OS }}</span>
                    </div>
                </div>
                
                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center mr-3">
                            <i class="ph-git-branch text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Contrôle de version</p>
                            <p class="text-white font-medium">Git</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-purple-600/20 text-purple-400">main</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-700 text-gray-300">{{ substr(md5($project->name), 0, 7) }}</span>
                    </div>
                </div>
                
                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center mr-3">
                            <i class="ph-plug text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Extensions</p>
                            <p class="text-white font-medium">5 actives</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-600/20 text-green-400">ESLint</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-600/20 text-green-400">Prettier</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section principale avec graphiques et fichiers -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Langages avec graphiques améliorés -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600/40 to-purple-600/40 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/10">
                            <i class="ph-code text-indigo-400"></i>
                        </span>
                        Langages
                    </h2>
                    
                    <div class="space-y-6">
                        @if($project->languages->count() > 0)
                            <div class="relative h-[350px] mb-6">
                                <canvas id="languagesChart"></canvas>
                            </div>
                            <div class="grid grid-cols-4 gap-2">
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


                                
                                <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700 hover:border-{{ $langColor }}-500/50 transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <i class="{{ $iconClass }} text-{{ $langColor }}-400 mr-2"></i>
                                            <span class="text-white font-medium">{{ $language->name }}</span>
                                        </div>
                                        <span class="text-{{ $langColor }}-400 text-sm">{{ \App\Models\Language::formatTime($language->time_ms) }}</span>
                                    </div>
                                    
                                    <div class="w-full h-1.5 bg-gray-700 rounded-full mb-3">
                                        <div class="h-full bg-{{ $langColor }}-500 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-5 gap-1 text-center">
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
                        </div>
                        @else
                            <div class="bg-gray-900/50 p-6 rounded-xl text-center">
                                <i class="ph-code-block text-indigo-400 text-4xl mb-3"></i>
                                <p class="text-white font-medium mb-1">Aucun langage détecté</p>
                                <p class="text-gray-400 text-sm">Les langages apparaîtront dès que vous commencerez à coder.</p>
                            </div>
                        @endif
                    </div>
                </div>
                

                <div class="grid grid-cols-2 gap-2">

                     <!-- Ajout d'un graphique de tendance de productivité -->
                <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600/40 to-emerald-600/40 flex items-center justify-center mr-3 shadow-lg shadow-green-500/10">
                            <i class="ph-chart-line-up text-green-400"></i>
                        </span>
                        Productivité
                    </h2>
                    
                    <div class="relative h-[200px] mb-4">
                        <canvas id="productivityChart"></canvas>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-900/50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-400 mb-1">Pic de productivité</p>
                            <p class="text-white">{{ $productivityStats['peakHour'] ?? '12h - 14h' }}</p>
                        </div>
                        <div class="bg-gray-900/50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-400 mb-1">Efficacité</p>
                            <p class="text-{{ $productivityStats['percentChange'] >= 0 ? 'green' : 'red' }}-400">
                                {{ $productivityStats['efficiency'] ?? 75 }}%
                                
                                @if(isset($productivityStats['percentChange']))
                                    <span class="text-xs ml-1">
                                        @if($productivityStats['percentChange'] > 0)
                                            <i class="ph-arrow-up"></i>
                                        @elseif($productivityStats['percentChange'] < 0)
                                            <i class="ph-arrow-down"></i>
                                        @endif
                                        {{ abs($productivityStats['percentChange']) }}%
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nouveau graphique d'activité par heure de la journée -->
                <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600/40 to-pink-600/40 flex items-center justify-center mr-3 shadow-lg shadow-purple-500/10">
                            <i class="ph-chart-bar text-purple-400"></i>
                        </span>
                        Répartition d'activité
                    </h2>
                    
                    <div class="relative h-[300px]">
                        <canvas id="activityHeatmapChart"></canvas>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                            <p class="text-xs text-gray-400 mb-1">Heures de code</p>
                            <p class="text-xl text-white font-semibold">{{ round($project->getTotalTime() / 3600000, 1) }}</p>
                            <p class="text-xs text-purple-400 mt-1">Projet total</p>
                        </div>
                        
                        <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                            <p class="text-xs text-gray-400 mb-1">Moyenne par jour</p>
                            <p class="text-xl text-white font-semibold">
                                @php
                                    $filteredDays = isset($chartData['weekly']['data']) ? array_filter($chartData['weekly']['data']) : [];
                                    $countFilteredDays = count($filteredDays);
                                    $avgDailyHours = ($countFilteredDays > 0) 
                                        ? array_sum($chartData['weekly']['data']) / $countFilteredDays
                                        : 0;
                                @endphp
                                {{ number_format($avgDailyHours, 1) }} h
                            </p>
                            @if(isset($productivityStats['percentChange']))
                                <p class="text-xs text-{{ $productivityStats['percentChange'] >= 0 ? 'green' : 'red' }}-400 mt-1">
                                    {{ $productivityStats['percentChange'] > 0 ? '+' : '' }}{{ $productivityStats['percentChange'] }}% cette semaine
                                </p>
                            @endif
                        </div>
                        
                        <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                            <p class="text-xs text-gray-400 mb-1">Jours actifs</p>
                            <p class="text-xl text-white font-semibold">
                                @php
                                    $activeDays = isset($chartData['weekly']['data']) 
                                        ? count(array_filter($chartData['weekly']['data'], function($h) { return $h > 0; }))
                                        : 0;
                                @endphp
                                {{ $activeDays }} / 7
                            </p>
                            <p class="text-xs text-blue-400 mt-1">
                                {{ $activities->count() }} sessions
                            </p>
                        </div>
                    </div>
                </div>

                </div>
               
            </div>
            
            <!-- Colonne de droite avec fichiers récents et nouveau graphique d'activité -->
            <div class="">
                <!-- Fichiers récents -->
                <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600/40 to-cyan-600/40 flex items-center justify-center mr-3 shadow-lg shadow-blue-500/10">
                                <i class="ph-files text-blue-400"></i>
                            </span>
                            Fichiers récents
                        </h2>
                        <div class="flex rounded-lg overflow-hidden border border-gray-700">
                            <input type="text" placeholder="Rechercher un fichier..." 
                                   class="px-3 py-1.5 bg-gray-900 text-gray-300 w-64 border-none focus:outline-none">
                            <button class="px-3 py-1.5 bg-gray-700 text-gray-300">
                                <i class="ph-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                        @forelse($activities->unique('file_path')->take(8) as $activity)
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
                            
                            <div class="flex items-center p-4 bg-gray-900/50 border border-gray-700 hover:border-{{ $langColor }}-500/50 transition-all rounded-xl group hover:bg-gray-900/80">
                                <span class="w-10 h-10 rounded-lg bg-{{ $langColor }}-600/20 flex items-center justify-center mr-3">
                                    <i class="{{ $iconClass }} text-{{ $langColor }}-400"></i>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-medium">{{ $activity->file_name }}</h4>
                                    <p class="text-sm text-gray-400 truncate max-w-xs">{{ $activity->file_path }}</p>
                                </div>
                                <div class="flex flex-col items-end ml-3">
                                    <span class="text-gray-300">{{ \App\Models\Language::formatTime($activity->duration * 1000) }}</span>
                                    <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-900/50 p-6 rounded-xl text-center">
                                <i class="ph-files text-blue-400 text-4xl mb-3"></i>
                                <p class="text-white font-medium mb-1">Aucun fichier récent</p>
                                <p class="text-gray-400 text-sm">Les fichiers apparaîtront dès que vous commencerez à coder.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
               
            </div>




            <div class="grid grid-cols-1 gap-2">
                 


                <!-- Nouvelle section avec le graphique d'activité temporelle -->
            <div class="bg-gray-900/30 p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600/40 to-purple-600/40 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/10">
                            <i class="ph-clock text-indigo-400"></i>
                        </span>
                        Activité temporelle
                    </h2>
                    <div class="flex space-x-2">
                        <button class="period-btn px-4 py-2 bg-indigo-600 text-white rounded-lg">Quotidien</button>
                        <button class="period-btn px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 rounded-lg" data-period="weekly">Hebdomadaire</button>
                        <button class="period-btn px-4 py-2 bg-gray-700 text-gray-300 hover:bg-gray-600 rounded-lg" data-period="monthly">Mensuel</button>
                    </div>
                </div>
                
                <div class="relative h-[350px] mb-6">
                    <canvas id="timeActivityChart"></canvas>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Total</p>
                        <p class="text-xl text-white font-semibold" id="period-total">0h</p>
                    </div>
                    <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Moyenne</p>
                        <p class="text-xl text-white font-semibold" id="period-average">0h/heure</p>
                    </div>
                    <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">Temps le plus productif</p>
                        <p class="text-xl text-white font-semibold" id="most-productive-time">N/A</p>
                    </div>
                </div>
            </div>
            </div>
            

        </div>

        



    </main>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Graphique d'activité temporelle
        const createTimeActivityChart = () => {
            const ctx = document.getElementById('timeActivityChart');
            if (!ctx) return;
            
            // Récupérer les données
            const chartData = {
                daily: {
                    labels: @json($chartData['daily']['labels'] ?? []),
                    data: @json($chartData['daily']['data'] ?? []),
                    title: @json($chartData['daily']['title'] ?? 'Activité quotidienne'),
                    mostProductiveTime: @json($chartData['daily']['mostProductiveHour'] ?? 'N/A'),
                    totalHours: @json($chartData['daily']['totalHours'] ?? 0)
                },
                weekly: {
                    labels: @json($chartData['weekly']['labels'] ?? []),
                    data: @json($chartData['weekly']['data'] ?? []),
                    title: @json($chartData['weekly']['title'] ?? 'Activité hebdomadaire'),
                    mostProductiveTime: @json($chartData['weekly']['mostProductiveDay'] ?? 'N/A'),
                    totalHours: @json($chartData['weekly']['totalHours'] ?? 0)
                },
                monthly: {
                    labels: @json($chartData['monthly']['labels'] ?? []),
                    data: @json($chartData['monthly']['data'] ?? []),
                    title: @json($chartData['monthly']['title'] ?? 'Activité mensuelle'),
                    mostProductiveTime: 'N/A',
                    totalHours: @json($chartData['monthly']['totalHours'] ?? 0)
                }
            };
            
            let currentPeriod = 'daily';
            let timeActivityChart;
            
            const updateChart = (period) => {
                const data = chartData[period];
                currentPeriod = period;
                
                // Mettre à jour les statistiques dans l'interface
                document.getElementById('period-total').textContent = `${data.totalHours.toFixed(1)}h`;
                document.getElementById('most-productive-time').textContent = data.mostProductiveTime;
                
                // Calculer la moyenne (éviter division par zéro)
                const nonZeroValues = data.data.filter(val => val > 0);
                const avg = nonZeroValues.length > 0 
                    ? data.totalHours / nonZeroValues.length 
                    : 0;
                
                let avgText;
                if (period === 'daily') avgText = `${avg.toFixed(1)}h/heure`;
                else if (period === 'weekly') avgText = `${avg.toFixed(1)}h/jour`;
                else avgText = `${avg.toFixed(1)}h/semaine`;
                
                document.getElementById('period-average').textContent = avgText;
                
                // Détruire le graphique existant si nécessaire
                if (timeActivityChart) {
                    timeActivityChart.destroy();
                }
                
                // Créer le nouveau graphique
                timeActivityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Heures de code',
                            data: data.data,
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            borderRadius: 4,
                            hoverBackgroundColor: '#6366f1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: data.title,
                                color: '#fff',
                                font: {
                                    size: 16
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        return value.toFixed(2) + ' heures';
                                    }
                                },
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(99, 102, 241, 0.6)',
                                borderWidth: 1,
                                padding: 12
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        return value + 'h';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
            };
            
            // Initialiser le graphique avec les données quotidiennes
            updateChart('daily');
            
            // Gérer les clics sur les boutons de période
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const period = this.getAttribute('data-period');
                    if (period === currentPeriod) return;
                    
                    // Mettre à jour l'état des boutons
                    document.querySelectorAll('.period-btn').forEach(b => {
                        b.classList.remove('bg-indigo-600', 'text-white');
                        b.classList.add('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    });
                    this.classList.remove('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    this.classList.add('bg-indigo-600', 'text-white');
                    
                    // Mettre à jour le graphique
                    updateChart(period);
                });
            });
        };

        // Graphique des langages (donut)
        const createLanguagesChart = () => {
            const ctx = document.getElementById('languagesChart');
            if (!ctx) {
                console.error("Canvas 'languagesChart' introuvable");
                return;
            }
            
            const languages = @json($project->languages->map(function($lang) {
                return [
                    'name' => $lang->name,
                    'time' => $lang->time_ms,
                ];
            }));
            
            if (!languages || languages.length === 0) {
                console.log("Aucun langage à afficher dans le graphique");
                return;
            }
            
            const labels = languages.map(lang => lang.name);
            const data = languages.map(lang => lang.time);
            
            // Couleurs pour chaque langage
            const colors = languages.map(lang => {
                const name = lang.name.toLowerCase();
                if (name.includes('javascript')) return '#fbbf2490';
                if (name.includes('css')) return '#3b82f690';
                if (name.includes('html')) return '#f9731690';
                if (name.includes('php')) return '#a855f790';
                if (name.includes('python')) return '#22c55e90';
                return '#6366f190';
            });
            
            // Couleurs de bordure
            const borderColors = languages.map(lang => {
                const name = lang.name.toLowerCase();
                if (name.includes('javascript')) return '#fbbf24';
                if (name.includes('css')) return '#3b82f6';
                if (name.includes('html')) return '#f97316';
                if (name.includes('php')) return '#a855f7';
                if (name.includes('python')) return '#22c55e';
                return '#6366f1';
            });
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderColor: borderColors,
                        borderWidth: 2,
                        hoverOffset: 10
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
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.chart.getDatasetMeta(0).total;
                                    const percentage = Math.round((value / total) * 100);
                                    const formattedTime = formatTime(value);
                                    return `${label}: ${formattedTime} (${percentage}%)`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Répartition du temps par langage',
                            color: '#ffffff',
                            font: {
                                size: 16,
                                weight: 'normal'
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        };
        
        // Graphique de productivité (ligne)
        const createProductivityChart = () => {
            const ctx = document.getElementById('productivityChart');
            if (!ctx) {
                console.error("Canvas 'productivityChart' introuvable");
                return;
            }
            
            // Utiliser les données réelles
            const days = @json($productivityStats['last7Days'] ?? []);
            const productivityData = @json($productivityStats['productivity7Days'] ?? []);
            
            if (!days || !productivityData || days.length === 0) {
                console.log("Aucune donnée de productivité disponible");
                
                // Utiliser des données fictives
                const defaultDays = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                const defaultData = [65, 72, 68, 85, 60, 70, 78];
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: defaultDays,
                        datasets: [{
                            label: 'Productivité',
                            data: defaultData,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#22c55e',
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
                            title: {
                                display: true,
                                text: 'Tendance de productivité',
                                color: '#ffffff',
                                font: {
                                    size: 16
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 0,
                                max: 100,
                                ticks: {
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#94a3b8'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                return;
            }
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Productivité',
                        data: productivityData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#22c55e',
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
                        title: {
                            display: true,
                            text: 'Tendance de productivité',
                            color: '#ffffff',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 0,
                            max: 100,
                            ticks: {
                                color: '#94a3b8',
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#94a3b8'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        };
        
        // Graphique d'activité par heure (heatmap)
        const createActivityHeatmap = () => {
            const ctx = document.getElementById('activityHeatmapChart');
            if (!ctx) {
                console.error("Canvas 'activityHeatmapChart' introuvable");
                return;
            }
            
            // Version simplifiée avec un graphique à barres
            const heatmapData = @json($heatmapData ?? []);
            
            if (!heatmapData || !heatmapData.days) {
                // Données par défaut
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                        datasets: [{
                            label: 'Heures d\'activité',
                            data: [4.2, 3.8, 5.1, 2.9, 3.5, 1.2, 0.8],
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.7)',
                                'rgba(99, 102, 241, 0.6)',
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(99, 102, 241, 0.5)',
                                'rgba(99, 102, 241, 0.6)',
                                'rgba(99, 102, 241, 0.3)',
                                'rgba(99, 102, 241, 0.2)'
                            ],
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            borderRadius: 4,
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
                                text: 'Activité par jour de la semaine',
                                color: '#fff'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
                return;
            }
            
            // Création d'un dataset plus simple pour contourner le problème de Matrix
            // Calculons l'activité moyenne par jour
            let dayAverages = [];
            for (let i = 0; i < heatmapData.data.length; i++) {
                const daySum = heatmapData.data[i].reduce((sum, val) => sum + val, 0);
                const dayAvg = daySum / heatmapData.data[i].length;
                dayAverages.push(dayAvg);
            }
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: heatmapData.days,
                    datasets: [{
                        label: 'Activité moyenne',
                        data: dayAverages,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Répartition d\'activité par jour',
                            color: '#fff',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Intensité: ${context.raw.toFixed(1)}%`;
                                }
                            },
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        };
        
        // Fonction utilitaire pour formater le temps
        function formatTime(ms) {
            if (ms < 1000) return '0s';
            
            const seconds = Math.floor((ms / 1000) % 60);
            const minutes = Math.floor((ms / (1000 * 60)) % 60);
            const hours = Math.floor((ms / (1000 * 60 * 60)));
            
            if (hours > 0) {
                return `${hours}h ${minutes}m`;
            } else {
                return `${minutes}m ${seconds}s`;
            }
        }
        
        // Initialiser tous les graphiques avec gestion d'erreur
        try {
            createLanguagesChart();
        } catch(e) {
            console.error("Erreur lors de la création du graphique des langages:", e);
        }
        
        try {
            createProductivityChart();
        } catch(e) {
            console.error("Erreur lors de la création du graphique de productivité:", e);
        }
        
        try {
            createActivityHeatmap();
        } catch(e) {
            console.error("Erreur lors de la création du graphique d'activité:", e);
        }
        
        try {
            createTimeActivityChart();
        } catch(e) {
            console.error("Erreur lors de la création du graphique d'activité temporelle:", e);
        }
    });
</script>