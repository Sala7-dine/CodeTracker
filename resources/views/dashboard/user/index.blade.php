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
                <p class="text-3xl font-bold text-white">{{ $globalStats['formattedTime'] }}</p>
                <p class="text-sm text-green-400 mt-2">+2.5% vs semaine dernière</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-folders text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $number_of_projects }}</p>
                <p class="text-sm text-purple-400 mt-2">2 nouveaux cette semaine</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Lignes de code</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-code-block text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $globalStats['totalLines'] }}</p>
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

        <!-- Résumé du projet actuel -->
        @if(isset($globalStats['currentProject']))
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-white">Projet actuel: {{ $globalStats['currentProject']['name'] }}</h2>
                    <p class="text-gray-400">Vue d'ensemble du projet</p>
                </div>
                <a href="#" class="px-4 py-2 bg-indigo-600 rounded-lg text-white text-sm hover:bg-indigo-700 transition-all">
                    Voir le détail
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-700/50 p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400">Fichiers</h3>
                        <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                            <i class="ph-file-text text-purple-400"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ $globalStats['currentProject']['totalFiles'] }}</p>
                </div>
                
                <div class="bg-gray-700/50 p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400">Lignes de code</h3>
                        <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                            <i class="ph-code-block text-blue-400"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ $globalStats['currentProject']['totalLines'] }}</p>
                </div>
                
                <div class="bg-gray-700/50 p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-400">Temps total</h3>
                        <span class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                            <i class="ph-clock text-indigo-400"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ $globalStats['currentProject']['formattedTime'] }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Nouvelle section: Technologies utilisées -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white mb-2">Technologies utilisées</h2>
            @if(isset($globalStats['currentProject']))
                <p class="text-gray-400 mb-6">Projet actuel: <span class="text-indigo-400">{{ $globalStats['currentProject']['name'] }}</span></p>
            @else
                <p class="text-gray-400 mb-6">Tous les projets</p>
            @endif
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if(isset($globalStats['languages']) && count($globalStats['languages']) > 0)
                    @foreach($globalStats['languages'] as $langName => $lang)
                        <div class="bg-gray-700/50 p-4 rounded-xl flex items-center space-x-3">
                            <span class="w-10 h-10 rounded-lg 
                                @if(strtolower($langName) == 'javascript') bg-yellow-600/20
                                @elseif(strtolower($langName) == 'css') bg-blue-600/20
                                @elseif(strtolower($langName) == 'html') bg-orange-600/20
                                @elseif(strtolower($langName) == 'php') bg-purple-600/20
                                @elseif(strtolower($langName) == 'python') bg-green-600/20
                                @else bg-indigo-600/20
                                @endif
                                flex items-center justify-center">
                                <i class="
                                @if(strtolower($langName) == 'javascript') ph-file-js text-yellow-400
                                @elseif(strtolower($langName) == 'css') ph-file-css text-blue-400
                                @elseif(strtolower($langName) == 'html') ph-file-html text-orange-400
                                @elseif(strtolower($langName) == 'php') ph-file-php text-purple-400
                                @elseif(strtolower($langName) == 'python') ph-file-py text-green-400
                                @else ph-file-code text-indigo-400
                                @endif
                                text-xl"></i>
                            </span>
                            <div>
                                <h4 class="text-white font-medium">{{ ucfirst($langName) }}</h4>
                                <p class="text-sm text-gray-400">{{ $lang['formattedTime'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full bg-gray-700/50 p-5 rounded-xl border border-gray-600 text-center">
                        <i class="ph-code-bold text-indigo-400 text-4xl mb-3"></i>
                        <h3 class="text-lg font-medium text-white mb-2">Aucune donnée disponible</h3>
                        <p class="text-gray-400">Commencez à utiliser l'extension CodeTracker pour voir les statistiques de vos langages de programmation.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section Fichier Actuel -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <h2 class="text-xl font-semibold text-white mb-6">Session actuelle</h2>
            
            @if(isset($globalStats['currentFile']))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informations du fichier actuel -->
                <div class="col-span-2 bg-gray-700/50 p-5 rounded-xl border border-gray-600">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-600/30 flex items-center justify-center">
                            @php
                                $iconClass = 'ph-file-code';
                                $bgColor = 'bg-indigo-600/20';
                                $textColor = 'text-indigo-400';
                                
                                if (isset($globalStats['currentFile']['language'])) {
                                    $lang = strtolower($globalStats['currentFile']['language']);
                                    if (strpos($lang, 'javascript') !== false || strpos($lang, 'js') !== false) {
                                        $iconClass = 'ph-file-js';
                                        $bgColor = 'bg-yellow-600/20';
                                        $textColor = 'text-yellow-400';
                                    } elseif (strpos($lang, 'css') !== false) {
                                        $iconClass = 'ph-file-css';
                                        $bgColor = 'bg-blue-600/20';
                                        $textColor = 'text-blue-400';
                                    } elseif (strpos($lang, 'html') !== false) {
                                        $iconClass = 'ph-file-html';
                                        $bgColor = 'bg-orange-600/20';
                                        $textColor = 'text-orange-400';
                                    } elseif (strpos($lang, 'php') !== false) {
                                        $iconClass = 'ph-file-php';
                                        $bgColor = 'bg-purple-600/20';
                                        $textColor = 'text-purple-400';
                                    } elseif (strpos($lang, 'python') !== false) {
                                        $iconClass = 'ph-file-py';
                                        $bgColor = 'bg-green-600/20';
                                        $textColor = 'text-green-400';
                                    }
                                }
                            @endphp
                            <i class="{{ $iconClass }} {{ $textColor }} text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-white">{{ basename($globalStats['currentFile']['name']) }}</h3>
                            <p class="text-sm text-gray-400 truncate">{{ $globalStats['currentFile']['path'] }}</p>
                            <div class="flex items-center mt-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs {{ $bgColor }} {{ $textColor }} mr-2">
                                    {{ ucfirst($globalStats['currentFile']['language']) }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-600/50 text-gray-300">
                                    {{ $globalStats['currentFile']['lines'] }} lignes
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-gray-300 font-medium">Temps passé</div>
                            <div class="text-indigo-400 text-lg font-bold">{{ $globalStats['currentFile']['formattedTime'] }}</div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-600 pt-4 mt-3">
                        <div class="flex items-center space-x-2 text-gray-400 text-sm mb-2">
                            <i class="ph-activity text-green-400"></i>
                            <span>Dernière activité il y a {{ isset($globalStats['currentFile']['lastActive']) ? $globalStats['currentFile']['lastActive'] : 'quelques instants' }}</span>
                        </div>
                        <div class="w-full bg-gray-600 rounded-full h-2 mb-4">
                            <div class="bg-indigo-600 h-2 rounded-full animate-pulse" style="width: 85%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-gray-400">Modifications</p>
                                <p class="text-lg font-medium text-white">{{ $globalStats['currentFile']['edits'] ?? 23 }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Efficacité</p>
                                <p class="text-lg font-medium text-white">{{ $globalStats['currentFile']['efficiency'] ?? '95%' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Projet</p>
                                <p class="text-lg font-medium text-white">{{ $globalStats['currentFile']['project'] ?? 'Principal' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations système -->
                <div class="bg-gray-700/50 p-5 rounded-xl border border-gray-600">
                    <h3 class="text-md font-medium text-white mb-4 flex items-center">
                        <i class="ph-desktop text-indigo-400 mr-2"></i>
                        Environnement
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Éditeur</p>
                            <div class="flex items-center">
                                <i class="ph-code-bold text-indigo-400 mr-2"></i>
                                <span class="text-white">{{ $globalStats['environment']['editor'] ?? 'VS Code' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Système d'exploitation</p>
                            <div class="flex items-center">
                                @php
                                    $osIcon = 'ph-windows-logo';
                                    if (isset($globalStats['environment']['os']) && strpos(strtolower($globalStats['environment']['os']), 'mac') !== false) {
                                        $osIcon = 'ph-apple-logo';
                                    } elseif (isset($globalStats['environment']['os']) && strpos(strtolower($globalStats['environment']['os']), 'linux') !== false) {
                                        $osIcon = 'ph-linux-logo';
                                    }
                                @endphp
                                <i class="{{ $osIcon }} text-indigo-400 mr-2"></i>
                                <span class="text-white">{{ $globalStats['environment']['os'] ?? 'Windows 11' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Extensions actives</p>
                            <div class="space-y-2 mt-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-300">CodeTracker</span>
                                    <span class="text-green-400 text-xs">Actif</span>
                                </div>
                                @if(isset($globalStats['environment']['extensions']))
                                    @foreach($globalStats['environment']['extensions'] as $ext)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-300">{{ $ext['name'] }}</span>
                                        <span class="text-{{ $ext['active'] ? 'green' : 'gray' }}-400 text-xs">
                                            {{ $ext['active'] ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-300">GitLens</span>
                                        <span class="text-green-400 text-xs">Actif</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-300">Prettier</span>
                                        <span class="text-green-400 text-xs">Actif</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-gray-700/50 p-5 rounded-xl border border-gray-600 text-center">
                <i class="ph-code-bold text-indigo-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-medium text-white mb-2">Aucune session active</h3>
                <p class="text-gray-400">Ouvrez un fichier dans VS Code avec l'extension CodeTracker pour commencer à suivre votre temps.</p>
            </div>
            @endif
        </div>

        <!-- Section des projets récents -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Projets récents</h2>
                <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                    Voir tous les projets
                    <i class="ph-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($projects->take(3) as $project)
                <div class="bg-gray-700/50 p-5 rounded-xl border border-gray-600 hover:border-indigo-500/50 transition-all hover:shadow-lg hover:shadow-indigo-500/10">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-white font-medium flex items-center">
                            <i class="ph-folder text-indigo-400 mr-2"></i>
                            {{ $project->name }}
                        </h3>
                        <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Actif</span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-400">{{ \App\Models\Language::formatTime($project->getTotalTime()) }}</span>
                        <span class="text-gray-400">{{ $project->getTotalFiles() }} fichiers</span>
                    </div>
                    
                    <div class="w-full bg-gray-600 rounded-full h-1.5">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 75%"></div>
                    </div>
                    
                    <div class="mt-4">
                        @if($project->languages && $project->languages->count() > 0)
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