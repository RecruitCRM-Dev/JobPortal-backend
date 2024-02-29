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
use App\Http\Controllers\EmployeesAuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\EmployeesProfileController;
use App\Http\Controllers\EmployeePostedJobsController;

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


// Authentication


// User - Job Seeker
Route::post('register/user', [AuthController::class, 'register']);
Route::post('login/user', [AuthController::class, 'login']);
Route::post('logout/user', [AuthController::class, 'logout']);

// Employee
Route::post('register/employee', [EmployeesAuthController::class, 'register']);
Route::post('login/employee', [EmployeesAuthController::class, 'login']);
Route::post('logout/employee', [EmployeesAuthController::class, 'logout']);


// Job lists
 
Route::apiResource('jobs/latest',LatestJobController::class)->only(['index']);
Route::apiResource('jobs', JobController::class)->only(['index','show']);

// User
// Route::middleware('auth:sanctum')->group(function() {
//     Route::apiResource('user/profile', UserProfileController::class)->only(['show','update','destroy']);
//     Route::post('user/profile/{profile}/update/', [UserProfileController::class, 'updateUser']);
// });



Route::middleware('auth:sanctum')->group(function() {
    // Route::apiResource('employee/profile', EmployerProfileController::class)->except(['index','update']);
    // Route::post('employees/profile/update/{id}', [EmployerProfileController::class, 'updateEmployer']);


    // Jobs 
    Route::apiResource('myJobs',MyJobController::class)->only(['index']);
    Route::apiResource('job/application',JobApplicationController::class)->only(['store','destroy']);
    Route::get('/user/{userId}/applied/{jobId}', [JobApplicationController::class, 'checkUserHasApplied']);


    // Users    
    Route::post('users/{user}', [UsersProfileController::class, 'update'])->name('users.update');
    Route::apiResource('users',UsersProfileController::class)->except(['update']);
   
    
    // Employees
    Route::apiResource('employees.jobs', EmployeePostedJobsController::class)
    ->scoped();
    Route::post('employees/{employee}', [EmployeesProfileController::class, 'update'])->name('employees.update');
    Route::apiResource('employees',EmployeesProfileController::class)->except(['update']);

});



