<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'project_id', 'file_path', 'file_name', 'language',
        'duration', 'lines', 'activity_time', 'stats'
    ];
    
    protected $casts = [
        'stats' => 'array',
        'activity_time' => 'datetime',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function getFormattedDuration()
    {
        $seconds = $this->duration;
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        
        return sprintf('%dh %dm %ds', $hours, $minutes % 60, $seconds % 60);
    }
}