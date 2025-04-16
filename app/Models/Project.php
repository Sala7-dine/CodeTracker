<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'user_id' , 'description', 'environment_info'];
    
    protected $casts = [
        'environment_info' => 'array',
    ];
    
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
    public function languages()
    {
        return $this->hasMany(Language::class);
    }
    
    public function getTotalFiles()
    {
        return $this->languages->sum('files');
    }
    
    public function getTotalLines()
    {
        return $this->languages->sum('lines');
    }
    
    public function getTotalTime()
    {
        return $this->languages->sum('time_ms');
    }
}