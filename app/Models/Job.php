<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    public static array $category =[
        'IT',
        'Finance',
        'Sales',
        'Marketing'
    ];
    public static array $status =[
        'Active',
        'Expired'
    ];
    public function employee():BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    public function jobapplication():HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
