<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostingRequest;
use App\Models\Job;
use App\Models\Employer;
use App\Models\User;
use App\Notifications\StatusNotification;
use App\Notifications\StatusUpdateNotification;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Resources\JobDetailResource;
use Illuminate\Support\Facades\Notification;
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
        //checking that employer try to access other employer posted jobs or not?
        if ($job->employer_id !== $employer->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // $this->authorize('update', $employer);
        $users = JobApplication::with(['user'])
            ->where('job_id', $job->id)
            ->get();

        $totalJobApplications = $users->count();

        // return JobApplicationResource::collection([$users, $job]);
        return response()->json(['job' => $job, 'users' => $users, 'job_application_count' => $totalJobApplications]);
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

        if ($job->employer_id !== $employer->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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

        if(!$jobApplication){
            return response()->json(['message'=> 'User has not applied for this job'], 404);
        }

        $jobApplication->status = $status;
        $jobApplication->save();
        Notification::send($user, new StatusUpdateNotification($jobApplication));
        return response()->json(['message' => 'Status changed successfully.'], 200);
    }

    public function updateJobStatus(Request $request, Employer $employer, Job $job)
    {
        $job_status = Job::$status;
        $request->validate([
            'status' => ['required', 'in:' . implode(',', $job_status)],
        ]);

        if ($job->employer_id !== $employer->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $status = $request->input('status');

        $job_ = Job::where('id', $job->id)
            ->first();

        $job_->status = $status;
        $job_->save();
        return response()->json(['message' => 'Job Status changed successfully.'], 200);
    }
}
