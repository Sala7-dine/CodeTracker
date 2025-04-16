<!-- filepath: c:\Users\LENOVO\Herd\CodeTracker\resources\views\layouts\guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://unpkg.com/phosphor-icons@1.4.2/src/css/icons.css" rel="stylesheet">

        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
 
        <!-- Phosphor Icons -->
        <script src="https://unpkg.com/phosphor-icons"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
        
        @if (session('success'))
            <div id="success-notification" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    document.getElementById('success-notification').style.display = 'none';
                }, 5000);
            </script>
        @endif

        @if (session('error'))
            <div class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center"
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90">
                <i class="ph-warning-circle mr-2 text-xl"></i>
                {{ session('error') }}
                <button @click="show = false" class="ml-4 text-white/80 hover:text-white">
                    <i class="ph-x"></i>
                </button>
            </div>
        @endif

        {{ $slot }}

        <!-- Projet Selection Modal -->
        <div id="project-modal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-gray-800 w-full max-w-2xl rounded-2xl border border-gray-700 overflow-hidden">
                <!-- Header -->
                <div class="p-5 border-b border-gray-700 flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center mr-3">
                            <i class="ph-folders text-white text-lg"></i>
                        </span>
                        <div>
                            <h3 class="text-xl font-medium text-white">Sélectionner un projet</h3>
                            <p class="text-gray-400 text-sm">Choisissez un projet pour voir ses détails</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-white transition-colors" onclick="closeProjectModal()">
                        <i class="ph-x text-2xl"></i>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-5">
                    <div class="mb-5">
                        <div class="flex rounded-lg border border-gray-700 overflow-hidden">
                            <div class="bg-gray-700 flex items-center px-3">
                                <i class="ph-magnifying-glass text-gray-400"></i>
                            </div>
                            <input type="text" id="project-search" placeholder="Rechercher un projet..." 
                                   class="bg-gray-900 text-white py-2 px-3 w-full border-none focus:outline-none"
                                   oninput="filterProjects()">
                        </div>
                    </div>
                    
                    <div class="max-h-[400px] overflow-y-auto pr-2 space-y-3" id="projects-list">
                        <!-- Indicateur de chargement -->
                        <div class="text-center py-8" id="loading-indicator">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-indigo-500 border-r-2 border-indigo-500 border-b-2 border-transparent"></div>
                            <p class="text-gray-400 mt-3">Chargement des projets...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="p-5 border-t border-gray-700 flex justify-between">
                    <button class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition-all"
                            onclick="closeProjectModal()">
                        Annuler
                    </button>
                    <a href="#" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all">
                        <i class="ph-plus mr-1"></i>Nouveau projet
                    </a>
                </div>
            </div>
        </div>

        <script>
        // Projets (sera chargé depuis le serveur)
        let allProjects = [];

        // Charger les projets via fetch
        function loadProjects() {
            const projectsList = document.getElementById('projects-list');
            const loadingIndicator = document.getElementById('loading-indicator');
            
            // Afficher l'indicateur de chargement
            projectsList.innerHTML = '';
            projectsList.appendChild(loadingIndicator);
            
            fetch('/api/projects')
                .then(response => response.json())
                .then(data => {
                    allProjects = data;
                    renderProjects(allProjects);
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des projets:', error);
                    projectsList.innerHTML = `
                        <div class="text-center py-8">
                            <i class="ph-warning-circle text-red-500 text-4xl mb-3"></i>
                            <p class="text-gray-400">Impossible de charger les projets</p>
                            <button class="mt-3 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600" onclick="loadProjects()">
                                Réessayer
                            </button>
                        </div>
                    `;
                });
        }

        // Fonction pour rendre les projets dans la modal
        function renderProjects(projects) {
            const projectsList = document.getElementById('projects-list');
            projectsList.innerHTML = '';
            
            if (projects.length === 0) {
                projectsList.innerHTML = `
                    <div class="text-center py-8">
                        <i class="ph-folder-open text-gray-500 text-4xl mb-3"></i>
                        <p class="text-gray-400">Aucun projet trouvé</p>
                        <button class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            <i class="ph-plus mr-1"></i>Créer un nouveau projet
                        </button>
                    </div>
                `;
                return;
            }
            
            projects.forEach(project => {
                const langBadges = project.languages && project.languages.length > 0 
                    ? project.languages.slice(0, 3).map(lang => {
                        let color = 'indigo';
                        if (lang.name.toLowerCase().includes('javascript')) color = 'yellow';
                        if (lang.name.toLowerCase().includes('php')) color = 'purple';
                        if (lang.name.toLowerCase().includes('html')) color = 'orange';
                        if (lang.name.toLowerCase().includes('css')) color = 'blue';
                        if (lang.name.toLowerCase().includes('python')) color = 'green';
                        
                        return `<span class="px-2 py-0.5 text-xs rounded-full bg-${color}-500/20 text-${color}-400">${lang.name}</span>`;
                      }).join('')
                    : '';
                    
                const projectElement = document.createElement('div');
                projectElement.className = 'p-4 bg-gray-800/50 rounded-xl border border-gray-700 hover:border-indigo-500/50 transition-all cursor-pointer';
                projectElement.onclick = () => {
                    window.location.href = `/dashboard/project/${project.id}`;
                };
                
                projectElement.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                                <i class="ph-folder text-indigo-400"></i>
                            </span>
                            <div>
                                <h4 class="text-white font-medium">${project.name}</h4>
                                <p class="text-sm text-gray-400">${project.total_files || 0} fichiers, ${project.total_lines || 0} lignes</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-300 text-sm">${project.formatted_time || '0h 0m'}</span>
                            <span class="text-green-400 text-xs">${project.updated_at ? 'Mis à jour ' + timeAgo(new Date(project.updated_at)) : ''}</span>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2 flex-wrap">
                        ${langBadges}
                    </div>
                `;
                
                projectsList.appendChild(projectElement);
            });
        }

        // Filtrer les projets
        function filterProjects() {
            const searchTerm = document.getElementById('project-search').value.toLowerCase();
            const filtered = allProjects.filter(project => 
                project.name.toLowerCase().includes(searchTerm)
            );
            renderProjects(filtered);
        }

        // Helper pour afficher le temps écoulé
        function timeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + " an" + (Math.floor(interval) > 1 ? "s" : "");
            
            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + " mois";
            
            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + " jour" + (Math.floor(interval) > 1 ? "s" : "");
            
            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + " heure" + (Math.floor(interval) > 1 ? "s" : "");
            
            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + " minute" + (Math.floor(interval) > 1 ? "s" : "");
            
            return Math.floor(seconds) + " seconde" + (Math.floor(seconds) > 1 ? "s" : "");
        }

        // Ouvrir la modal
        function openProjectModal() {
            document.getElementById('project-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadProjects();
        }

        // Fermer la modal
        function closeProjectModal() {
            document.getElementById('project-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('project-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectModal();
            }
        });
        </script>

    </body>
</html>