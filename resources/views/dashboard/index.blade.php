<!DOCTYPE html>
<html>
<head>
    <title>CodeTrack Dashboard</title>
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
        <div class="row">
            <div class="col-12">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Statistiques globales</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Fichiers:</span>
                                    <span class="card-stats">{{ $globalStats['totalFiles'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Lignes:</span>
                                    <span class="card-stats">{{ $globalStats['totalLines'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Temps total:</span>
                                    <span class="card-stats">{{ $globalStats['formattedTime'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">Fichier actuel</h5>
                            </div>
                            <div class="card-body">
                                @if($globalStats['currentFile'])
                                    <div class="mb-2">
                                        <strong>Nom:</strong> 
                                        <span class="card-stats">{{ $globalStats['currentFile']['name'] }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Chemin:</strong>
                                        <div class="text-truncate small">
                                            {{ $globalStats['currentFile']['path'] }}
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Langage:</strong> 
                                        <span class="card-stats">{{ $globalStats['currentFile']['language'] }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Lignes:</strong> 
                                        <span class="card-stats">{{ $globalStats['currentFile']['lines'] }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Temps passé:</strong> 
                                        <span class="card-stats">{{ $globalStats['currentFile']['formattedTime'] }}</span>
                                    </div>
                                @else
                                    <div class="alert alert-info">Aucun fichier actif récemment</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                                    Projets
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
                                    Activités récentes
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="projects" role="tabpanel">
                                <div class="row">
                                    @forelse($projects as $project)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title mb-0">
                                                        <a href="{{ route('dashboard.project', $project->id) }}">
                                                            {{ $project->name }}
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Fichiers:</span>
                                                            <span class="card-stats">{{ $project->getTotalFiles() }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Lignes:</span>
                                                            <span class="card-stats">{{ $project->getTotalLines() }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Temps total:</span>
                                                            <span class="card-stats">{{ \App\Models\Language::formatTime($project->getTotalTime()) }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <h6>Langages:</h6>
                                                    @forelse($project->languages as $language)
                                                        <div class="language-card p-2 bg-light">
                                                            <strong>{{ $language->name }}</strong>
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
                                                <div class="card-footer text-muted">
                                                    <small>Dernière mise à jour: {{ $project->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                Aucun projet enregistré pour le moment.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="activities" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Projet</th>
                                                <th>Fichier</th>
                                                <th>Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentActivities as $activity)
                                                <tr class="activity-row">
                                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>
                                                        <a href="{{ route('dashboard.project', $activity->project->id) }}">
                                                            {{ $activity->project->name }}
                                                        </a>
                                                    </td>
                                                    <td title="{{ $activity->file_path }}">{{ $activity->file_name }}</td>
                                                    <td>{{ $activity->getFormattedDuration() }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        Aucune activité enregistrée pour le moment.
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh every 30 seconds
        setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>