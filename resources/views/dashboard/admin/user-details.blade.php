<x-app-layout>
    <!-- Sidebar -->
    @include('layouts.admin_sidebar')

    <!-- Main Content -->
    <main class="ml-64 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-white">
                        <i class="ph-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-white">Profil utilisateur</h1>
                </div>
                <p class="text-gray-400 mt-1">Détails et activités de {{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($user->id !== 1) {{-- Protéger l'administrateur principal --}}
                    <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 {{ $user->is_active ? 'bg-red-600/20 text-red-400 hover:bg-red-600/30' : 'bg-green-600/20 text-green-400 hover:bg-green-600/30' }} rounded-lg transition-colors flex items-center">
                            <i class="{{ $user->is_active ? 'ph-prohibit' : 'ph-check-circle' }} text-lg mr-2"></i>
                            {{ $user->is_active ? 'Bloquer' : 'Débloquer' }}
                        </button>
                    </form>
                    
                    <button onclick="document.getElementById('roleModal').showModal()" class="px-4 py-2 bg-indigo-600/20 text-indigo-400 hover:bg-indigo-600/30 rounded-lg transition-colors flex items-center">
                        <i class="ph-user-gear text-lg mr-2"></i>
                        Modifier rôle
                    </button>
                    
                    <button onclick="document.getElementById('deleteModal').showModal()" class="px-4 py-2 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg transition-colors flex items-center">
                        <i class="ph-trash text-lg mr-2"></i>
                        Supprimer
                    </button>
                @endif
            </div>
        </header>

        <!-- Alerts de succès/erreur -->
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="ph-check-circle text-xl mr-2"></i>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto text-green-400 hover:text-green-300">
                    <i class="ph-x"></i>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="ph-x-circle text-xl mr-2"></i>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-300">
                    <i class="ph-x"></i>
                </button>
            </div>
        @endif

        <!-- User Profile -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Profile Card -->
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-24 h-24 rounded-full {{ $user->role === 'admin' ? 'bg-red-600' : 'bg-blue-600' }} flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strlen($user->name) > 1 ? strtoupper(substr($user->name, 1, 1)) : '' }}
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <div class="mt-3">
                        <span class="px-3 py-1 rounded-full text-xs {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }} ml-2">
                            {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                        </span>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 pt-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Membre depuis</span>
                            <span class="text-white">{{ $userStats['memberSince'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Dernière activité</span>
                            <span class="text-white">{{ $userStats['lastActive'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Projets</span>
                            <span class="text-white">{{ $userStats['totalProjects'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Temps de codage</span>
                            <span class="text-white">{{ $userStats['totalCodingTime'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Lignes de code</span>
                            <span class="text-white">{{ number_format($userStats['totalLines']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Projects -->
            <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-6">Projets de l'utilisateur</h3>
                @if($user->projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->projects as $project)
                            <div class="bg-gray-700/50 p-4 rounded-xl hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-white font-medium">{{ $project->name }}</h4>
                                        <p class="text-sm text-gray-400">
                                            {{ Str::limit($project->description ?? 'Pas de description', 80) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-gray-400 text-xs">Activités</div>
                                            <div class="text-white">{{ $project->activities_count }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-400 text-xs">Langages</div>
                                            <div class="text-white">{{ $project->languages_count }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-16 h-16 bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                            <i class="ph-folder-simple-dashed text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-400">Cet utilisateur n'a pas encore de projets</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Modals -->
        @if($user->id !== 1) {{-- Protéger l'administrateur principal --}}
            <!-- Modal pour changer le rôle -->
            <dialog id="roleModal" class="bg-gray-800 text-gray-300 rounded-lg p-0 shadow-lg backdrop:bg-black/70">
                <div class="p-6 w-96">
                    <h3 class="text-lg font-semibold text-white mb-4">Changer le rôle de {{ $user->name }}</h3>
                    <form method="POST" action="{{ route('admin.users.changeRole', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-400 mb-2">Sélectionner un rôle</label>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="role" value="user" {{ $user->role === 'user' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 border-gray-600 bg-gray-700">
                                    <span>Utilisateur</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="role" value="admin" {{ $user->role === 'admin' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 border-gray-600 bg-gray-700">
                                    <span>Administrateur</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="document.getElementById('roleModal').close()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Changer le rôle
                            </button>
                        </div>
                    </form>
                </div>
            </dialog>
            
            <!-- Modal de confirmation pour supprimer -->
            <dialog id="deleteModal" class="bg-gray-800 text-gray-300 rounded-lg p-0 shadow-lg backdrop:bg-black/70">
                <div class="p-6 w-96">
                    <h3 class="text-lg font-semibold text-white mb-2">Confirmer la suppression</h3>
                    <p class="text-gray-400 mb-4">Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ? Cette action est irréversible et supprimera également tous les projets associés.</p>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="document.getElementById('deleteModal').close()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600">
                            Annuler
                        </button>
                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
            </dialog>
        @endif
    </main>
</x-app-layout>