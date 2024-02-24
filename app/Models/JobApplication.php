<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =[
        'job_id',
        'job_seeker_id',
        'status',
        'deleted_at'
    ];
    public static array $status =[
        'Just_Applied',
        'ResumeViewed',
        'Underconsideration',
        'Rejected',
        'Selected'
    ];
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function job_seeker(): BelongsTo
    {
        return $this->belongsTo(JobSeeker::class);
    }
}
