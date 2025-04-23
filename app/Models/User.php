<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'username',
        'bio',
        'profile_image',
        'phone',
        'website',
        'country',
        'city',
        'preferences',
        'email',
        'password',
        'api_key',
        'role',
        'is_active'
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
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the projects for the user.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
    /**
     * Determine if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Determine if the user account is active.
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Get the user's profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Utilisez les initiales pour l'avatar par défaut
        $name = $this->firstname . ' ' . $this->lastname;
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . "&color=ffffff&background=6366f1&size=150";
    }

    /**
     * Automatically hash the password when it's being set
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
