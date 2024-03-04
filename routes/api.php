<?php

use App\Http\Controllers\Employer\EmployersAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerificationController;
use App\Mail\PasswordResetLink;
use App\Models\User;
use App\Http\Controllers\Job\StatusNotificationController;
use App\Http\Controllers\User\UserInsights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\User\AuthController;
use App\Http\Resources\RegisterResource;
use App\Http\Controllers\Job\JobApplicationController;
use App\Http\Controllers\User\UsersProfileController;
use App\Http\Controllers\Employer\EmployersProfileController;
use App\Http\Controllers\Employer\EmployerPostedJobsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $token = $request->header('Authorization');

    if ($token && str_starts_with($token, 'Bearer ')) {
        $token = substr($token, 7); // Remove 'Bearer ' prefix
    }
    return new RegisterResource($request->user(), $token);
});

//List All Jobs
Route::get('jobs/latest', [JobController::class, 'getLatestJobs']);

Route::apiResource('jobs', JobController::class)->only(['index', 'show']);

Route::prefix('user')->group(function () {

    //Auth Routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->get('profile/{user}', [UsersProfileController::class, 'show']);

    Route::middleware(['auth:sanctum', 'check_user_authorization'])->group(function () {
        //Profile Routes
        Route::post('profile/{user}', [UsersProfileController::class, 'update']);
        Route::get('profile/{user}/insights', [UserInsights::class, 'getInsights']);
        Route::get('{user}/notifications', [StatusNotificationController::class, 'getLatestNotifications']);


        //Job Post Routes
        Route::apiResource('{user}/jobs', JobApplicationController::class);
        // api/user/{user}/jobs/{job} -> checking User has applied or not -> GEt
        // api/user/{user}/jobs -> list of all jobs that user has applied ->GET
        // api/user/{user}/jobs ->job apply -> pOST

    });
});


Route::prefix('employer')->group(function () {

    //Auth Routes
    Route::post('register', [EmployersAuthController::class, 'register']);
    Route::post('login', [EmployersAuthController::class, 'login']);
    Route::post('logout', [EmployersAuthController::class, 'logout']);

    Route::middleware(['auth:sanctum', 'check_employer_authorization'])->group(function () {
        //Profile Routes
        Route::get('profile/{employer}', [EmployersProfileController::class, 'show']);
        Route::post('profile/{employer}', [EmployersProfileController::class, 'update']);

        //Job Post Routes
        Route::apiResource('{employer}/jobs', EmployerPostedJobsController::class);
    });
});

//Email Verification routes
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

//Password Reset Routes
Route::post('forgot-password', [PasswordResetController::class, 'sendResetPasswordLink']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

Route::get('user/{user_id}/notifications', [StatusNotificationController::class, 'getLatestNotifications']);
