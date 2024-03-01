<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\FilterJobs;
class Job extends Model
{
    use HasFactory;
    // protected static function booted()
    // {
    //     static::addGlobalScope(new FilterJobs);
    // }
    public static array $category =[
        'IT',
        'Finance',
        'Sales',
        'Marketing',
        'HR'
    ];
    public static array $status =[
        'Active',
        'Expired'
    ];

    public static array $jobType = [
        'Full Time',
        'Part Time',
        'Internship',
        'Freelancing',
    ];

        protected $fillable = [
        'title',
        'description',
        'responsibilities',
        'category',
        'experience',
        'type',
        'salary',
        'location',
        'status'
    ];

    public function employer():BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobapplication():HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeFilter(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = strtolower($search); // Convert search term to lowercase
            $query->where(function ($query) use ($search) {
                $query->where('LOWER(title) like ?', ['%' . $search . '%'])
                    ->orWhere('LOWER(description) like ?', ['%' . $search . '%'])
                    ->orWhereHas('employer', function($query) use($search){
                        $query->whereRaw('LOWER(name) like ?', ['%' . $search . '%']);
                    });
            });
        })->when($filters['min_salary'] ?? null, function ($query, $minSalary) {
            $query->where('salary', '>=', $minSalary);
        })->when($filters['max_salary'] ?? null, function ($query, $maxSalary) {
            $query->where('salary', '<=', $maxSalary);
        })->when($filters['experience'] ?? null, function ($query, $experience) {
            $query->where('experience', '<',$experience);
        })->when($filters['category'] ?? null, function ($query, $category) {
            $query->where('category', $category);
        });
    }

    public function scopeLatest(Builder|QueryBuilder $query): void{
        $query->orderBy('created_at', 'desc');
    }
}
