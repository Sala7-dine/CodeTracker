<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeTracker - Suivi intelligent du temps de programmation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/phosphor-icons@1.4.2/src/css/icons.css" rel="stylesheet">
</head>
<body class="antialiased bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="absolute top-0 left-0 w-full z-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center px-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-600/20 rounded-xl">
                            <i class="ph-code-bold text-2xl text-indigo-400"></i>
                        </div>
                        <span class="text-xl font-bold text-white">CodeTracker</span>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/user/dashboard') }}" class="text-white hover:text-indigo-200 transition-colors duration-300">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-indigo-200 transition-colors duration-300">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105">
                                    Commencer
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center bg-gray-900 overflow-hidden">
        <!-- Éléments décoratifs -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 opacity-5">
                <!-- Grille de pseudo-code animée -->
                <div class="absolute inset-0 overflow-hidden opacity-20">
                    @for ($i = 0; $i < 15; $i++)
                        <div class="text-xs sm:text-sm text-indigo-300/40 whitespace-nowrap font-mono" style="transform: translateY({{ $i * 40 }}px)">
                            function trackCodingTime() { const start = new Date(); /** Tracking code time */ return (new Date() - start); }
                        </div>
                    @endfor
                </div>
            </div>
            <!-- Cercles lumineux -->
            <div class="absolute top-1/4 -left-12 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 -right-12 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Contenu principal -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Texte et CTA -->
                <div class="text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                        Suivez votre temps de code 
                        <span class="relative">
                            <span class="relative z-10 text-indigo-400">intelligemment</span>
                            <span class="absolute bottom-2 left-0 w-full h-3 bg-indigo-500/20 -rotate-2"></span>
                        </span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8">
                        Maximisez votre productivité en analysant précisément votre temps de programmation. Intégration VS Code, rapports détaillés et insights personnalisés.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-indigo-500/25">
                            <span>Commencer gratuitement</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#demo" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-700 text-gray-300 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300">
                            <i class="ph-play-circle-bold mr-2 text-lg"></i>
                            Voir la démo
                        </a>
                    </div>

                    <!-- Statistiques -->
                    <div class="mt-12 grid grid-cols-3 gap-8">
                        <div class="text-center p-4 bg-gray-800/50 rounded-xl backdrop-blur-sm">
                            <div class="text-3xl font-bold text-indigo-400">8K+</div>
                            <div class="text-gray-400 text-sm">Développeurs</div>
                        </div>
                        <div class="text-center p-4 bg-gray-800/50 rounded-xl backdrop-blur-sm">
                            <div class="text-3xl font-bold text-indigo-400">120K+</div>
                            <div class="text-gray-400 text-sm">Heures suivies</div>
                        </div>
                        <div class="text-center p-4 bg-gray-800/50 rounded-xl backdrop-blur-sm">
                            <div class="text-3xl font-bold text-indigo-400">25%</div>
                            <div class="text-gray-400 text-sm">Productivité gagnée</div>
                        </div>
                    </div>
                </div>

                <!-- Image/Animation -->
                <div class="relative hidden lg:block">
                    <div class="relative z-10 bg-gray-800/70 backdrop-blur-sm rounded-2xl p-2 shadow-2xl transform hover:scale-105 transition-all duration-500 border border-gray-700">
                        <img src="/dashboard-preview.png" alt="Dashboard CodeTracker" class="rounded-xl">
                        
                        <!-- Éléments flottants -->
                        <div class="absolute -top-6 -right-6 bg-indigo-600 text-white p-4 rounded-xl shadow-lg transform rotate-6">
                            <div class="text-sm font-semibold">5h32 de code aujourd'hui</div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-purple-600 text-white p-4 rounded-xl shadow-lg transform -rotate-6">
                            <div class="text-sm font-semibold">Productivité +18%</div>
                        </div>
                        
                        <!-- Aperçu extension VS Code -->
                        <div class="absolute -bottom-12 -right-12 bg-gray-900/90 border border-gray-700 p-3 rounded-lg transform rotate-12 shadow-xl">
                            <div class="flex items-center text-xs text-indigo-300">
                                <i class="ph-code text-indigo-400 mr-2"></i>
                                <span>Extension VS Code</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comment ça marche Section -->
    <section id="how-it-works" class="py-24 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-bold text-white mb-4">Comment ça marche ?</h2>
                <p class="text-xl text-gray-400">Une solution complète pour suivre et optimiser votre temps de codage</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-indigo-900/50 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-400">1</div>
                    <div class="bg-gray-800 rounded-3xl p-8 h-full transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-700/50">
                        <div class="h-16 w-16 bg-indigo-600/30 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ph-plug-bold text-3xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-white mb-4">Installez l'extension</h3>
                        <p class="text-gray-400">Connectez CodeTracker à votre environnement VS Code pour un suivi automatique de vos activités de programmation.</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-indigo-900/50 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-400">2</div>
                    <div class="bg-gray-800 rounded-3xl p-8 h-full transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-700/50">
                        <div class="h-16 w-16 bg-indigo-600/30 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ph-code-bold text-3xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-white mb-4">Codez normalement</h3>
                        <p class="text-gray-400">Continuez à travailler sur vos projets comme d'habitude pendant que nous suivons discrètement votre activité.</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-indigo-900/50 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-400">3</div>
                    <div class="bg-gray-800 rounded-3xl p-8 h-full transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-700/50">
                        <div class="h-16 w-16 bg-indigo-600/30 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ph-chart-line-up-bold text-3xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-white mb-4">Analysez vos données</h3>
                        <p class="text-gray-400">Consultez des rapports détaillés, identifiez les tendances et optimisez votre productivité grâce à nos insights.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-gray-800 relative overflow-hidden">
        <div class="absolute inset-0">
            <!-- Élements décoratifs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-bold text-white mb-4">Fonctionnalités avancées</h2>
                <p class="text-xl text-gray-400">Des outils puissants pour les développeurs soucieux de leur productivité</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <div class="bg-gray-900 rounded-3xl p-8 shadow-lg transform transition-all duration-300 hover:shadow-2xl border border-gray-700/50">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-900/50 rounded-2xl flex items-center justify-center">
                                <i class="ph-clock-countdown-bold text-xl text-indigo-400"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Suivi automatique</h3>
                            <p class="text-gray-400">Enregistrement précis de vos sessions de codage avec détection intelligente des périodes d'inactivité.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-3xl p-8 shadow-lg transform transition-all duration-300 hover:shadow-2xl border border-gray-700/50">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-900/50 rounded-2xl flex items-center justify-center">
                                <i class="ph-git-branch-bold text-xl text-indigo-400"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Intégration avec les projets</h3>
                            <p class="text-gray-400">Associez automatiquement votre temps aux différents projets, branches Git et langages de programmation.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-3xl p-8 shadow-lg transform transition-all duration-300 hover:shadow-2xl border border-gray-700/50">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-900/50 rounded-2xl flex items-center justify-center">
                                <i class="ph-graph-bold text-xl text-indigo-400"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Visualisations avancées</h3>
                            <p class="text-gray-400">Graphiques interactifs et tableaux de bord personnalisables pour analyser votre temps de programmation.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-3xl p-8 shadow-lg transform transition-all duration-300 hover:shadow-2xl border border-gray-700/50">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-900/50 rounded-2xl flex items-center justify-center">
                                <i class="ph-lightbulb-bold text-xl text-indigo-400"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Insights de productivité</h3>
                            <p class="text-gray-400">Recevez des recommandations personnalisées basées sur vos habitudes de codage pour optimiser votre efficacité.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <!-- Élements décoratifs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Ce que disent nos utilisateurs</h2>
                <p class="text-xl text-gray-400">Découvrez comment CodeTracker transforme la productivité des développeurs</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-gray-800/70 backdrop-blur-sm rounded-3xl p-8 border border-gray-700 shadow-xl">
                    <div class="flex flex-col h-full">
                        <div class="mb-4">
                            <i class="ph-quotes text-3xl text-indigo-400"></i>
                        </div>
                        <p class="text-gray-300 mb-8 flex-grow">
                            "En tant que freelance, facturer précisément mon temps était un défi. Grâce à CodeTracker, je peux désormais suivre automatiquement chaque minute passée sur mes projets clients, ce qui a révolutionné ma facturation."
                        </p>
                        <div class="flex items-center">
                            <div class="mr-4">
                                <div class="w-12 h-12 bg-indigo-600/30 rounded-full flex items-center justify-center text-xl font-semibold text-white">MT</div>
                            </div>
                            <div>
                                <div class="text-white font-medium">Marie Thibault</div>
                                <div class="text-sm text-gray-400">Développeuse Web Freelance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/70 backdrop-blur-sm rounded-3xl p-8 border border-gray-700 shadow-xl">
                    <div class="flex flex-col h-full">
                        <div class="mb-4">
                            <i class="ph-quotes text-3xl text-indigo-400"></i>
                        </div>
                        <p class="text-gray-300 mb-8 flex-grow">
                            "L'extension VS Code s'intègre parfaitement à mon flux de travail. Les insights sur ma productivité m'ont permis d'identifier mes périodes de concentration optimales et d'améliorer mon efficacité de plus de 30%."
                        </p>
                        <div class="flex items-center">
                            <div class="mr-4">
                                <div class="w-12 h-12 bg-indigo-600/30 rounded-full flex items-center justify-center text-xl font-semibold text-white">TK</div>
                            </div>
                            <div>
                                <div class="text-white font-medium">Thomas Kouassi</div>
                                <div class="text-sm text-gray-400">Lead Developer, TechInnovate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Extension VS Code -->
    <section class="py-24 bg-gray-800 relative overflow-hidden">
        <div class="absolute inset-0">
            <!-- Élements décoratifs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-white mb-6">Extension VS Code intégrée</h2>
                    <p class="text-xl text-gray-300 mb-8">
                        Restez dans votre flux de travail pendant que notre extension VS Code suit discrètement votre activité de codage en arrière-plan.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3 mt-0.5"></i>
                            <span class="text-gray-300">Suivi automatique du temps par fichier et projet</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3 mt-0.5"></i>
                            <span class="text-gray-300">Détection intelligente des périodes d'inactivité</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3 mt-0.5"></i>
                            <span class="text-gray-300">Synchronisation bidirectionnelle avec l'application web</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3 mt-0.5"></i>
                            <span class="text-gray-300">Fonctionnement hors ligne avec synchronisation ultérieure</span>
                        </li>
                    </ul>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-indigo-500/25">
                        <i class="ph-download-bold mr-2"></i>
                        <span>Télécharger l'extension</span>
                    </a>
                </div>
                <div class="relative">
                    <div class="relative z-10 bg-gray-900 rounded-2xl p-1 border border-gray-700 shadow-2xl">
                        <div class="bg-gray-800 rounded-t-xl p-2 flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <div class="ml-2 text-xs text-gray-400">VS Code</div>
                        </div>
                        <div class="p-4 font-mono text-xs text-gray-300 overflow-hidden">
                            <div class="flex justify-between items-center bg-gray-700/30 p-2 rounded mb-2">
                                <div class="flex items-center">
                                    <i class="ph-activity-bold text-indigo-400 mr-2"></i>
                                    <span>CodeTracker</span>
                                </div>
                                <div class="text-indigo-400">3:45:12 aujourd'hui</div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-gray-700/30 p-2 rounded">
                                    <div class="text-xs text-indigo-400 mb-1">Projets actifs</div>
                                    <div class="mb-1">• E-commerce API <span class="text-green-400">1:12:05</span></div>
                                    <div>• Dashboard UI <span class="text-green-400">2:33:07</span></div>
                                </div>
                                <div class="bg-gray-700/30 p-2 rounded">
                                    <div class="text-xs text-indigo-400 mb-1">Langages</div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span>JavaScript</span>
                                        <span class="text-green-400">65%</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-600 rounded-full mb-1">
                                        <div class="h-1.5 bg-green-500 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span>HTML/CSS</span>
                                        <span class="text-green-400">35%</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-600 rounded-full">
                                        <div class="h-1.5 bg-indigo-500 rounded-full" style="width: 35%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-700/30 p-2 rounded">
                                <div class="text-xs text-indigo-400 mb-2">Session en cours</div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="ph-file-code-bold mr-2 text-indigo-400"></i>
                                        <span>UserController.php</span>
                                    </div>
                                    <div class="bg-indigo-600/30 px-2 py-0.5 rounded text-indigo-300">
                                        <span class="animate-pulse">●</span> 
                                        Suivi en cours
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Élément décoratif -->
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA améliorée -->
    <section class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <!-- Élements décoratifs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/70 backdrop-blur-sm rounded-3xl p-12 shadow-2xl border border-gray-700">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-white mb-6">
                            Prêt à optimiser votre temps de code ?
                        </h2>
                        <p class="text-xl text-gray-300 mb-8">
                            Rejoignez des milliers de développeurs qui ont déjà amélioré leur productivité grâce à CodeTracker.
                        </p>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-300">
                                <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3"></i>
                                Configuration simple en moins de 2 minutes
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3"></i>
                                Plan gratuit disponible sans limite de temps
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="ph-check-circle-bold text-xl text-indigo-400 mr-3"></i>
                                Données exportables à tout moment
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-900 p-8 rounded-2xl border border-gray-700">
                        <form class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="votreemail@exemple.com">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-indigo-500/25">
                                Créer un compte gratuitement
                            </button>
                            <p class="text-sm text-gray-400 text-center">
                                Pas de carte de crédit requise
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-gray-800 relative overflow-hidden">
        <div class="absolute inset-0">
            <!-- Élements décoratifs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Questions fréquentes</h2>
                <p class="text-xl text-gray-400">Tout ce que vous devez savoir sur CodeTracker</p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-semibold text-white mb-3">Comment fonctionne l'extension VS Code ?</h3>
                    <p class="text-gray-300">
                        Notre extension s'intègre directement à VS Code et enregistre automatiquement le temps que vous passez sur chaque fichier. Elle détecte intelligemment les périodes d'inactivité et synchronise vos données avec votre compte web pour une analyse approfondie.
                    </p>
                </div>
                
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-semibold text-white mb-3">Mes données sont-elles sécurisées ?</h3>
                    <p class="text-gray-300">
                        Absolument. Nous utilisons un chiffrement de bout en bout pour protéger vos données. De plus, nous ne collectons que les métadonnées (temps, noms de fichiers, langages) - jamais votre code source ou des informations personnelles sensibles.
                    </p>
                </div>
                
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-semibold text-white mb-3">CodeTracker fonctionne-t-il avec d'autres éditeurs de code ?</h3>
                    <p class="text-gray-300">
                        Actuellement, nous prenons en charge VS Code, mais des extensions pour d'autres éditeurs populaires (IntelliJ, Sublime Text, Atom) sont en développement et seront disponibles prochainement.
                    </p>
                </div>
                
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-semibold text-white mb-3">Puis-je exporter mes données ?</h3>
                    <p class="text-gray-300">
                        Oui, vous pouvez exporter toutes vos données à tout moment dans plusieurs formats (CSV, JSON, PDF) pour les utiliser dans d'autres outils ou pour vos propres analyses.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2 bg-indigo-600/20 rounded-xl">
                            <i class="ph-code-bold text-2xl text-indigo-400"></i>
                        </div>
                        <span class="text-xl font-bold text-white">CodeTracker</span>
                    </div>
                    <p class="text-gray-400">Suivez, analysez et optimisez votre temps de programmation de manière intelligente.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">À propos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Fonctionnalités</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Extension VS Code</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Conditions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">RGPD</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Rejoignez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">
                            <i class="ph-github-logo-bold text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">
                            <i class="ph-twitter-logo-bold text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300">
                            <i class="ph-linkedin-logo-bold text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} CodeTracker. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>