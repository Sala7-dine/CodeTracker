<x-app-layout>
    <!-- Sidebar -->
    @include('layouts.admin_sidebar')

    <!-- Main Content -->
    <main class="ml-64 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Gestion des utilisateurs</h1>
                <p class="text-gray-400">Administration des comptes utilisateurs</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <form action="{{ route('admin.users') }}" method="GET">
                        <input type="text" name="search" placeholder="Rechercher un utilisateur..." class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-300 w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ request('search') }}">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                            <i class="ph-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
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

        <!-- Users Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-white">Liste des utilisateurs</h2>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-400">{{ $users->total() }} utilisateurs au total</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                            <th class="pb-4">ID</th>
                            <th class="pb-4">Utilisateur</th>
                            <th class="pb-4">Email</th>
                            <th class="pb-4">Rôle</th>
                            <th class="pb-4">Projets</th>
                            <th class="pb-4">Date d'inscription</th>
                            <th class="pb-4">Statut</th>
                            <th class="pb-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($users as $user)
                            <tr class="border-b border-gray-700/50 hover:bg-gray-700/30">
                                <td class="py-4">{{ $user->id }}</td>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-full {{ $user->role === 'admin' ? 'bg-red-600' : 'bg-blue-600' }} flex items-center justify-center text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strlen($user->name) > 1 ? strtoupper(substr($user->name, 1, 1)) : '' }}
                                        </div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-indigo-400 transition">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->projects_count }}</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                        {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Menu contextuel -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="p-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600">
                                                <i class="ph-dots-three-vertical"></i>
                                            </button>
                                            
                                            <!-- Dropdown menu -->
                                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg py-1 border border-gray-700">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 flex items-center">
                                                    <i class="ph-user text-lg mr-2"></i>
                                                    Voir détails
                                                </a>
                                                
                                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-300 hover:bg-gray-700 flex items-center">
                                                        <i class="{{ $user->is_active ? 'ph-prohibit' : 'ph-check-circle' }} text-lg mr-2"></i>
                                                        {{ $user->is_active ? 'Bloquer' : 'Débloquer' }}
                                                    </button>
                                                </form>
                                                
                                                <button @click="$refs.roleModal{{ $user->id }}.showModal()" class="w-full text-left px-4 py-2 text-gray-300 hover:bg-gray-700 flex items-center">
                                                    <i class="ph-user-gear text-lg mr-2"></i>
                                                    Changer le rôle
                                                </button>
                                                
                                                <button @click="$refs.deleteModal{{ $user->id }}.showModal()" class="w-full text-left px-4 py-2 text-red-400 hover:bg-gray-700 flex items-center">
                                                    <i class="ph-trash text-lg mr-2"></i>
                                                    Supprimer
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal pour changer le rôle -->
                                        <dialog x-ref="roleModal{{ $user->id }}" class="bg-gray-800 text-gray-300 rounded-lg p-0 shadow-lg backdrop:bg-black/70">
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
                                                        <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600">
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
                                        <dialog x-ref="deleteModal{{ $user->id }}" class="bg-gray-800 text-gray-300 rounded-lg p-0 shadow-lg backdrop:bg-black/70">
                                            <div class="p-6 w-96">
                                                <h3 class="text-lg font-semibold text-white mb-2">Confirmer la suppression</h3>
                                                <p class="text-gray-400 mb-4">Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ? Cette action est irréversible et supprimera également tous les projets associés.</p>
                                                <div class="flex justify-end space-x-3 mt-6">
                                                    <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600">
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="8" class="py-6 text-center text-gray-400">Aucun utilisateur trouvé</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </main>
</x-app-layout>