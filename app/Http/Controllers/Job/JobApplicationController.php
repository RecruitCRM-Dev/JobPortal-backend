<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;

class JobApplicationController extends Controller
{
    public function index( Request $request, User $user)
    {
        $user_id = $user->id;

        $filters = $request->only([
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category',
            'status'
        ]);

        $job_applications = JobApplication::where('user_id', $user_id)
            ->whereHas('job', function ($query) use ($filters) {
                $query->filter($filters);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with('job')
            ->get();

        return response()->json(['job_applications' => $job_applications]);
    }

    public function show(Request $request,User $user, Job $job)
    {
        $jobApplication = JobApplication::where('job_id', $job->id)
        ->where('user_id', $user->id)
        ->first();

        if ($jobApplication) {
            return response()->json(['message' => 'Already applied for the job!'], 409);
        }

        return response()->json(['message' => 'User has not applied for the job.'], 200);
    }

    public function store(JobApplicationRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $job_id = $validatedData['job_id'];
        $job = Job::find($job_id);

        if($job){
            $job->jobapplication()->create($validatedData);
            return response()->json('The job application has been submitted successfully');
        }else {
            return response()->json(['message' => 'Job not found'],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(int $id)
    // {
    //     $job_application =  JobApplication::find($id);
    //     if(!$job_application){
    //         return response()->json(['Job application not found'],404);
    //     }
    //     $job_application->delete();
    //     return response()->json(['message'=>'Job Application Deleted Successfully!']);
    // }

}
