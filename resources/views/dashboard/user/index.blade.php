<x-app-layout>
    
    @include("layouts.user_sidebare");

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
            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Temps total</h3>
                    <span class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                        <i class="ph-clock text-indigo-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $globalStats['formattedTime'] }}</p>
                <p class="text-sm text-green-400 mt-2">+2.5% vs semaine dernière</p>
            </div>

            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-folders text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $number_of_projects }}</p>
                <p class="text-sm text-purple-400 mt-2">2 nouveaux cette semaine</p>
            </div>

            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Lignes de code</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $globalStats['totalLines'] }}</p>
                <p class="text-sm text-blue-400 mt-2">+847 aujourd'hui</p>
            </div>

            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
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

        <!-- Section principale regroupée - Projet actuel, Technologies et Session -->
        @if(isset($globalStats['currentProject']))
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8 overflow-hidden">
            <!-- En-tête avec informations du projet -->
            <div class="relative mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-white flex items-center">
                            <i class="ph-folder-open text-indigo-400 mr-3"></i>
                            {{ $globalStats['currentProject']['name'] }}
                        </h2>
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

            <!-- Blocs de statistiques et contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Colonne de gauche - Stats du projet -->
                <div class="lg:col-span-1">
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
                
                <!-- Colonne centrale - Fichier actuel -->
                <div class="lg:col-span-2">
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
                
                <!-- Colonne de droite - Technologies utilisées -->
                <div class="lg:col-span-1">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Projets actifs</h2>
                <div class="space-y-4">
                    @forelse($projects->take(3) as $project)
                    <!-- Project Card -->
                    <div class="p-4 bg-gray-800/30 border border-gray-700 rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-white font-medium">{{ $project->name }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                                {{ isset($globalStats['currentProject']) && $globalStats['currentProject']['id'] == $project->id ? 'Actif' : 'Récent' }}
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
                            <span class="text-indigo-400">{{ $percentage }}%</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 bg-gray-800/30 border border-gray-700 rounded-xl text-center">
                        <p class="text-gray-400">Aucun projet actif</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">Fichiers récents</h2>
                <div class="space-y-4">
                    @if(isset($globalStats['currentFile']))
                    <!-- File Card -->
                    <div class="flex items-center justify-between p-4 bg-gray-800/30 border border-gray-700 rounded-xl">
                        <div class="flex items-center space-x-3">
                            @php
                                $iconClass = 'ph-file-code';
                                $iconColor = 'text-blue-400';
                                
                                $lang = strtolower($globalStats['currentFile']['language']);
                                if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                                    $iconClass = 'ph-file-js';
                                    $iconColor = 'text-yellow-400';
                                } elseif (strpos($lang, 'css') !== false) {
                                    $iconClass = 'ph-file-css';
                                    $iconColor = 'text-blue-400';
                                } elseif (strpos($lang, 'html') !== false) {
                                    $iconClass = 'ph-file-html';
                                    $iconColor = 'text-orange-400';
                                } elseif (strpos($lang, 'php') !== false) {
                                    $iconClass = 'ph-file-php';
                                    $iconColor = 'text-purple-400';
                                } elseif (strpos($lang, 'python') !== false) {
                                    $iconClass = 'ph-file-py';
                                    $iconColor = 'text-green-400';
                                }
                            @endphp
                            <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                            </span>
                            <div>
                                <h4 class="text-white">{{ basename($globalStats['currentFile']['name']) }}</h4>
                                <p class="text-sm text-gray-400">{{ dirname($globalStats['currentFile']['path']) }}</p>
                            </div>
                        </div>
                        <span class="text-gray-400">{{ $globalStats['currentFile']['formattedTime'] }}</span>
                    </div>
                    @else
                    <div class="p-4 bg-gray-800/30 border border-gray-700 rounded-xl text-center">
                        <p class="text-gray-400">Aucun fichier récent</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section des projets récents -->
        <div class="bg-gray-900 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 ">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Projets récents</h2>
                <a href="{{ route('user.dashboard.projects') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
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
                labels: {!! json_encode(array_keys($globalStats['languages'] ?? [])) !!},
                datasets: [{
                    data: {!! json_encode(array_column($globalStats['languages'] ?? [], 'time_spent')) !!},
                    backgroundColor: [
                        '#fbbf24',  // JavaScript
                        '#a855f7',  // PHP
                        '#f97316',  // HTML
                        '#3b82f6',  // CSS
                        '#22c55e',   // Python
                        '#ec4899',   // Rose
                        '#0ea5e9',   // Bleu clair
                        '#6366f1'    // Indigo
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