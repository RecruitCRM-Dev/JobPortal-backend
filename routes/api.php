<?php

use App\Http\Controllers\FilterController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LatestJobController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MyJobController;
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
    return $request->user();
});

Route::post('register/user', [AuthController::class, 'register']);
Route::post('login/user', [AuthController::class, 'login']);
Route::post('logout/user', [AuthController::class, 'logout']);

Route::apiResource('jobs/latest',LatestJobController::class)->only(['index']);

Route::apiResource('jobs', JobController::class)->only(['index','show']);

Route::apiResource('user/profile', UserProfileController::class)->only(['show','update','destroy']);

Route::apiResource('myJobs',MyJobController::class)->only(['index']);

Route::apiResource('job/application',JobApplicationController::class)->only(['store','destroy']);
