<?php

use App\Models\Job_Seeker;
use App\Models\JobSeeker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_seekers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender',JobSeeker::$gender);
            $table->string('email')->unique();
            $table->string('phone');
            $table->longText('address');
            $table->longText('resume');
            $table->integer('experience');
            $table->longText('profile_pic');
            $table->longText('education');
            $table->longText('skills');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
