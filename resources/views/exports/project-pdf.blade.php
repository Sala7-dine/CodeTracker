<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $project->name }} - Rapport de statistiques</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px;
            background-color: #fff;
        }
        
        /* En-tête avec bannière de couleur */
        .header {
            position: relative;
            margin-bottom: 40px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #4f46e5, #818cf8);
            border-radius: 4px;
        }
        
        h1 {
            color: #4f46e5;
            margin: 25px 0 10px 0;
            font-size: 28px;
        }
        
        .project-description {
            color: #6b7280;
            margin-bottom: 10px;
            font-style: italic;
        }
        
        .export-date {
            color: #9ca3af;
            font-size: 12px;
        }
        
        /* Sections */
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            position: relative;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 15px;
            font-size: 18px;
            padding-left: 12px;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            bottom: 2px;
            width: 4px;
            background-color: #4f46e5;
            border-radius: 2px;
        }
        
        /* Cartes */
        .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            border: 1px solid #f3f4f6;
        }
        
        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 14px;
        }
        
        table, th, td {
            border: 1px solid #e5e7eb;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        
        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        /* Vue d'ensemble - Stats principales */
        .stats-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .stat-box {
            text-align: center;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
            flex: 1;
            margin: 0 5px;
            border: 1px solid #e5e7eb;
        }
        
        .stat-box:first-child {
            margin-left: 0;
        }
        
        .stat-box:last-child {
            margin-right: 0;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .stat-value {
            color: #111827;
            font-size: 18px;
            font-weight: bold;
        }
        
        /* Barres de langage */
        .language-box {
            margin-bottom: 15px;
        }
        
        .language-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        
        .language-name {
            font-weight: 600;
            color: #4b5563;
        }
        
        .language-stats {
            color: #6b7280;
            font-size: 13px;
        }
        
        .language-bar-bg {
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .language-bar-fill {
            height: 100%;
            border-radius: 4px;
        }
        
        /* Définir les couleurs pour les différents langages */
        .lang-js { background-color: #fbbf24; }
        .lang-php { background-color: #8b5cf6; }
        .lang-html { background-color: #ef4444; }
        .lang-css { background-color: #3b82f6; }
        .lang-python { background-color: #10b981; }
        .lang-default { background-color: #6366f1; }
        
        /* Pied de page */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            padding-top: 15px;
            border-top: 1px solid #f3f4f6;
        }
        
        .logo {
            font-weight: bold;
            color: #4f46e5;
        }
        
        /* Graphiques simplifiés (représentation statique) */
        .activity-grid {
            display: flex;
            height: 120px;
            align-items: flex-end;
            padding: 10px 0;
            margin: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .activity-bar {
            flex: 1;
            margin: 0 2px;
            background-color: #818cf8;
            border-radius: 4px 4px 0 0;
        }
        
        .activity-labels {
            display: flex;
            justify-content: space-between;
            color: #6b7280;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $project->name }}</h1>
        @if($project->description)
            <p class="project-description">{{ $project->description }}</p>
        @endif
        <div class="export-date">Exporté le {{ $exportDate }}</div>
    </div>
    
    <!-- Vue d'ensemble -->
    <div class="section">
        <h2 class="section-title">Vue d'ensemble</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Temps de codage</div>
                <div class="stat-value">{{ $formattedTime }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Fichiers</div>
                <div class="stat-value">{{ $totalFiles }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Lignes de code</div>
                <div class="stat-value">{{ number_format($totalLines) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Moyenne quotidienne</div>
                <div class="stat-value">{{ number_format($totalHours / max(1, count(array_filter($activityData))), 1) }}h</div>
            </div>
        </div>
    </div>
    
    <!-- Activité par jour -->
    <div class="section">
        <h2 class="section-title">Activité par jour</h2>
        <div class="card">
            <div class="activity-grid">
                @php 
                    $maxHours = max($activityData) > 0 ? max($activityData) : 1;
                @endphp
                
                @foreach($activityData as $day => $hours)
                    @php 
                        $heightPercentage = ($hours / $maxHours) * 100;
                        $opacity = 0.3 + ($heightPercentage / 100) * 0.7; // Opacité entre 0.3 et 1 selon la hauteur
                    @endphp
                    <div class="activity-bar" style="height: {{ max(5, $heightPercentage) }}%; opacity: {{ $opacity }};"></div>
                @endforeach
            </div>
            
            <table style="margin-top: 20px;">
                <tr>
                    <th>Jour</th>
                    <th>Heures de code</th>
                </tr>
                @foreach($activityData as $day => $hours)
                    <tr>
                        <td>{{ $day }}</td>
                        <td>{{ $hours }} h</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    
    <!-- Répartition par langage -->
    <div class="section">
        <h2 class="section-title">Répartition par langage</h2>
        <div class="card">
            @php $totalTime = $languages->sum('time_ms'); @endphp
            
            @foreach($languages as $language)
                @php 
                    $percentage = $totalTime > 0 ? ($language->time_ms / $totalTime) * 100 : 0;
                    $formattedLangTime = \App\Models\Language::formatTime($language->time_ms);
                    
                    // Déterminer la couleur de la barre selon le langage
                    $langClass = 'lang-default';
                    
                    $langName = strtolower($language->name);
                    if (strpos($langName, 'javascript') !== false || strpos($langName, 'js') !== false) {
                        $langClass = 'lang-js';
                    } elseif (strpos($langName, 'php') !== false) {
                        $langClass = 'lang-php';
                    } elseif (strpos($langName, 'html') !== false) {
                        $langClass = 'lang-html';
                    } elseif (strpos($langName, 'css') !== false) {
                        $langClass = 'lang-css';
                    } elseif (strpos($langName, 'python') !== false) {
                        $langClass = 'lang-python';
                    }
                @endphp
                
                <div class="language-box">
                    <div class="language-header">
                        <span class="language-name">{{ $language->name }}</span>
                        <span class="language-stats">{{ $formattedLangTime }} ({{ round($percentage) }}%) • {{ number_format($language->lines) }} lignes</span>
                    </div>
                    <div class="language-bar-bg">
                        <div class="language-bar-fill {{ $langClass }}" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
    <div class="footer">
        <span class="logo">CODE</span>TRACKER • Rapport généré le {{ $exportDate }} • Tous droits réservés
    </div>
</body>
</html>