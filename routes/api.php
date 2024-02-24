<?php

use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MyJobController;

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
Route::apiResource('myjobs',MyJobController::class)
       ->only(['index']);
Route::apiResource('jobapplications',JobApplicationController::class);
Route::post('register/user', [AuthController::class, 'register']);
Route::post('login/user', [AuthController::class, 'login']);

Route::get('jobs/latest',[JobController::class,'index']);
Route::get('jobs/{id}', [JobController::class, 'show']);