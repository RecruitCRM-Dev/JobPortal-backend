<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Resources\JobDetailResource;
use App\Http\Resources\JobApplicationResource;

class EmployerPostedJobsController extends Controller
{
    
    public function index(Employee $employer)
    {
        $jobs = $employer->jobs()->paginate(10);
        return JobDetailResource::collection($jobs);
    }

    public function show(Employee $employer, Job $job)
    {   
        $users = JobApplication::with(['user'])
        ->where('job_id', $job->id)
        ->get();
       
        // return JobApplicationResource::collection([$users, $job]);
        return response()->json(['job' => $job, 'users' => $users]);
    }
}
