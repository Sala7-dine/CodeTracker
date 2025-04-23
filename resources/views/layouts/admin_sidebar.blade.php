<aside class="fixed left-0 top-0 h-screen w-64 bg-gray-800 border-r border-gray-700 z-10">
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
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-100 bg-gray-700' : 'text-gray-400 hover:bg-gray-700 hover:text-gray-100' }}">
            <i class="ph-squares-four text-lg mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users') || request()->routeIs('admin.users.show') ? 'text-gray-100 bg-gray-700' : 'text-gray-400 hover:bg-gray-700 hover:text-gray-100' }}">
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
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-6 py-3 text-gray-400 hover:bg-gray-700 hover:text-gray-100">
            <i class="ph-sign-out text-lg mr-3"></i>
            Déconnexion
        </a>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
</aside>