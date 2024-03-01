<?php

use App\Http\Controllers\Employer\EmployersAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\User\AuthController;
use App\Http\Resources\RegisterResource;
use App\Http\Controllers\MyJobController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\LatestJobController;
use App\Http\Controllers\UsersProfileController;
use App\Http\Controllers\JobApplicationController;
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


Route::prefix('user')->group(function () {

    //Auth Routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
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



