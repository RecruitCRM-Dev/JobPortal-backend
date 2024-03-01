<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\RegisterResource;
use App\Http\Controllers\MyJobController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\LatestJobController;
use App\Http\Controllers\UsersProfileController;
use App\Http\Controllers\EmployersAuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\EmployersProfileController;
use App\Http\Controllers\EmployerPostedJobsController;

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


Route::prefix('employer')->group(function(){
    Route::post('register', [EmployersAuthController::class, 'register']);
    Route::post('login', [EmployersAuthController::class, 'login']);
    Route::post('logout', [EmployersAuthController::class, 'logout']);
});



