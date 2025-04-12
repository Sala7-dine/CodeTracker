<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    
    protected $fillable = ['project_id', 'name', 'files', 'lines', 'time_ms'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function getFormattedTime()
    {
        return $this->formatTime($this->time_ms);
    }
    
    public static function formatTime($milliseconds)
    {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        
        return sprintf('%dh %dm %ds', $hours, $minutes % 60, $seconds % 60);
    }
}