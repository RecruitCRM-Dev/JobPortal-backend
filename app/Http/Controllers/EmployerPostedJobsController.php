<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPostingRequest;
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

    //TODO: how to uniquely define a job ?
    public function store(JobPostingRequest $request, Employee $employer) {

        $validatedData = $request->validated();
        $job = $employer->jobs()->create($validatedData);
        return response()->json(['message' => 'Job Posted Successfully!'], 200);
    }

    public function update(Request $request, Employee $employer, Job $job) {

        $request->validate([
            'userId' => 'required',
            'status' => 'required',
        ]);
    
        $userId = $request->input('userId');
        $status = $request->input('status');

        $jobApplication = JobApplication::where('job_id', $job->id)
        ->where('user_id', $userId)
        ->get();
        
        $jobApplication->status = $status;
    
        return response()->json(['message' => 'Status changed successfully.'], 200);
    }
}
