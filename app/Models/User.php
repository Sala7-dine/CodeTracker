<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'username',
        'bio',
        'phone',
        'website',
        'country',
        'city',
        'profile_image',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
    ];

    /**
     * Get the user's projects.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Get the URL of the user's profile image.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return Storage::url($this->profile_image);
        }
        
        // Generate an avatar image with initials
        $name = $this->firstname . ' ' . $this->lastname;
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=6366f1&color=fff&size=256";
    }
}
