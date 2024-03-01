<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employer extends Authenticatable{
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'employer';

    protected $fillable = ['name', 'email', 'about', 'password', 'address', 'profile_pic', 'experience'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
