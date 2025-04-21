<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'user_id',
        'directory',
        'description',
        'environment_info',
    ];
    
    protected $casts = [
        'environment_info' => 'array',
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            Log::info('Creating project', [
                'name' => $project->name,
                'user_id' => $project->user_id
            ]);
        });
        
        static::created(function ($project) {
            Log::info('Project created', [
                'id' => $project->id,
                'name' => $project->name,
                'user_id' => $project->user_id
            ]);
        });
    }
    
    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the activities for the project.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
    
    /**
     * Get the languages for the project.
     */
    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }
    
    /**
     * Get the total number of files in the project.
     */
    public function getTotalFiles()
    {
        return $this->languages()->sum('files');
    }
    
    /**
     * Get the total number of lines of code in the project.
     */
    public function getTotalLines()
    {
        return $this->languages()->sum('lines');
    }
    
    /**
     * Get the total time spent on the project in milliseconds.
     */
    public function getTotalTime()
    {
        return $this->languages()->sum('time_ms');
    }
}