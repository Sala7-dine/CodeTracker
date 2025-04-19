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