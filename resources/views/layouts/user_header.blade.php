<!-- Header -->
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-400">Bienvenue sur CodeTrack, {{ auth()->user()->firstname }}</p>
    </div>
    <div class="flex items-center space-x-4">
        <button class="px-4 py-2 bg-gray-700 rounded-lg text-gray-300 hover:bg-gray-600 transition-all">
            <i class="ph-bell text-xl"></i>
        </button>
        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">JD</div>
    </div>
</header>