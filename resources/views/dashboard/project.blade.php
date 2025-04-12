<!DOCTYPE html>
<html>
<head>
    <title>{{ $project->name }} - CodeTrack</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .language-card {
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .card-stats {
            font-size: 1.2rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .activity-row:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">CodeTrack</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Projet: {{ $project->name }}</h1>
        <div class="row mt-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Statistiques du projet</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Fichiers:</span>
                            <span class="card-stats">{{ $project->getTotalFiles() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Lignes:</span>
                            <span class="card-stats">{{ $project->getTotalLines() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Temps total:</span>
                            <span class="card-stats">{{ $formattedTime }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Fichier actuel du projet</h5>
                    </div>
                    <div class="card-body">
                        @if($currentFile)
                            <div class="mb-2">
                                <strong>Nom:</strong> 
                                <span class="card-stats">{{ $currentFile->file_name }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Chemin:</strong>
                                <div class="text-truncate small">
                                    {{ $currentFile->file_path }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong>Langage:</strong> 
                                <span class="card-stats">{{ $currentFile->language ?? 'inconnu' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Lignes:</strong> 
                                <span class="card-stats">{{ $currentFile->lines }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Temps passé:</strong> 
                                <span class="card-stats">{{ \App\Models\Language::formatTime($currentFile->duration * 1000) }}</span>
                            </div>
                            <div class="small text-muted">
                                Dernière activité: {{ $currentFile->created_at->format('Y-m-d H:i:s') }}
                            </div>
                        @else
                            <div class="alert alert-info">Aucun fichier actif récemment dans ce projet</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistiques globales</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Fichiers:</span>
                            <span class="card-stats">{{ $project->getTotalFiles() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Lignes:</span>
                            <span class="card-stats">{{ $project->getTotalLines() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Temps total:</span>
                            <span class="card-stats">{{ \App\Models\Language::formatTime($project->getTotalTime()) }}</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistiques par langage</h5>
                    </div>
                    <div class="card-body">
                        @forelse($project->languages as $language)
                            <div class="language-card p-2 bg-light mb-3">
                                <h6>{{ $language->name }}</h6>
                                <div class="d-flex justify-content-between small">
                                    <span>Fichiers: {{ $language->files }}</span>
                                    <span>Lignes: {{ $language->lines }}</span>
                                </div>
                                <div class="small">
                                    Temps: {{ $language->getFormattedTime() }}
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                Aucune donnée de langage disponible
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Environnement</h5>
                    </div>
                    <div class="card-body">
                        @if($project->environment_info)
                            <div class="row">
                                @foreach($project->environment_info as $key => $value)
                                    <div class="col-md-6 mb-2">
                                        <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                Aucune information d'environnement disponible
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activités récentes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Fichier</th>
                                        <th>Langage</th>
                                        <th>Durée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activities as $activity)
                                        <tr class="activity-row">
                                            <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td title="{{ $activity->file_path }}">{{ $activity->file_name }}</td>
                                            <td>{{ $activity->language ?? 'N/A' }}</td>
                                            <td>{{ $activity->getFormattedDuration() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Aucune activité enregistrée pour ce projet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh every 30 seconds
        setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>