<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_path',
        'file_name',
        'duration',
        'activity_time',
        'last_activity_time',
        'activity_status',
        'inactive_gap',
        'language',
        'lines',
        'stats',
        'project_id',  // Assurez-vous que cette ligne est présente
        'user_id'
    ];
    
    protected $casts = [
        'stats' => 'array',
        'activity_time' => 'datetime',
        'last_activity_time' => 'datetime',
        'inactive_gap' => 'integer'
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

    
    public function getFormattedDuration()
    {
        $seconds = $this->duration;
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        
        return sprintf('%dh %dm %ds', $hours, $minutes % 60, $seconds % 60);
    }
    
    public function getFormattedInactiveGap()
    {
        if (!$this->inactive_gap) {
            return '0m';
        }
        
        $seconds = $this->inactive_gap;
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        
        return sprintf('%dh %dm', $hours, $minutes % 60);
    }
    
    public function isResumed()
    {
        return $this->activity_status === 'resumed';
    }
    
    public function scopeActive($query)
    {
        return $query->where('activity_status', 'active');
    }
    
    public function scopeResumed($query)
    {
        return $query->where('activity_status', 'resumed');
    }
    
    public function scopeWithInactiveGaps($query)
    {
        return $query->where('inactive_gap', '>', 0);
    }
}