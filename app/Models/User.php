<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'name', 'email', 'password', 'gender', 'phone', 'address', 'resume', 'experience', 'profile_pic', 'education', 'skills' ];

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
    ];
    public static array $gender =[
        'Male',
        'Female',
        'Others'
    ];
    public static array $role =[
        'Software Developer',
        'Graphic Designer',
        'Sales',
        "HR",
        "Business",
        "Project Manager",
        "Marketing"
    ];
    public static array $skills = ['HTML5','Javascript','Vue','Laravel','ReactJS', 'Python', 'Java', 'Django'];
    public function jobapplication(): HasMany
    {
        return  $this->hasMany(JobApplication::class);
    }
}
