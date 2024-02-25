<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->longText('about')->nullable();
            $table->enum('gender',User::$gender)->nullable();
            $table->string('phone')->nullable();
            $table->enum('role',User::$role)->nullable();
            $table->longText('address')->nullable();
            $table->longText('resume')->nullable();
            $table->integer('experience')->nullable();
            $table->longText('profile_pic')->nullable();
            $table->longText('education')->nullable();
            $table->enum('skills',User::$skills)->nullable();
            $table->timestamp('email_verified_at')->nullable();            
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
