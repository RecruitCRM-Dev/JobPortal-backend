<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employer extends Authenticatable implements MustVerifyEmail{
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;
    protected $guard = 'employer';

    protected $fillable = ['name', 'email', 'description', 'password', 'address', 'profile_pic', 'experience'];

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
