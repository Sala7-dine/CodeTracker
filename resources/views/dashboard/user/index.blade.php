<x-app-layout>
    
    @include("layouts.user_sidebare");

    <!-- Main Content -->
    <main class="ml-20 p-8">
    
        @include("layouts.user_header");

        @include("layouts.stats_cards");

        <!-- Section principale regroupée - Projet actuel, Technologies et Session -->
        @if(isset($globalStats['currentProject']))
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8 overflow-hidden">
            <!-- En-tête avec informations du projet -->
            <div class="relative mb-8">
                <div class="flex items-center justify-between">
                    <div>

                        <div class="flex items-end">
                            <h2 class="text-4xl font-semibold text-white flex items-end">
                                <i class="ph-folder-open text-indigo-400 mr-3"></i>
                                {{ $globalStats['currentProject']['name'] }} 
                            </h2>
                            <p class="text-gray-400 text-md px-2 font-medium text-xl">{{ $globalStats['currentProject']['formattedTime'] }}</p>
                        </div>
                        <p class="text-gray-400 mt-1">Session de développement active</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1.5 rounded-full text-xs bg-green-500/20 text-green-400 flex items-center">
                            <i class="ph-pulse text-xs mr-1"></i>
                            Actif maintenant
                        </span>
                        <a href="#" class="px-4 py-2 bg-indigo-600/90 hover:bg-indigo-600 rounded-lg text-white text-sm transition-all">
                            Voir le détail
                        </a>
                    </div>
                </div>

                <!-- Barre de progression stylisée -->
                <div class="absolute -bottom-4 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-70 rounded-full"></div>
            </div>

            <!-- Colonne centrale - Fichier actuel -->
            <div class="mb-8">
                @if(isset($globalStats['currentFile']))
                <div class="bg-gray-800/30 h-full p-5 rounded-xl border border-gray-700 backdrop-blur-md relative overflow-hidden">
                    <!-- Gradient décoratif -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full filter blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/5 rounded-full filter blur-3xl -ml-32 -mb-32 pointer-events-none"></div>
                    
                    <h3 class="text-lg font-medium text-white mb-4 pb-3 border-b border-gray-700 flex items-center relative">
                        <i class="ph-code text-indigo-400 mr-2"></i>
                        Session active
                    </h3>
                    
                    <!-- Contenu du fichier actuel -->
                    <div class="space-y-5 relative">
                        <!-- En-tête du fichier -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-lg bg-indigo-600/20 flex items-center justify-center flex-shrink-0">
                                @php
                                    $iconClass = 'ph-file-code';
                                    $textColor = 'text-indigo-400';
                                    
                                    if (isset($globalStats['currentFile']['language'])) {
                                        $lang = strtolower($globalStats['currentFile']['language']);
                                        if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                                            $iconClass = 'ph-file-js';
                                            $textColor = 'text-yellow-400';
                                        } elseif (strpos($lang, 'css') !== false) {
                                            $iconClass = 'ph-file-css';
                                            $textColor = 'text-blue-400';
                                        } elseif (strpos($lang, 'html') !== false) {
                                            $iconClass = 'ph-file-html';
                                            $textColor = 'text-orange-400';
                                        } elseif (strpos($lang, 'php') !== false) {
                                            $iconClass = 'ph-file-php';
                                            $textColor = 'text-purple-400';
                                        } elseif (strpos($lang, 'python') !== false) {
                                            $iconClass = 'ph-file-py';
                                            $textColor = 'text-green-400';
                                        }
                                    }
                                @endphp
                                <i class="{{ $iconClass }} {{ $textColor }} text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-white">{{ basename($globalStats['currentFile']['name']) }}</h3>
                                <p class="text-sm text-gray-400 truncate">{{ $globalStats['currentFile']['path'] }}</p>
                                <div class="flex items-center mt-2 flex-wrap gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-600/40 {{ $textColor }}">
                                        {{ ucfirst($globalStats['currentFile']['language']) }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-600/40 text-gray-300">
                                        {{ $globalStats['currentFile']['lines'] }} lignes
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-600/40 text-indigo-300">
                                        {{ $globalStats['currentFile']['formattedTime'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activité -->
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2 text-gray-400 text-sm">
                                    <i class="ph-activity text-green-400"></i>
                                    <span>Dernière activité il y a {{ isset($globalStats['currentFile']['lastActive']) ? $globalStats['currentFile']['lastActive'] : 'quelques instants' }}</span>
                                </div>
                                <div class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-500/20 text-green-400">
                                    En cours d'édition
                                </div>
                            </div>
                            
                            <!-- Timeline stylisée -->
                            <div class="relative h-12 mb-3">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="w-full h-full flex items-end">
                                        <!-- Graphique d'activité simulé -->
                                        @for($i = 0; $i < 50; $i++)
                                            @php $height = rand(15, 100); @endphp
                                            <div class="flex-1 h-{{ $height }}% bg-indigo-500/{{ rand(20, 70) }} rounded-sm mx-px"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            
                           
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-800/30 h-full p-5 rounded-xl border border-gray-700 backdrop-blur-md flex flex-col items-center justify-center text-center">
                    <div class="rounded-full bg-gray-700 p-4 mb-4">
                        <i class="ph-code-block text-indigo-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun fichier actif</h3>
                    <p class="text-gray-400 max-w-md">Ouvrez un fichier dans VS Code avec l'extension CodeTracker pour commencer à suivre votre session de codage.</p>
                    <button class="mt-5 px-4 py-2 bg-indigo-600/80 hover:bg-indigo-600 rounded-lg text-white text-sm transition-all flex items-center">
                        <i class="ph-play mr-2"></i>
                        Démarrer une session
                    </button>
                </div>
                @endif
            </div>
            <!-- Blocs de statistiques et contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Colonne de gauche - Stats du projet -->
                <div class="lg:col-span-2">
                    <div class="space-y-5">
                        <!-- Stats du projet -->
                        <div class="bg-gray-800/30 p-5 rounded-xl border border-gray-700 backdrop-blur-md">
                            <h3 class="text-lg font-medium text-white mb-4 pb-3 border-b border-gray-700 flex items-center">
                                <i class="ph-chart-bar text-indigo-400 mr-2"></i>
                                Statistiques
                            </h3>
                            
                            <!-- Remplacement des barres de progression dans la section Statistiques -->
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-400">Fichiers</span>
                                        <span class="text-white font-medium">{{ $globalStats['currentProject']['totalFiles'] }}</span>
                                    </div>
                                    <!-- Remplacé par des "dots" interactifs -->
                                    <div class="w-full flex justify-between space-x-1">
                                        @for ($i = 0; $i < 20; $i++)
                                            @php 
                                                $active = $i < min(20, $globalStats['currentProject']['totalFiles'] / 5);
                                                $delay = $i * 0.05;
                                            @endphp
                                            <div class="flex-1 flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full {{ $active ? 'bg-indigo-500 animate-pulse' : 'bg-gray-600/50' }}" 
                                                     style="animation-delay: {{ $delay }}s"></div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-400">Lignes de code</span>
                                        <span class="text-white font-medium">{{ $globalStats['currentProject']['totalLines'] }}</span>
                                    </div>
                                    <!-- Remplacé par un "sparkline" stylisé -->
                                    <div class="w-full h-7 flex items-end justify-between space-x-0.5">
                                        @for ($i = 0; $i < 20; $i++)
                                            @php 
                                                $progress = min(100, $globalStats['currentProject']['totalLines'] / 100);
                                                $height = rand(10, 100);
                                                if ($i < $progress / 5) {
                                                    $height = max(50, $height);
                                                }
                                            @endphp
                                            <div class="flex-1 bg-purple-{{ ($i < $progress / 5) ? '500' : '600/20' }} rounded-sm" 
                                                 style="height: {{ $height }}%"></div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-400">Temps total</span>
                                        <span class="text-white font-medium">{{ $globalStats['currentProject']['formattedTime'] }}</span>
                                    </div>
                                    <!-- Remplacé par un "circular pattern" -->
                                    <div class="w-full flex items-center justify-between py-1.5">
                                        @for ($i = 0; $i < 10; $i++)
                                            @php 
                                                $size = ($i % 2 == 0) ? 'w-2.5 h-2.5' : 'w-2 h-2';
                                                $active = $i < 8.5;
                                            @endphp
                                            <div class="relative flex items-center justify-center {{ $size }}">
                                                <div class="absolute inset-0 {{ $active ? 'bg-pink-500' : 'bg-gray-600/50' }} rounded-full"></div>
                                                @if($active)
                                                    <div class="absolute inset-0 bg-pink-500 rounded-full animate-ping opacity-30" 
                                                         style="animation-duration: {{ 1 + $i * 0.2 }}s"></div>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Environnement de développement -->
                        <div class="bg-gray-800/30 p-5 rounded-xl border border-gray-700 backdrop-blur-md">
                            <h3 class="text-lg font-medium text-white mb-4 pb-3 border-b border-gray-700 flex items-center">
                                <i class="ph-desktop text-indigo-400 mr-2"></i>
                                Environnement
                            </h3>
                            
                            <div class="space-y-3.5">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center mr-3">
                                        <i class="ph-code-block text-indigo-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Éditeur</p>
                                        <p class="text-white">{{ $globalStats['environment']['editor'] ?? 'VS Code' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center mr-3">
                                        @php
                                            $osIcon = 'ph-windows-logo';
                                            if (isset($globalStats['environment']['os']) && strpos(strtolower($globalStats['environment']['os']), 'mac') !== false) {
                                                $osIcon = 'ph-apple-logo';
                                            } elseif (isset($globalStats['environment']['os']) && strpos(strtolower($globalStats['environment']['os']), 'linux') !== false) {
                                                $osIcon = 'ph-linux-logo';
                                            }
                                        @endphp
                                        <i class="{{ $osIcon }} text-purple-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Système</p>
                                        <p class="text-white">{{ $globalStats['environment']['os'] ?? 'Windows 11' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-pink-600/20 flex items-center justify-center mr-3">
                                        <i class="ph-plug text-pink-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Extensions</p>
                                        <p class="text-white">{{ isset($globalStats['environment']['extensions']) ? count($globalStats['environment']['extensions']) : 3 }} actives</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne de droite - Technologies utilisées -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-800/30 h-full p-5 rounded-xl border border-gray-700 backdrop-blur-md">
                        <h3 class="text-lg font-medium text-white mb-4 pb-3 border-b border-gray-700 flex items-center">
                            <i class="ph-brackets-curly text-indigo-400 mr-2"></i>
                            Technologies
                        </h3>
                        
                        <!-- Remplacement des barres de progression dans la section Technologies -->
                        <div class="space-y-3">
                            @if(isset($globalStats['languages']) && count($globalStats['languages']) > 0)
                                @foreach($globalStats['languages'] as $langName => $lang)
                                    @php
                                        $langColor = 'indigo';
                                        $iconClass = 'ph-file-code';
                                        
                                        if (strtolower($langName) == 'javascript') {
                                            $langColor = 'yellow';
                                            $iconClass = 'ph-file-js';
                                        } elseif (strtolower($langName) == 'css') {
                                            $langColor = 'blue';
                                            $iconClass = 'ph-file-css';
                                        } elseif (strtolower($langName) == 'html') {
                                            $langColor = 'orange';
                                            $iconClass = 'ph-file-html';
                                        } elseif (strtolower($langName) == 'php') {
                                            $langColor = 'purple';
                                            $iconClass = 'ph-file-php';
                                        } elseif (strtolower($langName) == 'python') {
                                            $langColor = 'green';
                                            $iconClass = 'ph-file-py';
                                        }
                                        
                                        // Calculer un pourcentage pour la visualisation
                                        $totalTime = 0;
                                        foreach($globalStats['languages'] as $l) {
                                            $totalTime += $l['time_spent'];
                                        }
                                        $percentage = $totalTime > 0 ? ($lang['time_spent'] / $totalTime) * 100 : 0;
                                        $segments = ceil($percentage / 10); // 10 segments max
                                    @endphp
                                    
                                    <div class="bg-gray-900 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <i class="{{ $iconClass }} text-{{ $langColor }}-400 mr-2"></i>
                                                <span class="text-white font-medium">{{ ucfirst($langName) }}</span>
                                            </div>
                                            <span class="text-{{ $langColor }}-400 text-sm">{{ $lang['formattedTime'] }}</span>
                                        </div>
                                        
                                        <!-- Nouvelle visualisation avec un motif en zigzag -->
                                        <div class="flex items-center justify-between h-5 gap-1">
                                            @for ($i = 0; $i < 10; $i++)
                                                <div class="flex-1 flex {{ $i % 2 == 0 ? 'items-start' : 'items-end' }} justify-center">
                                                    <div class="w-full h-[3px] {{ $i < $segments ? 'bg-'.$langColor.'-500' : 'bg-gray-600/40' }} rounded-full"></div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-gray-900 rounded-lg p-5 text-center">
                                    <i class="ph-code text-indigo-400 text-3xl mb-2"></i>
                                    <h4 class="text-white mb-1">Aucune donnée</h4>
                                    <p class="text-gray-400 text-sm">Commencez à coder pour voir les statistiques des technologies utilisées.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Message si aucun projet actif -->
        <div class="bg-gray-900 backdrop-blur-xl p-8 rounded-2xl border border-gray-700 mb-8 text-center">
            <div class="max-w-md mx-auto">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-600/20 mb-4">
                    <i class="ph-folder-open text-indigo-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-white mb-2">Aucun projet actif</h2>
                <p class="text-gray-400 mb-6">Ouvrez un projet dans votre éditeur et commencez à coder pour voir apparaître des statistiques détaillées ici.</p>
                <button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-medium transition-all">
                    Démarrer un nouveau projet
                </button>
            </div>
        </div>
        @endif

        <!-- Main Chart -->
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <span class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center mr-3">
                        <i class="ph-chart-line text-indigo-400"></i>
                    </span>
                    @if(isset($globalStats['currentProject']))
                        Activité de codage - {{ $globalStats['currentProject']['name'] }}
                    @else
                        Activité de codage
                    @endif
                </h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 text-sm hover:bg-gray-600 chart-view-btn" data-view="daily">Jour</button>
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm chart-view-btn" data-view="weekly">Semaine</button>
                    <button class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 text-sm hover:bg-gray-600 chart-view-btn" data-view="monthly">Mois</button>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <!-- Line Chart -->
                <div class="relative h-[400px]">
                    @if(isset($globalStats['currentProject']))
                        <canvas id="activityChart" class="w-full h-full"></canvas>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-gray-800/50 backdrop-blur-sm rounded-xl">
                            <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
                                <i class="ph-chart-line text-indigo-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-white mb-2">Aucun projet actif</h3>
                            <p class="text-gray-400 max-w-md">Ouvrez un projet dans votre éditeur pour voir les statistiques d'activité.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Polar Area Chart - Distribution par langage de programmation -->
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8 relative overflow-hidden">
            <!-- Éléments décoratifs en arrière-plan -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/5 rounded-full filter blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/5 rounded-full filter blur-3xl -ml-32 -mb-32 pointer-events-none"></div>
            
            <!-- En-tête avec style amélioré -->
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <span class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-600/40 to-purple-600/40 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/10">
                        <i class="ph-code text-indigo-400"></i>
                    </span>
                    Distribution du temps par langage
                </h2>
            </div>
            
            <!-- Conteneur du graphique avec style amélioré -->
            <div class="relative h-[400px] lg:h-[500px] overflow-hidden">
                <!-- Canvas pour Chart.js -->
                <canvas id="polarAreaChart" class="w-full h-full p-4"></canvas>
                
                <!-- Message si pas de données -->
                <div id="noChartData" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center p-8 z-20 bg-gray-800/80 backdrop-blur-md">
                    <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
                        <i class="ph-code text-indigo-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucune donnée disponible</h3>
                    <p class="text-gray-400 max-w-md">Commencez à coder avec l'extension pour générer des statistiques de temps par langage.</p>
                </div>
            </div>
            
            <!-- Info supplémentaire -->
            <div id="languageDetails" class="mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Les informations détaillées seront ajoutées dynamiquement ici -->
            </div>
        </div>

    
        <!-- Section des projets récents -->
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 ">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Projets récents</h2>
                <a href="#" onclick="openProjectModal()" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                    Voir tous les projets
                    <i class="ph-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($projects->take(3) as $project)
                <div class="bg-gray-800/30 p-5 rounded-xl border border-gray-600 hover:border-indigo-500/50 transition-all hover:shadow-lg hover:shadow-indigo-500/10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-white font-medium flex items-center">
                            <i class="ph-folder text-indigo-400 mr-2"></i>
                            {{ $project->name }}
                        </h3>
                        <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                            {{ isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id ? 'Actif' : 'Récent' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-400">{{ \App\Models\Language::formatTime($project->getTotalTime()) }}</span>
                        <span class="text-gray-400">{{ $project->getTotalFiles() }} fichiers</span>
                    </div>
                    
                    <div class="flex gap-1 my-3">
                        @for ($i = 0; $i < 10; $i++)
                            @php
                                $active = $i < 7.5;
                                if (isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id) {
                                    $active = $i < 9;
                                }
                            @endphp
                            <div class="flex-1 h-1 rounded-full {{ $active ? 'bg-indigo-600' : 'bg-gray-600/30' }}"></div>
                        @endfor
                    </div>
                    
                    <div class="mt-4">
                        @if($project->languages && $project->languages->count() > 0)
                            <!-- Section avec les langages de projets -->
                            @foreach($project->languages->take(3) as $language)
                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="flex items-center">
                                    @php
                                        $iconClass = 'ph-file-code';
                                        $textColor = 'text-indigo-400';
                                        
                                        if (strtolower($language->name) === 'javascript') {
                                            $iconClass = 'ph-file-js';
                                            $textColor = 'text-yellow-400';
                                        } elseif (strtolower($language->name) === 'css') {
                                            $iconClass = 'ph-file-css';
                                            $textColor = 'text-blue-400';
                                        } elseif (strtolower($language->name) === 'html') {
                                            $iconClass = 'ph-file-html';
                                            $textColor = 'text-orange-400';
                                        } elseif (strtolower($language->name) === 'php') {
                                            $iconClass = 'ph-file-php';
                                            $textColor = 'text-purple-400';
                                        } elseif (strtolower($language->name) === 'python') {
                                            $iconClass = 'ph-file-py';
                                            $textColor = 'text-green-400';
                                        }
                                    @endphp
                                    <i class="{{ $iconClass }} {{ $textColor }} mr-2"></i>
                                    <span class="text-gray-300">{{ $language->name }}</span>
                                </div>
                                <span class="text-gray-400">{{ \App\Models\Language::formatTime($language->time_ms ?? 0) }}</span>
                            </div>
                            @endforeach
                        @else
                            <p class="text-gray-400 text-sm">Aucune donnée de langage disponible</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-gray-700/50 p-5 rounded-xl border border-gray-600 text-center">
                    <i class="ph-folders text-indigo-400 text-4xl mb-3"></i>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun projet récent</h3>
                    <p class="text-gray-400">Commencez à coder avec l'extension pour voir apparaître vos projets ici.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupération des données depuis le contrôleur
            const languageLabels = {!! json_encode(array_keys($globalStats['languages'] ?? [])) !!};
            const languageData = {!! json_encode(array_column($globalStats['languages'] ?? [], 'time_spent')) !!};
            const languageColors = [];
            const languageDetails = {};
            
            // Définir les couleurs et collecter les détails par langage
            @foreach($globalStats['languages'] ?? [] as $langName => $lang)
                @php
                    $langColor = '#6366f1'; // Indigo par défaut
                    
                    if (strtolower($langName) == 'javascript' || strtolower($langName) == 'js') {
                        $langColor = '#fbbf24'; // yellow-500
                    } elseif (strtolower($langName) == 'php') {
                        $langColor = '#a855f7'; // purple-500
                    } elseif (strtolower($langName) == 'css') {
                        $langColor = '#3b82f6'; // blue-500
                    } elseif (strtolower($langName) == 'html') {
                        $langColor = '#f97316'; // orange-500
                    } elseif (strtolower($langName) == 'python' || strtolower($langName) == 'py') {
                        $langColor = '#22c55e'; // green-500
                    } elseif (strtolower($langName) == 'typescript' || strtolower($langName) == 'ts') {
                        $langColor = '#ec4899'; // pink-500
                    } elseif (strtolower($langName) == 'json') {
                        $langColor = '#0ea5e9'; // sky-500
                    }
                @endphp
                
                languageColors.push('{{ $langColor }}');
                
                languageDetails['{{ $langName }}'] = {
                    name: '{{ $langName }}',
                    color: '{{ $langColor }}',
                    files: {{ $lang['files'] ?? 0 }},
                    lines: {{ $lang['lines'] ?? 0 }},
                    formattedTime: '{{ $lang['formattedTime'] ?? "0m" }}'
                };
            @endforeach
            
            // Vérifier si nous avons des données
            if (languageLabels.length === 0 || languageData.length === 0) {
                document.getElementById('noChartData').classList.remove('hidden');
                return;
            }
            
            // Créer le Polar Area Chart
            const ctx = document.getElementById('polarAreaChart').getContext('2d');
            const polarAreaChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: languageLabels,
                    datasets: [{
                        data: languageData,
                        backgroundColor: languageColors.length > 0 ? languageColors : [
                            '#fbbf24',  // yellow-500  - JavaScript
                            '#a855f7',  // purple-500 - PHP
                            '#f97316',  // orange-500 - HTML
                            '#3b82f6',  // blue-500   - CSS
                            '#22c55e',  // green-500  - Python
                            '#ec4899',  // pink-500   - TypeScript
                            '#0ea5e9',  // sky-500    - JSON
                            '#6366f1',  // indigo-500 - Autres
                        ],
                        borderWidth: 1,
                        borderColor: '#1f2937',
                        hoverBorderColor: 'white',
                        hoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#fff',
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                },
                                // Personnaliser les labels pour inclure le temps formaté
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const formattedTime = languageDetails[label]?.formattedTime || '';
                                            
                                            return {
                                                text: `${label} (${formattedTime})`,
                                                fillStyle: chart.data.datasets[0].backgroundColor[i],
                                                strokeStyle: chart.data.datasets[0].borderColor,
                                                lineWidth: chart.data.datasets[0].borderWidth,
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
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
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    const label = context.label;
                                    const value = languageDetails[label]?.formattedTime || '';
                                    const lines = languageDetails[label]?.lines || 0;
                                    const files = languageDetails[label]?.files || 0;
                                    return [
                                        `Temps total: ${value}`,
                                        `Fichiers: ${files}`,
                                        `Lignes: ${lines}`
                                    ];
                                }
                            },
                            titleFont: {
                                weight: 'bold'
                            },
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            borderColor: 'rgba(99, 102, 241, 0.6)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                display: false
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            angleLines: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            pointLabels: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
            
            // Générer les détails des langages
            const languageDetailsContainer = document.getElementById('languageDetails');
            let detailsHTML = '';
            
            // Trier les langages par temps passé (du plus grand au plus petit)
            const sortedLanguages = Object.values(languageDetails).sort((a, b) => {
                const timeA = parseDuration(a.formattedTime);
                const timeB = parseDuration(b.formattedTime);
                return timeB - timeA;
            });
            
            // Top 4 langages max
            const topLanguages = sortedLanguages.slice(0, 4);
            
            // Créer les cartes de détails pour les langages
            topLanguages.forEach(lang => {
                detailsHTML += `
                <div class="bg-gray-800/30 p-4 rounded-xl border border-gray-700">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 rounded-lg" style="background-color: ${lang.color}40">
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="ph-code text-lg" style="color: ${lang.color}"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-white font-medium">${lang.name}</h3>
                            <p class="text-xs text-gray-400">${lang.formattedTime}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Fichiers</span>
                            <span class="text-white">${lang.files}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Lignes de code</span>
                            <span class="text-white">${lang.lines}</span>
                        </div>
                        <div class="w-full h-1.5 mt-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: 75%; background-color: ${lang.color}"></div>
                        </div>
                    </div>
                </div>
                `;
            });
            
            // Si nous n'avons pas de données détaillées
            if (topLanguages.length === 0) {
                detailsHTML = `
                <div class="col-span-full bg-gray-800/30 p-5 rounded-xl border border-gray-700 text-center">
                    <i class="ph-code text-indigo-400 text-3xl mb-2"></i>
                    <h4 class="text-white mb-1">Aucun détail disponible</h4>
                    <p class="text-gray-400 text-sm">Les statistiques détaillées apparaîtront ici une fois que vous aurez commencé à coder.</p>
                </div>
                `;
            }
            
            languageDetailsContainer.innerHTML = detailsHTML;
            
            // Fonction pour analyser les durées formatées (comme "2h 30m" ou "45m")
            function parseDuration(formattedTime) {
                if (!formattedTime) return 0;
                
                let totalMinutes = 0;
                
                // Extraire les heures
                const hourMatch = formattedTime.match(/(\d+)h/);
                if (hourMatch) {
                    totalMinutes += parseInt(hourMatch[1]) * 60;
                }
                
                // Extraire les minutes
                const minuteMatch = formattedTime.match(/(\d+)m/);
                if (minuteMatch) {
                    totalMinutes += parseInt(minuteMatch[1]);
                }
                
                return totalMinutes;
            }
            
            // Ajouter le clic sur les segments du graphique pour mettre en évidence
            document.getElementById('polarAreaChart').onclick = function(evt) {
                const activePoints = polarAreaChart.getElementsAtEventForMode(
                    evt, 
                    'nearest', 
                    { intersect: true }, 
                    true
                );
                
                if (activePoints.length) {
                    const firstPoint = activePoints[0];
                    const label = polarAreaChart.data.labels[firstPoint.index];
                    
                    // Vous pourriez ajouter ici un comportement pour mettre en évidence
                    // la section correspondante dans les détails
                    console.log("Langage sélectionné:", label);
                }
            };
        });
    </script>

    <!-- Ajout du script Chart.js à la fin du body avec les données du contrôleur -->
    <script>
        // Configuration du thème global de Chart.js
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#334155';

        // Récupération des données depuis le contrôleur
        const chartData = {
            daily: {
                labels: {!! json_encode($globalStats['chartData']['daily']['labels'] ?? []) !!},
                data: {!! json_encode($globalStats['chartData']['daily']['data'] ?? []) !!},
                colors: {!! json_encode($globalStats['chartData']['daily']['colors'] ?? []) !!},
                title: "{{ $globalStats['chartData']['daily']['title'] ?? 'Activité quotidienne' }}"
            },
            weekly: {
                labels: {!! json_encode($globalStats['chartData']['weekly']['labels'] ?? []) !!},
                data: {!! json_encode($globalStats['chartData']['weekly']['data'] ?? []) !!},
                colors: {!! json_encode($globalStats['chartData']['weekly']['colors'] ?? []) !!},
                title: "{{ $globalStats['chartData']['weekly']['title'] ?? 'Activité hebdomadaire' }}"
            },
            monthly: {
                labels: {!! json_encode($globalStats['chartData']['monthly']['labels'] ?? []) !!},
                data: {!! json_encode($globalStats['chartData']['monthly']['data'] ?? []) !!},
                colors: {!! json_encode($globalStats['chartData']['monthly']['colors'] ?? []) !!},
                title: "{{ $globalStats['chartData']['monthly']['title'] ?? 'Activité mensuelle' }}"
            }
        };

        // Graphique d'activité (ligne)
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        let currentView = 'weekly'; // Vue par défaut
        let activityChart;

        // Fonction pour initialiser le graphique
        function initActivityChart() {
            const currentData = chartData[currentView];
            
            // Vérifier si nous avons des données
            if (!currentData.labels || !currentData.data || currentData.labels.length === 0) {
                // Utiliser des données par défaut si aucune donnée n'est disponible
                currentData.labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                currentData.data = [0, 0, 0, 0, 0, 0, 0];
                currentData.colors = Array(7).fill('rgba(99, 102, 241, 0.2)');
            }

            activityChart = new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: currentData.labels,
                    datasets: [{
                        label: 'Heures de code',
                        data: currentData.data,
                        borderColor: '#818cf8',
                        backgroundColor: '#818cf820',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: currentData.colors || Array(currentData.data.length).fill('#818cf8'),
                        pointBorderColor: '#ffffff',
                        pointRadius: 5,
                        pointHoverRadius: 7
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
                            text: currentData.title || 'Temps de codage',
                            color: '#fff',
                            font: {
                                size: 16,
                                weight: 'normal'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return value.toFixed(1) + ' heures';
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
        }

        // Initialiser le graphique au chargement
        document.addEventListener('DOMContentLoaded', function() {
            initActivityChart();

            // Gérer les clics sur les boutons de vue
            document.querySelectorAll('.chart-view-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Récupérer la vue sélectionnée
                    const view = this.dataset.view;
                    if (view === currentView) return;
                    
                    // Mettre à jour l'état des boutons
                    document.querySelectorAll('.chart-view-btn').forEach(btn => {
                        btn.classList.remove('bg-indigo-600', 'text-white');
                        btn.classList.add('bg-gray-700', 'text-gray-300');
                    });
                    this.classList.remove('bg-gray-700', 'text-gray-300');
                    this.classList.add('bg-indigo-600', 'text-white');
                    
                    // Mettre à jour la vue actuelle
                    currentView = view;
                    
                    // Détruire et recréer le graphique
                    if (activityChart) {
                        activityChart.destroy();
                    }
                    initActivityChart();
                });
            });
        });
    </script>

</x-app-layout>