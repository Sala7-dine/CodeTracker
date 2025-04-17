<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Seuil d'inactivité
    |--------------------------------------------------------------------------
    |
    | Durée en secondes après laquelle un utilisateur est considéré inactif.
    | Par défaut: 120 secondes (2 minutes)
    |
    */
    'inactivity_threshold' => env('CODETRACK_INACTIVITY_THRESHOLD', 120),
    
    /*
    |--------------------------------------------------------------------------
    | Réessais automatiques
    |--------------------------------------------------------------------------
    |
    | Indique si les activités échouées doivent être automatiquement réessayées
    | 
    */
    'retry_failed_activities' => env('CODETRACK_RETRY_FAILED', true),
];