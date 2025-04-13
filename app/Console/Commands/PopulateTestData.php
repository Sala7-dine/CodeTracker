<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Language;
use App\Models\Activity;
use Illuminate\Console\Command;

class PopulateTestData extends Command
{
    protected $signature = 'codetracker:populate';
    protected $description = 'Populate database with test data for CodeTracker';

    public function handle()
    {
        $this->info('Generating test data for CodeTracker...');

        // Créer quelques projets
        $projects = [
            ['name' => 'CodeTracker', 'description' => 'Extension de suivi du temps de codage'],
            ['name' => 'E-commerce', 'description' => 'Boutique en ligne'],
            ['name' => 'Portfolio', 'description' => 'Site portfolio personnel'],
            ['name' => 'Blog', 'description' => 'Blog personnel avec Laravel'],
            ['name' => 'API REST', 'description' => 'API de gestion de tâches'],
        ];

        $this->info('Creating projects...');
        $projectBar = $this->output->createProgressBar(count($projects));
        $projectBar->start();

        foreach ($projects as $projectData) {
            $project = Project::firstOrCreate([
                'name' => $projectData['name']
            ], [
                'description' => $projectData['description'],
                'environment_info' => [
                    'editor' => 'VS Code',
                    'os' => PHP_OS,
                    'extensions' => [
                        ['name' => 'GitLens', 'active' => true],
                        ['name' => 'Prettier', 'active' => true],
                        ['name' => 'ESLint', 'active' => mt_rand(0, 1) === 1],
                    ]
                ]
            ]);

            // Créer des langages pour chaque projet
            $languages = [
                ['name' => 'JavaScript', 'files' => mt_rand(10, 50), 'lines' => mt_rand(1000, 5000), 'time_ms' => mt_rand(3600000, 10800000)],
                ['name' => 'PHP', 'files' => mt_rand(5, 30), 'lines' => mt_rand(800, 3000), 'time_ms' => mt_rand(2400000, 7200000)],
                ['name' => 'HTML', 'files' => mt_rand(3, 15), 'lines' => mt_rand(500, 1500), 'time_ms' => mt_rand(1200000, 3600000)],
                ['name' => 'CSS', 'files' => mt_rand(3, 15), 'lines' => mt_rand(400, 1200), 'time_ms' => mt_rand(900000, 2700000)],
            ];

            // Ajouter uniquement des langages aléatoires
            $numLangs = mt_rand(2, 4);
            shuffle($languages);
            $selectedLangs = array_slice($languages, 0, $numLangs);

            foreach ($selectedLangs as $langData) {
                Language::updateOrCreate(
                    ['project_id' => $project->id, 'name' => $langData['name']],
                    [
                        'files' => $langData['files'],
                        'lines' => $langData['lines'],
                        'time_ms' => $langData['time_ms'],
                    ]
                );
            }

            // Générer quelques activités pour ce projet
            $filesBase = [
                'JavaScript' => ['app.js', 'main.js', 'utils.js', 'components/Button.js', 'store/index.js'],
                'PHP' => ['app/Models/User.php', 'app/Http/Controllers/HomeController.php', 'routes/web.php', 'config/app.php'],
                'HTML' => ['index.html', 'about.html', 'contact.html', 'views/home.blade.php'],
                'CSS' => ['style.css', 'main.css', 'components.css', 'tailwind.css'],
            ];

            // Créer 5 à 15 activités pour chaque projet
            $numActivities = mt_rand(5, 15);
            for ($i = 0; $i < $numActivities; $i++) {
                // Sélectionner un langage
                $lang = $selectedLangs[array_rand($selectedLangs)];
                $langName = $lang['name'];
                
                // Sélectionner un fichier pour ce langage
                $fileList = $filesBase[$langName] ?? ['unknown.txt'];
                $fileName = $fileList[array_rand($fileList)];
                
                // Générer un timestamp dans les derniers 30 jours
                $timestamp = now()->subDays(mt_rand(0, 30))->subHours(mt_rand(0, 24))->subMinutes(mt_rand(0, 60));
                
                // Créer l'activité
                Activity::create([
                    'project_id' => $project->id,
                    'file_path' => 'c:/projects/' . $projectData['name'] . '/' . $fileName,
                    'file_name' => $fileName,
                    'language' => $langName,
                    'duration' => mt_rand(60, 3600), // Entre 1 minute et 1 heure
                    'lines' => mt_rand(10, 200),
                    'activity_time' => $timestamp,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            $projectBar->advance();
        }

        $projectBar->finish();
        $this->newLine(2);
        $this->info('Test data generated successfully!');
        
        return Command::SUCCESS;
    }
}
