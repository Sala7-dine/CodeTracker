<x-app-layout>
    <aside class="fixed left-0 top-0 h-screen w-64 bg-gray-800 border-r border-gray-700">
        <div class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <i class="ph-shield text-xl text-white"></i>
                </div>
                <span class="text-white font-bold text-lg">Admin Panel</span>
            </div>
        </div>

        <nav class="mt-6">
            <div class="px-6 py-3">
                <p class="text-xs font-semibold text-gray-400 uppercase">Principal</p>
            </div>
            <a href="#" class="flex items-center px-6 py-3 text-gray-100 bg-gray-700">
                <i class="ph-squares-four text-lg mr-3"></i>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-users text-lg mr-3"></i>
                Utilisateurs
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-folders text-lg mr-3"></i>
                Projets
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-chart-line text-lg mr-3"></i>
                Statistiques
            </a>

            <div class="px-6 py-3 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase">Système</p>
            </div>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-gear-six text-lg mr-3"></i>
                Paramètres
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-database text-lg mr-3"></i>
                Base de données
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
                <i class="ph-sign-out text-lg mr-3"></i>
                Déconnexion
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Administrateur</h1>
                <p class="text-gray-400">Vue d'ensemble du système</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600 transition-all">
                    <i class="ph-bell text-xl"></i>
                </button>
                <div class="flex items-center space-x-3 px-4 py-2 bg-gray-700 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-sm">AD</div>
                    <span class="text-white">Admin</span>
                </div>
            </div>
        </header>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Utilisateurs totaux</h3>
                    <span class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                        <i class="ph-users text-blue-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">1,247</p>
                <p class="text-sm text-blue-400 mt-2">+12 aujourd'hui</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Projets actifs</h3>
                    <span class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center">
                        <i class="ph-folder-open text-green-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">3,842</p>
                <p class="text-sm text-green-400 mt-2">+85 cette semaine</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Espace utilisé</h3>
                    <span class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center">
                        <i class="ph-database text-purple-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">78.5 GB</p>
                <p class="text-sm text-purple-400 mt-2">Sur 100 GB</p>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400">Charge serveur</h3>
                    <span class="w-8 h-8 rounded-lg bg-red-600/20 flex items-center justify-center">
                        <i class="ph-cpu text-red-400"></i>
                    </span>
                </div>
                <p class="text-3xl font-bold text-white">42%</p>
                <p class="text-sm text-red-400 mt-2">Normal</p>
            </div>
        </div>

        <!-- Recent Users & System Status -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Utilisateurs récents</h2>
                    <button class="text-sm text-gray-400 hover:text-white">Voir tout</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-700/50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">JD</div>
                            <div>
                                <h4 class="text-white">John Doe</h4>
                                <p class="text-sm text-gray-400">john@example.com</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Actif</span>
                    </div>
                    <!-- Autres utilisateurs... -->
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-6">État du système</h2>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">CPU</span>
                            <span class="text-white">42%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 42%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Mémoire</span>
                            <span class="text-white">68%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Stockage</span>
                            <span class="text-white">78.5%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 78.5%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-gray-800/50 backdrop-blur-xl p-6 rounded-2xl border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-6">Logs système récents</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-400 text-sm">
                            <th class="pb-4">Timestamp</th>
                            <th class="pb-4">Type</th>
                            <th class="pb-4">Message</th>
                            <th class="pb-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        <tr class="border-t border-gray-700">
                            <td class="py-3">2024-02-20 14:23</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">Warning</span>
                            </td>
                            <td>Haute utilisation CPU détectée</td>
                            <td>
                                <i class="ph-warning text-yellow-400"></i>
                            </td>
                        </tr>
                        <tr class="border-t border-gray-700">
                            <td class="py-3">2024-02-20 14:20</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Info</span>
                            </td>
                            <td>Backup automatique complété</td>
                            <td>
                                <i class="ph-check-circle text-green-400"></i>
                            </td>
                        </tr>
                        <!-- Autres logs... -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</x-app-layout>