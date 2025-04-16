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
                                {{ isset($user->role) && $user->role == 'admin' ? 'Admin' : 'Pro' }}
                            </span>
                        </div>
                        <p class="text-gray-400">Développeur Full Stack • Membre depuis {{ isset($user->created_at) ? $user->created_at->diffForHumans() : 'récemment' }}</p>
                    </div>
                </div>
                
                <div>
                    <button type="submit" form="profile-form" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white hover:from-indigo-500 hover:to-purple-500 transition-all flex items-center shadow-lg shadow-indigo-500/20">
                        <i class="ph-floppy-disk text-lg mr-2"></i>
                        Enregistrer toutes les modifications
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulaire unifié pour toutes les données du profil -->
        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            
            <!-- Colonne de gauche - Statistiques et préférences -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Statistiques -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700 overflow-hidden">
                    <h3 class="text-lg font-medium text-white mb-5">Statistiques</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-400">Date d'inscription</span>
                                <span class="text-white">{{ isset($user->created_at) ? $user->created_at->format('d M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-400">Dernière connexion</span>
                                <span class="text-white">Aujourd'hui</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-400">Projets</span>
                                <span class="text-white">{{ $user->projects()->count() ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Préférences d'apparence -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-5">Apparence</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Mode sombre</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="dark_mode" class="sr-only peer" 
                                       {{ isset($user->preferences['theme']) && $user->preferences['theme'] == 'dark' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        
                        <!-- Couleur d'accent -->
                        <div>
                            <label class="block text-gray-300 mb-2">Couleur d'accent</label>
                            <div class="grid grid-cols-5 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent_color" value="indigo" class="sr-only peer" 
                                           {{ (!isset($user->preferences['accent_color']) || $user->preferences['accent_color'] == 'indigo') ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg bg-indigo-600 peer-checked:ring-2 peer-checked:ring-white"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent_color" value="purple" class="sr-only peer" 
                                           {{ isset($user->preferences['accent_color']) && $user->preferences['accent_color'] == 'purple' ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg bg-purple-600 peer-checked:ring-2 peer-checked:ring-white"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent_color" value="blue" class="sr-only peer" 
                                           {{ isset($user->preferences['accent_color']) && $user->preferences['accent_color'] == 'blue' ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg bg-blue-600 peer-checked:ring-2 peer-checked:ring-white"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent_color" value="green" class="sr-only peer" 
                                           {{ isset($user->preferences['accent_color']) && $user->preferences['accent_color'] == 'green' ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg bg-green-600 peer-checked:ring-2 peer-checked:ring-white"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="accent_color" value="pink" class="sr-only peer" 
                                           {{ isset($user->preferences['accent_color']) && $user->preferences['accent_color'] == 'pink' ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-lg bg-pink-600 peer-checked:ring-2 peer-checked:ring-white"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-5">Notifications</h3>
                    
                    <div class="space-y-4">
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
                    </div>
                </div>
            </div>
            
            <!-- Colonne centrale - Informations personnelles et coordonnées -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center mr-3">
                            <i class="ph-user text-indigo-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-white">Informations personnelles</h3>
                            <p class="text-gray-400 text-sm">Modifiez vos informations de base</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    
                    <!-- Bio -->
                    <div class="mt-6">
                        <label for="bio" class="block text-gray-400 text-sm mb-2">Bio</label>
                        <div class="relative">
                            <textarea id="bio" name="bio" rows="4" 
                                      class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                      placeholder="Parlez-nous de vous...">{{ $user->bio ?? 'Développeur passionné par les technologies web et l\'amélioration continue des applications.' }}</textarea>
                            <div class="absolute bottom-3 right-3 text-gray-500 text-xs">
                                <span id="bio-count">{{ strlen($user->bio ?? 'Développeur passionné par les technologies web et l\'amélioration continue des applications.') }}</span>/200
                            </div>
                        </div>
                        @error('bio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Coordonnées et localisation -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
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
                </div>
                
                <!-- Sécurité et mot de passe -->
                <div class="bg-gray-800/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center mr-3">
                            <i class="ph-lock text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-white">Sécurité du compte</h3>
                            <p class="text-gray-400 text-sm">Modifiez votre mot de passe</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="current_password" class="block text-gray-400 text-sm mb-2">Mot de passe actuel</label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                       placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-lock-key"></i>
                                </div>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-gray-400 text-sm mb-2">Nouveau mot de passe</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                       placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-password"></i>
                                </div>
                            </div>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="new_password_confirmation" class="block text-gray-400 text-sm mb-2">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                       class="w-full bg-gray-700/50 border border-gray-600 rounded-xl py-3 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                       placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-400">
                                    <i class="ph-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <p class="text-gray-400 text-sm">Laissez ces champs vides si vous ne souhaitez pas changer de mot de passe.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Zone de danger - optionnelle -->
                <div class="bg-red-900/20 p-5 rounded-2xl border border-red-700/50">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-lg bg-red-900/30 flex items-center justify-center mr-3">
                            <i class="ph-warning-circle text-red-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-white">Zone de danger</h3>
                            <p class="text-gray-400 text-sm">Actions irréversibles</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="#" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) document.getElementById('delete-account-form').submit();" 
                           class="px-5 py-2.5 bg-red-900/30 hover:bg-red-900/50 border border-red-700/30 rounded-xl text-red-400 hover:text-red-300 inline-flex items-center transition-all">
                            <i class="ph-trash text-lg mr-2"></i>
                            Supprimer mon compte
                        </a>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Formulaire de suppression de compte (caché) -->
        <form id="delete-account-form" action="{{ route('profile.delete') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="confirmation" value="DELETE">
        </form>
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
        
        // Fonction pour vérifier le formulaire avant soumission
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            // Si un des champs de mot de passe est rempli mais pas tous
            if ((currentPassword || newPassword || confirmPassword) && 
                !(currentPassword && newPassword && confirmPassword)) {
                e.preventDefault();
                alert('Pour changer votre mot de passe, tous les champs de mot de passe doivent être remplis.');
                return false;
            }
            
            // Si les nouveaux mots de passe ne correspondent pas
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Les nouveaux mots de passe ne correspondent pas.');
                return false;
            }
            
            return true;
        });
    </script>
</x-app-layout>