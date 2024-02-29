<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPostingRequest;
use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Resources\JobDetailResource;

class EmployeePostedJobsController extends Controller
{
    
    public function index(Employee $employee)
    {
        $this->authorize('update', $employee);
        $jobs = $employee->jobs()->paginate(10);
        return JobDetailResource::collection($jobs);
    }

    public function show(Employee $employee, Job $job)
    {   

        $this->authorize('update', $employee);
        $users = JobApplication::with(['user'])
        ->where('job_id', $job->id)
        ->get();
       
        // return JobApplicationResource::collection([$users, $job]);
        return response()->json(['job' => $job, 'users' => $users]);
    }

    //TODO: how to uniquely define a job ?
    public function store(JobPostingRequest $request, Employee $employee) {

        $this->authorize('update', $employee);
        $validatedData = $request->validated();
        $job = $employee->jobs()->create($validatedData);
        return response()->json(['message' => 'Job Posted Successfully!'], 200);
    }

    public function update(Request $request, Employee $employee, Job $job) {

        $this->authorize('update', $employee);
        $request->validate([
            'userId' => 'required',
            'status' => 'required',
        ]);
    
        $userId = $request->input('userId');
        $status = $request->input('status');

        $jobApplication = JobApplication::where('job_id', $job->id)
        ->where('user_id', $userId)
        ->first();

        $jobApplication->status = $status;
        $jobApplication->save();
        return response()->json(['message' => 'Status changed successfully.'], 200);
    }
}
