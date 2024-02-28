<?php

use App\Http\Controllers\CandidateProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\RegisterResource;
use App\Http\Controllers\MyJobController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\LatestJobController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\EmployerAuthController;
use App\Http\Controllers\EmployerPostedJobsController;
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\UploadFileController;

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
    return new RegisterResource($request->user(),$token);
});

Route::post('register/user', [AuthController::class, 'register']);
Route::post('login/user', [AuthController::class, 'login']);
Route::post('logout/user', [AuthController::class, 'logout']);

Route::apiResource('jobs/latest',LatestJobController::class)->only(['index']);
Route::apiResource('jobs', JobController::class)->only(['index','show']);
Route::apiResource('user/profile', UserProfileController::class)->only(['show','update','destroy']);
Route::post('user/profile/update/{id}', [UserProfileController::class, 'updateUser']);
Route::apiResource('myJobs',MyJobController::class)->only(['index']);
Route::apiResource('job/application',JobApplicationController::class)->only(['store','destroy']);
Route::get('/user/{userId}/applied/{jobId}', [JobApplicationController::class, 'checkUserHasApplied']);

//  Employer Routes 

// Authentication
Route::post('register/employer', [EmployerAuthController::class, 'register']);
Route::post('login/employer', [EmployerAuthController::class, 'login']);
Route::post('logout/employer', [EmployerAuthController::class, 'logout']);

Route::apiResource('employer/profile', EmployerProfileController::class)->except(['index','update']);
Route::post('employer/profile/update/{id}', [EmployerProfileController::class, 'updateEmployer']);

// Route::put('employer/{employer}/job/{job}/user/{user}', [EmployerPostedJobsController::class, 'changeTheStatusOfCandidate']);
Route::apiResource('employer.job', EmployerPostedJobsController::class)
    ->scoped();


 