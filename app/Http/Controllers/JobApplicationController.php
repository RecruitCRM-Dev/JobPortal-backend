<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Models\Job;
class JobApplicationController extends Controller
{
   //TODO: do authorization here 
    /**
     * Store a newly created resource in storage.
     */
    public function store(JobApplicationRequest $request)
    {
        $validatedData = $request->validated();
        $job_id = $validatedData['job_id'];
        $job = Job::find($job_id);
        $job->jobapplication()->create($validatedData);
        return response()->json('The job application has been submitted successfully');
    }

    public function checkUserHasApplied(Request $request, string $userId, string $jobId) {

        $jobApplication = JobApplication::where('job_id', $jobId)
        ->where('user_id', $userId)
        ->first();

        if ($jobApplication) {
            return response()->json(['message' => 'Already applied for the job!'], 409);
        }
    
        return response()->json(['message' => 'User has not applied for the job.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $job_application =  JobApplication::find($id);
        if(!$job_application){
            return response()->json(['Job application not found'],404);
        }
        $job_application->delete();
        return response()->json(['message'=>'Job Application Deleted Successfully!']);
    }
} 