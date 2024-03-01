<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPostingRequest;
use App\Models\Job;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Resources\JobDetailResource;

class EmployerPostedJobsController extends Controller
{

    public function index(Employer $employer)
    {
        // $this->authorize('update', $employer);
        $jobs = $employer->jobs()->get();
        return JobDetailResource::collection($jobs);
    }

    public function show(Employer $employer, Job $job)
    {

        // $this->authorize('update', $employer);
        $users = JobApplication::with(['user'])
            ->where('job_id', $job->id)
            ->get();

        // return JobApplicationResource::collection([$users, $job]);
        return response()->json(['job' => $job, 'users' => $users]);
    }

    //TODO: how to uniquely define a job ?
    public function store(JobPostingRequest $request, Employer $employer)
    {

        // $this->authorize('update', $employer);
        $validatedData = $request->validated();
        $job = $employer->jobs()->create($validatedData);
        return response()->json(['message' => 'Job Posted Successfully!', 'job_id' => $job->id], 200);
    }

    public function update(Request $request, Employer $employer, Job $job)
    {

        // $this->authorize('update', $employer);
        $request->validate([
            'userId' => 'required',
            'status' => 'required',
        ]);

        $userId = $request->input('userId');
        $status = $request->input('status');

        $user = User::find($userId);

        //if user is not found on given userId
        if (!$user) {
            return response()->json(['message' => 'User not exists'],404);
        }

        $jobApplication = JobApplication::where('job_id', $job->id)
            ->where('user_id', $userId)
            ->first();

        $jobApplication->status = $status;
        $jobApplication->save();
        return response()->json(['message' => 'Status changed successfully.'], 200);
    }
}
