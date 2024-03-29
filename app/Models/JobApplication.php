<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =[
        'job_id',
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
