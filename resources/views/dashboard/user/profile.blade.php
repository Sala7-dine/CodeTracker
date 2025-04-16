<x-app-layout>
    @include("layouts.user_sidebare")   

    <!-- Main Content -->
    <main class="ml-20 p-8">
        <!-- Bannière supérieure avec avatar et présentation -->
        <div class="relative mb-10">
            <!-- Bannière décorative avec motifs géométriques -->
            <div class="h-48 w-full rounded-2xl overflow-hidden relative bg-gradient-to-r from-indigo-900 via-purple-800 to-indigo-900">
                <div class="absolute inset-0 opacity-20">
                    @for ($i = 0; $i < 30; $i++)
                        <div class="absolute rounded-full opacity-70" 
                             style="width: {{ rand(5, 30) }}px; height: {{ rand(5, 30) }}px; 
                                    left: {{ rand(0, 100) }}%; top: {{ rand(0, 100) }}%; 
                                    animation: float {{ rand(10, 20) }}s infinite ease-in-out; 
                                    animation-delay: {{ $i * 0.1 }}s;
                                    background: rgba(255,255,255,{{ rand(2, 8) / 10 }});">
                        </div>
                    @endfor
                </div>
                
                <!-- Lignes de code stylisées -->
                <div class="absolute inset-0 opacity-10 text-xs text-white overflow-hidden">
                    @php
                        $codeSamples = [
                            "function updateProfile() {",
                            "  const user = getCurrentUser();",
                            "  if (user.isAuthenticated) {",
                            "    return saveChanges(user);",
                            "  }",
                            "  return redirect('/login');",
                            "}"
                        ];
                    @endphp
                    
                    @foreach($codeSamples as $index => $line)
                        <div class="whitespace-pre font-mono opacity-40" style="margin-left: {{ $index * 20 }}px;">{{ $line }}</div>
                    @endforeach
                </div>
            </div>
            
            <!-- Profil et actions principales -->
            <div class="flex justify-between items-end -mt-12 px-8">
                <div class="flex items-end">
                    <div class="relative group mr-6">
                        <form action="{{ route('profile.update.image') }}" method="POST" enctype="multipart/form-data" id="profile-image-form">
                            @csrf
                            <div class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl overflow-hidden shadow-lg">
                                <img src="{{ $user->profile_image_url ?? 'https://ui-avatars.com/api/?name='.$user->firstname.'+'.$user->lastname.'&background=6366f1&color=fff&size=120' }}" 
                                     alt="{{ $user->firstname }} {{ $user->lastname }}" class="w-full h-full object-cover">
                                
                                <!-- Effet de lumière dynamique -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Badge de statut -->
                            <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-gray-800"></div>
                            
                            <!-- Overlay d'upload au hover -->
                            <div class="absolute inset-0 bg-black/60 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all cursor-pointer">
                                <label for="profile-image-input" class="text-white cursor-pointer">
                                    <i class="ph-camera text-xl"></i>
                                    <input type="file" id="profile-image-input" name="profile_image" class="hidden" onchange="document.getElementById('profile-image-form').submit()">
                                </label>
                            </div>
                        </form>
                    </div>
                    
                    <div>
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-white">{{ $user->firstname ?? 'John' }} {{ $user->lastname ?? 'Doe' }}</h1>
                            <span class="ml-3 px-2 py-1 rounded-full text-xs bg-indigo-500/20 text-indigo-300 flex items-center">
                                <i class="ph-check-circle text-xs mr-1"></i>
                                {{ $user->role == 'admin' ? 'Admin' : 'Pro' }}
                            </span>
                        </div>
                        <p class="text-gray-400">Développeur Full Stack • Membre depuis {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button id="save-profile" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white hover:from-indigo-500 hover:to-purple-500 transition-all flex items-center shadow-lg shadow-indigo-500/20">
                        <i class="ph-floppy-disk text-lg mr-1.5"></i>
                        Sauvegarder
                    </button>
                </div>
            </div>
        </div>

        <!-- Onglets de navigation -->
        <div class="flex border-b border-gray-700 mb-8">
            <button class="px-6 py-3 text-indigo-400 border-b-2 border-indigo-500 font-medium">
                Informations personnelles
            </button>
            <button class="px-6 py-3 text-gray-400 hover:text-gray-300 transition-colors">
                Statistiques
            </button>
            <button class="px-6 py-3 text-gray-400 hover:text-gray-300 transition-colors">
                Préférences
            </button>
            <button class="px-6 py-3 text-gray-400 hover:text-gray-300 transition-colors">
                Sécurité
            </button>
        </div>

        <!-- Conteneur principal en trois colonnes -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Colonne 1: Statistiques et intégrations -->
            <div class="col-span-12 lg:col-span-3 space-y-6">
                <!-- Carte d'activité -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700 overflow-hidden">
                    <h3 class="text-lg font-medium text-white mb-5">Activité</h3>
                    
                    <div class="space-y-6">
                        <!-- Calendrier d'activité -->
                        <div class="grid grid-cols-7 gap-1.5">
                            @php
                                $days = 28;
                                $today = now()->day;
                            @endphp
                            
                            @for($i = 1; $i <= $days; $i++)
                                @php
                                    $intensity = rand(0, 4);
                                    $isToday = $i == $today;
                                    $bgClass = match($intensity) {
                                        0 => 'bg-gray-700/50',
                                        1 => 'bg-indigo-900/60',
                                        2 => 'bg-indigo-800/70',
                                        3 => 'bg-indigo-700/80',
                                        4 => 'bg-indigo-600',
                                    };
                                @endphp
                                
                                <div class="aspect-square {{ $bgClass }} rounded-sm {{ $isToday ? 'ring-2 ring-indigo-400' : '' }}" 
                                     title="{{ $intensity > 0 ? $intensity . 'h de codage' : 'Inactif' }}">
                                </div>
                            @endfor
                        </div>
                        
                        <!-- Statistiques de tendance -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-700/40 rounded-xl p-3 flex flex-col items-center justify-center">
                                <span class="text-gray-400 text-xs mb-1">Streak actuel</span>
                                <div class="flex items-center">
                                    <i class="ph-fire text-orange-400 mr-1"></i>
                                    <span class="text-white text-lg font-semibold">12 jours</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-700/40 rounded-xl p-3 flex flex-col items-center justify-center">
                                <span class="text-gray-400 text-xs mb-1">Meilleur streak</span>
                                <div class="flex items-center">
                                    <i class="ph-trophy text-yellow-400 mr-1"></i>
                                    <span class="text-white text-lg font-semibold">28 jours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sécurité et confidentialité -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Sécurité</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-300">Force du mot de passe</span>
                                <span class="text-green-400 text-sm">Forte</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-green-400" style="width: 85%"></div>
                            </div>
                        </div>
                        
                        <form action="{{ route('profile.update.password') }}" method="POST" class="w-full flex items-center justify-between px-4 py-2 bg-gray-700/60 hover:bg-gray-700 rounded-xl text-gray-300 transition-colors">
                            @csrf
                            <span>Changer le mot de passe</span>
                            <button type="submit" class="bg-transparent border-0">
                                <i class="ph-lock-key text-indigo-400 group-hover:rotate-12 transition-transform"></i>
                            </button>
                        </form>
                        
                        <button class="w-full flex items-center justify-between px-4 py-2 bg-gray-700/60 hover:bg-gray-700 rounded-xl text-gray-300 transition-colors group">
                            <span>Authentification 2FA</span>
                            <i class="ph-shield-check text-indigo-400 group-hover:rotate-12 transition-transform"></i>
                        </button>
                        
                        <button class="w-full flex items-center justify-between px-4 py-2 bg-red-900/30 hover:bg-red-900/40 rounded-xl text-red-300 transition-colors group">
                            <span>Supprimer mon compte</span>
                            <i class="ph-trash text-red-400 group-hover:rotate-12 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Colonne 2: Informations personnelles -->
            <div class="col-span-12 lg:col-span-6 space-y-6">
                <!-- Informations de base -->
                <form action="{{ route('profile.update') }}" method="POST" class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    @csrf
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center mr-3">
                            <i class="ph-user text-indigo-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-white">Informations personnelles</h3>
                            <p class="text-gray-400 text-sm">Modifiez vos informations de base</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="firstname" class="block text-gray-400 text-sm mb-2">Prénom</label>
                            <div class="relative">
                                <input type="text" id="firstname" name="firstname" value="{{ $user->firstname ?? '' }}" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-user-focus"></i>
                                </div>
                            </div>
                            @error('firstname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lastname" class="block text-gray-400 text-sm mb-2">Nom</label>
                            <div class="relative">
                                <input type="text" id="lastname" name="lastname" value="{{ $user->lastname ?? '' }}" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-user-focus"></i>
                                </div>
                            </div>
                            @error('lastname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-gray-400 text-sm mb-2">Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-at"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-gray-400 text-sm mb-2">Nom d'utilisateur</label>
                            <div class="relative">
                                <input type="text" id="username" name="username" value="{{ $user->username ?? '' }}" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-identification-badge"></i>
                                </div>
                            </div>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Bio et informations supplémentaires -->
                    <div class="mt-6">
                        <label for="bio" class="block text-gray-400 text-sm mb-2">Bio</label>
                        <div class="relative">
                            <textarea id="bio" name="bio" rows="4" 
                                      class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                      placeholder="Parlez-nous de vous...">{{ $user->bio ?? 'Développeur passionné par les technologies web et l\'amélioration continue des applications. J\'aime résoudre des problèmes complexes et apprendre de nouvelles technologies.' }}</textarea>
                            <div class="absolute bottom-3 right-3 text-gray-500 text-xs">
                                <span id="bio-count">{{ strlen($user->bio ?? '') }}</span>/200
                            </div>
                        </div>
                        @error('bio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white hover:from-indigo-500 hover:to-purple-500 transition-all flex items-center shadow-lg shadow-indigo-500/20">
                            <i class="ph-floppy-disk text-lg mr-1.5"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
       
                
                <!-- Coordonnées et localisation -->
                <form action="{{ route('profile.update') }}" method="POST" class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    @csrf
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center mr-3">
                            <i class="ph-map-pin text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-white">Coordonnées</h3>
                            <p class="text-gray-400 text-sm">Votre localisation et contact</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-gray-400 text-sm mb-2">Téléphone</label>
                            <div class="relative">
                                <input type="text" id="phone" name="phone" value="{{ $user->phone ?? '' }}" placeholder="+33 6 12 34 56 78" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-phone"></i>
                                </div>
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="website" class="block text-gray-400 text-sm mb-2">Site web</label>
                            <div class="relative">
                                <input type="url" id="website" name="website" value="{{ $user->website ?? '' }}" placeholder="https://example.com" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-globe"></i>
                                </div>
                            </div>
                            @error('website')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="country" class="block text-gray-400 text-sm mb-2">Pays</label>
                            <div class="relative">
                                <select id="country" name="country" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none">
                                    <option value="FR" {{ ($user->country ?? '') == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="US" {{ ($user->country ?? '') == 'US' ? 'selected' : '' }}>États-Unis</option>
                                    <option value="CA" {{ ($user->country ?? '') == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="UK" {{ ($user->country ?? '') == 'UK' ? 'selected' : '' }}>Royaume-Uni</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-caret-down"></i>
                                </div>
                            </div>
                            @error('country')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="city" class="block text-gray-400 text-sm mb-2">Ville</label>
                            <div class="relative">
                                <input type="text" id="city" name="city" value="{{ $user->city ?? '' }}" placeholder="Paris" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-buildings"></i>
                                </div>
                            </div>
                            @error('city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white hover:from-blue-500 hover:to-indigo-500 transition-all flex items-center shadow-lg shadow-blue-500/20">
                            <i class="ph-floppy-disk text-lg mr-1.5"></i>
                            Enregistrer les coordonnées
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Colonne 3: Préférences et confidentialité -->
            <div class="col-span-12 lg:col-span-3 space-y-6">
                <!-- Préférences d'apparence -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-5">Apparence</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Mode sombre</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        
                        <!-- Sélection de thème -->
                        <div>
                            <label class="block text-gray-300 mb-2">Thème de couleur</label>
                            <div class="grid grid-cols-5 gap-2">
                                <button class="w-full aspect-square rounded-lg bg-indigo-600 ring-2 ring-white"></button>
                                <button class="w-full aspect-square rounded-lg bg-purple-600"></button>
                                <button class="w-full aspect-square rounded-lg bg-blue-600"></button>
                                <button class="w-full aspect-square rounded-lg bg-green-600"></button>
                                <button class="w-full aspect-square rounded-lg bg-pink-600"></button>
                            </div>
                        </div>
                        
                        <!-- Taille de la police -->
                        <div>
                            <label class="block text-gray-300 mb-2">Taille de la police</label>
                            <div class="flex items-center justify-between bg-gray-700 rounded-lg h-10 px-2">
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white">
                                    <i class="ph-text-aa-decrease"></i>
                                </button>
                                <div class="flex-1 flex items-center justify-center space-x-1">
                                    @for ($i = 0; $i < 5; $i++)
                                        <div class="w-1 h-{{ 3 + $i }} bg-indigo-500 rounded-full"></div>
                                    @endfor
                                </div>
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white">
                                    <i class="ph-text-aa-increase"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications -->
                <form action="{{ route('profile.update.preferences') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300">Rapports hebdomadaires</p>
                            <p class="text-gray-500 text-xs">Recevez un résumé par email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="weekly_report" class="sr-only peer" 
                                   {{ isset($user->preferences['notifications']['weekly_report']) && $user->preferences['notifications']['weekly_report'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300">Objectifs atteints</p>
                            <p class="text-gray-500 text-xs">Notifications en temps réel</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="goal_achieved" class="sr-only peer"
                                   {{ isset($user->preferences['notifications']['goal_achieved']) && $user->preferences['notifications']['goal_achieved'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300">Actualités produit</p>
                            <p class="text-gray-500 text-xs">Nouvelles fonctionnalités</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="product_news" class="sr-only peer"
                                   {{ isset($user->preferences['notifications']['product_news']) && $user->preferences['notifications']['product_news'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl text-white hover:from-purple-500 hover:to-indigo-500 transition-all flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <i class="ph-floppy-disk text-lg mr-1.5"></i>
                        Enregistrer les préférences
                    </button>
                </form>
                
                
            </div>
        </div>
    </main>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>

    <script>
        // Compteur de caractères pour la bio
        document.getElementById('bio').addEventListener('input', function() {
            const maxLength = 200;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            document.getElementById('bio-count').textContent = currentLength;
            
            if (remaining < 20) {
                document.getElementById('bio-count').classList.add('text-red-400');
            } else {
                document.getElementById('bio-count').classList.remove('text-red-400');
            }
        });
    </script>
</x-app-layout>